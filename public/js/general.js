const CONTENT_MAXLEN = 1000;
const ANNOUNCEMENT_MAXLEN = 500;
const GROUP_MAXLEN = 255;
const EMAIL_MAXLEN = 255;
const NAME_MAXLEN = 255;
const LOCATION_MAXLEN = 25;
const PASSWORD_MINLEN = 8;
const PASSWORD_MAXLEN = 128;
const BANREASON_MAXLEN = 1000;

let userInfo = document.querySelector('#user-info');
const userID = userInfo.dataset.id;
const userName = userInfo.dataset.name;
const isAdmin = userInfo.dataset.isAdmin;
const loggedIn = userID != null;

function addEventListeners() {
	/* Content */
	$('.react-btn-upvote').click(toggleUpvote);
	$('.react-btn-downvote').click(toggleDownvote);

	/* Posts */
	$('.report-content-btn').click(reportContent);
	$('.edit-post-form').submit(editPost);
	$('.delete-post-form').submit(deletePost);
	$('[data-link-url]').click(openPost);

	/* Hamburger */
	$('#hamburger-btn').click(() => $("#collapsable-sidebar").addClass('open'));
	$('#hamburger-btn-close').click(() => $("#collapsable-sidebar").removeClass('open'));

	/* Settings */
	$('.settings-toggler').click(toggleSettings);

	/* Notifications */
	$("#dropdown-notifications").click((event) => event.stopPropagation());
	$('.notification-result').click(eraseNotification);
}

//Notification menu
function eraseNotification() {
	let notificationID =  this.getAttribute('data-notificationID');
	let numNotifications = $('.num-notifications');
	this.remove();
	numNotifications.text(getNumNotifications() - 1);
	if(getNumNotifications() === "0")
		$("#dropdown-notifications").html('<p class="my-3 text-center h4">No notifications.</p>');


	//Send delete request
	sendAjaxRequest('delete', '/api/notifications/' + notificationID, {id: notificationID}, null) // handler);
}

function getNumNotifications() {
	return $('.num-notifications').first().text();
}

function openPost() {
	location.href = this.dataset.linkUrl;
}

/*** Text area autoresized ***/
function enableAutoResizeTextarea(textarea, padding = 0, maxHeight = 1000) {
	function resize () {
		textarea.style.height = 'auto';
		let height = Math.min((textarea.scrollHeight + padding), maxHeight);
		if(textarea.value != "")
			textarea.style.height = height +'px';
	}
	function delayedResize () {
		window.setTimeout(resize, 0);
	}

	textarea.addEventListener('change', resize);
	textarea.addEventListener('cut', delayedResize);
	textarea.addEventListener('paste', delayedResize);
	textarea.addEventListener('drop', delayedResize);
	textarea.addEventListener('keydown', delayedResize);

	resize();
}

/*** Content ***/
function toggleUpvote() {
	if (!loggedIn)
		return;

	let content_id = this.dataset.contentId;
	if(this.classList.contains('react-btn-highlight')) {
		sendAjaxRequest('delete', '/api/content/' + content_id + '/appraisal', null, null);
		this.classList.remove('react-btn-highlight');
		let likesElement = $(this).find('span');
		likesElement.text(likesElement.text()-1);
	} else {
		sendAjaxRequest('post', '/api/content/' + content_id + '/appraisal', {'positive': 1}, null);
		this.classList.add('react-btn-highlight');
		let likesElement = $(this).find('span');
		likesElement.text(parseInt(likesElement.text())+1);
		if($(this).next().hasClass('react-btn-highlight')) {
			$(this).next().removeClass('react-btn-highlight');
			let dislikesElement = $(this).next().find('span');
			dislikesElement.text(dislikesElement.text()-1);
		}
	}
}

function toggleDownvote() {
	if (!loggedIn)
		return;

	let content_id = this.dataset.contentId;
	if(this.classList.contains('react-btn-highlight')) {
		sendAjaxRequest('delete', '/api/content/' + content_id + '/appraisal', null, null);
		this.classList.remove('react-btn-highlight');
		let dislikesElement = $(this).find('span');
		dislikesElement.text(dislikesElement.text()-1);
	} else {
		sendAjaxRequest('post', '/api/content/' + content_id + '/appraisal', {'positive': 0}, null);
		this.classList.add('react-btn-highlight');
		let dislikesElement = $(this).find('span');
		dislikesElement.text(parseInt(dislikesElement.text())+1);
		if($(this).prev().hasClass('react-btn-highlight')) {
			$(this).prev().removeClass('react-btn-highlight');
			let likesElement = $(this).prev().find('span');
			likesElement.text(likesElement.text()-1);
		}
	}
}

