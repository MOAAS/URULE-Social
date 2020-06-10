function addEventListeners() {
	$('#comment-form').submit(addComment);
	$('.edit-comment-form').submit(editComment);
	$('.delete-comment-form').submit(deleteComment);

	if (loggedIn)
		enableAutoResizeTextarea(document.querySelector('#comment-input'), 40);

	updateRuleSettingsHeight();
}

function updateRuleSettingsHeight() {
	let textarea = document.querySelector('#rule-description')
	let settings = document.querySelector('#rule-description-settings')

	if (textarea == null)
		return;

	textarea.style.height = Math.min(textarea.scrollHeight, 350) + "px";
	settings.style.maxHeight = Math.min(textarea.scrollHeight + 100, 450) + "px";
	console.log(textarea.style)
	console.log(settings.style)
}

function updateCommentCount() {
	$('.post .btn-comments span').text($('.comment').length);
}

function addComment(event) {
	event.preventDefault();

	let fields = formValues(this);
	clearFormInputFeedback(this);
	if (!validateInputLength(getFormInput(this, "content"), "Comment", 1, CONTENT_MAXLEN))
		return;
	let post_id = this.dataset.postId;
	setLoading(document.querySelector('#comment-btn'));
	sendAjaxRequest('post', '/api/post/' + post_id + '/comment', fields, commentAddedHandler);
}

function commentAddedHandler() {
	clearLoading(document.querySelector('#comment-btn'));
	let response = JSON.parse(this.responseText);
	console.log(response);
	if (this.status == 200 || this.status == 201) {
		let element = makeComment(response.content);

		$(`#no-comments-notice`).remove();
		$(`#comment-input`).val("");
		$(`#post-comments`).append(element);
		element.style.animation = "growing-animation .8s"
		$("html, body").animate({ scrollTop: $(document).height() }, 1000);
		updateCommentCount();
	} else {
		setInputError(document.querySelector('#comment-input'), response.message);
	}
}

function editComment(event) {
	event.preventDefault();
	let comment_id = this.dataset.contentId;
	let post_id = $('.post').get(0).dataset.contentId;

	let fields = formValues(this);
	clearFormInputFeedback(this);
	if (!validateInputLength(getFormInput(this, "content"), "Comment", 1, CONTENT_MAXLEN))
		return;
	setLoading($(`#edit-content${comment_id} button[type="submit"]`)[0]);
	let request = sendAjaxRequest('put', `/api/post/${post_id}/comment/${comment_id}`, fields, onCommentEdit);
	request.comment_id = comment_id;
}

function onCommentEdit() {
	let response = JSON.parse(this.responseText);
	let modal = $(`#edit-content${this.comment_id}`);
	let comment = $(`.comment[data-content-id=${this.comment_id}] .content-text`);
	clearLoading(modal.find('button[type="submit"]')[0]);
	if (this.status === 200 || this.status === 201) {
		modal.modal('hide');
		comment.html(response.content.formatted_content);
	}
	else setInputError(modal.find('textarea')[0], response.message);
}

function deleteComment(event) {
	event.preventDefault();
	let comment_id = this.dataset.contentId;
	let post_id = $('.post').get(0).dataset.contentId;

	sendAjaxRequest('delete', `/api/post/${post_id}/comment/${comment_id}`, null, null);
	$(`#delete-content${comment_id}`).modal('hide');

	let element = $(`.comment[data-content-id=${comment_id}]`);
	element.css('animation', 'shrinking-animation .8s');
	setTimeout(() => {
		element.remove();
		if($('.comment').length === 0)
			$('#post-comments').append(emptyCommentList());
		updateCommentCount();
	}, 800);
}

function atBottom() {
	let post_id = $('.post').get(0).dataset.contentId;
	console.log("Asking for comments!");
	unsetAtBottomHandler();
	sendAjaxRequest("get", `/api/post/${post_id}`, { offset: $('.comment').length, limit: 20 }, commentsReceivedHandler);
}

function commentsReceivedHandler() {
	let response = JSON.parse(this.responseText);
	if (this.status !== 200)
		return;
	console.log("Got comments!");
	console.log(response);
	if (response.length === 0) {
		$('.spinner').remove()
		return;
	}

	response.forEach((comment) => $('#post-comments').append(makeComment(comment)));
	setAtBottomHandler(atBottom);
}


setAtBottomHandler(atBottom);
addEventListeners();


////////////////////////
/*   Html templates   */
////////////////////////

function emptyCommentList() {
    return htmlToElement(`<div id="no-comments-notice" class="container text-center mt-5 h3">No one seems to have cared enough to comment. Be the first!</div>`);
}