/*** Post, search Settings ***/
function toggleSettings(name) {
	$(this.dataset.target).toggleClass('hide-settings');
	$(this).toggleClass('hide-settings');
	$(this).find('i').toggleClass('fa-angle-down').toggleClass('fa-angle-up');
}

/*** Posts ***/
function getPostElement(id) {
	return document.querySelector('.post[data-content-id="' + id + '"]');
}

function reportContent() {
	sendAjaxRequest('post', `/api/content/${this.dataset.contentId}/report`, null, null);

	this.setAttribute("disabled", true );
	this.textContent = "Reported!";
}

function editPost(event) {
	event.preventDefault();

	let post_id = this.dataset.contentId;
	let fields = formValues(this);
	clearFormInputFeedback(this);
	if (!validateInputLength(getFormInput(this, 'content'), "Post", 1, CONTENT_MAXLEN))
		return
	setLoading($(`#edit-content${post_id} button[type="submit"]`)[0]);
	let request = sendAjaxRequest('put', '/api/post/' + post_id, fields, postEditedHandler);
	request.post_id = post_id;
}

function postEditedHandler() {
	let response = JSON.parse(this.responseText);
	let modal = $(`#edit-content${this.post_id}`);
	let post = $(`.post[data-content-id=${this.post_id}] .content-text`);
	clearLoading(modal.find('button[type="submit"]')[0]);
	if (this.status === 200 || this.status === 201) {
		modal.modal('hide');
		post.html(response.content.formatted_content);
	}
	else setInputError(modal.find('textarea')[0], response.message);
}

function deletePost(event) {
	event.preventDefault();

	let post_id = this.dataset.contentId;
	sendAjaxRequest('delete', '/api/post/' + post_id, null, postDeletedHandler);
	$('#delete-content' + post_id).modal('hide');

	if (!location.pathname.startsWith('/post'))
		getPostElement(post_id).remove();
	//else location.href = "/feed";
}

function postDeletedHandler() {
	if (location.pathname.startsWith('/post'))
		location.href = "/feed";
}

addEventListeners();


////////////////////////
/*   Html templates   */
////////////////////////
function contentDropDownStr(content, canEdit, canDelete, canReport) {
	if (!canEdit && !canDelete)
		return "";
	return `<div class="dropleft p-2">
				<button class="btn text-dark" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>                
				<div class="dropdown-menu">` +
		(canEdit ? `<button class="dropdown-item" data-toggle="modal" data-target="#edit-content${content.content_id}">Edit</button>` : '') +
		(canDelete ? `<button class="dropdown-item" data-toggle="modal" data-target="#delete-content${content.content_id}">Delete</button>` : '') +
		(canReport ? `<button class="dropdown-item report-content-btn" data-content-id="${content.content_id}">Report</button>` : '') +
				`</div>
			</div>`;
}

function editModalStr(isPost, content) {
	return	`<div id="edit-content${content.content_id}" class="modal fade" tabindex="-1" role="dialog">
				<form class="${isPost ? 'edit-post-form':'edit-comment-form'} modal-dialog modal-lg" data-content-id="${content.content_id}">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Edit ${isPost ? 'Post':'Comment'}</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
						</div>
						<div class="modal-body form-group">
							<label for="message-text56" class="col-form-label">${isPost ? 'Post':'Comment'} Content:</label>
							<textarea class="form-control" name="content" id="message-text${content.content_id}" rows="4">${escapeHtml(content.content)}</textarea>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Save changes</button>
						</div>
					</div>
				</form>
			</div>`;
}

function deleteModalStr(isPost, content) {
	return `<div id="delete-content${content.content_id}" class="modal fade" tabindex="-1" role="dialog">
				<form class="${isPost ? 'delete-post-form':'delete-comment-form'} modal-dialog modal-lg" data-content-id="${content.content_id}">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Delete Post</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
						</div>
						<div class="modal-body form-group">
							<p>Are you sure you want to delete this ${isPost ? 'Post':'Comment'}? This action is irreversible.</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-danger">Delete</button>
						</div>
					</div>
				</form>
			</div>`;
}

function avatarStr(user) {
	return `<a href="${user.url}" class="profile-avatar p-2 text-truncate">
				<img class="profile-picture-small"  src="${user.avatar}" alt="${user.name}'s avatar">
				<h3 class="d-inline h6">${user.name}</h3>
			</a>`;
}

function makePost(content) {
	let can_edit = loggedIn && (content.author.user_id == userID);
	let can_delete = can_edit || isAdmin;
	let can_report = loggedIn;
	let element = htmlToElement(
		`<article class="card shadow-sm my-3 post" data-content-id="${content.content_id}">
				<header class="p-2 card-title bg-light d-flex align-items-center m-0">
					${avatarStr(content.author)}
					<small class="ml-auto mr-2 text-muted">${content.content_date_short}</small>
					${contentDropDownStr(content, can_edit, can_delete, can_report)}
					${editModalStr(true, content)}
					${deleteModalStr(true, content)}
				</header>
				<a href="${content.url}" class="card-body">    
					<p class="content-text card-text text-prewrap text-dark m-0">${escapeHtml(content.content)}</p>
				</a>
			
				<div class="d-flex post-stats border-top">
					<span class="react-btn react-btn-upvote ${loggedIn?'clickable':''} ${content.is_liked?'react-btn-highlight':''}" data-content-id="${content.content_id}"><i class="fas fa-thumbs-up"></i> <span class="p-0">${content.likes}</span></span>
					<span class="react-btn react-btn-downvote ${loggedIn?'clickable':''}  ${content.is_disliked?'react-btn-highlight':''}" data-content-id="${content.content_id}"><i class="fas fa-thumbs-down"></i> <span class="p-0">${content.dislikes}</span></span>
					<a href="${content.url}" class="btn-comments ml-auto"><i class="fas fa-comment"></i> <span class="p-0">${content.post.comments}</span></a>
				</div>
			</article>`
	);

	$(element).find('.react-btn-upvote').click(toggleUpvote);
	$(element).find('.react-btn-downvote').click(toggleDownvote);
	$(element).find('.edit-post-form').submit(editPost);
	$(element).find('.delete-post-form').submit(deletePost);
	$(element).find('.report-content-btn').click(reportContent);

	return element;
}

function makeComment(content) {
	console.log(content)
	let can_edit = loggedIn && (content.author.user_id == userID);
	let can_delete = can_edit || isAdmin;
	let can_report = loggedIn;

	let element = htmlToElement(
		`<article class="card shadow-sm my-3 mx-md-3 comment" data-content-id="${content.content_id}">
				<header class="p-2 card-title bg-light d-flex align-items-center m-0">
					${avatarStr(content.author)}
					<small class="ml-auto mr-2 text-muted">${content.content_date_short}</small>
					${contentDropDownStr(content, can_edit, can_delete, can_report)}
					${editModalStr(false, content)}
					${deleteModalStr(false, content)}
				</header>
				<div class="card-body" data-post-id="${content.content_id}">
					<p class="content-text card-text text-prewrap m-0">${escapeHtml(content.content)}</p>
				</div>

				<div class="d-flex post-stats border-top">
					<span class="react-btn react-btn-upvote ${loggedIn?'clickable':''} ${content.is_liked?'react-btn-highlight':''}" data-content-id="${content.content_id}"><i class="fas fa-thumbs-up"></i> <span class="p-0">${content.likes}</span></span>
					<span class="react-btn react-btn-downvote ${loggedIn?'clickable':''} ${content.is_disliked?'react-btn-highlight':''}" data-content-id="${content.content_id}"><i class="fas fa-thumbs-down"></i> <span class="p-0">${content.dislikes}</span></span>
				</div>
			</article>`
	);

	$(element).find('.react-btn-upvote').click(toggleUpvote);
	$(element).find('.react-btn-downvote').click(toggleDownvote);
	$(element).find('.edit-comment-form').submit(editComment);
	$(element).find('.delete-comment-form').submit(deleteComment);
	$(element).find('.report-content-btn').click(reportContent);

	return element;

}
