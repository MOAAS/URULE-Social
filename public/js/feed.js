function addEventListeners() {
	$('#post-form').submit(addPost);
	$('#rule-file').change(jsonRuleUpload);
	$('#post-image-input').change(postImgUpload);
	$('#post-image-area i').click(clearPostImg)

	if (loggedIn)
		enableAutoResizeTextarea(document.querySelector('#post-input'), 40);
}

function jsonRuleUpload() {
	let reader = new FileReader();
	let input = this;
	reader.onload = function() {
		$('#post-rules').val(reader.result);
		input.value = "";
	};
	reader.readAsText(input.files[0]);
}

function postImgUpload() {
	let img = document.querySelector("#post-image-preview");
	if (this.files && this.files[0]) {
		previewImageInput(this, img)
		img.alt = "Post Image Preview"
		document.querySelector("#post-image-area").classList.add("has-image");
	}
	else clearPostImg();
}

function clearPostImg(event) {
	if (event != null)
		event.preventDefault();

	let img = document.querySelector("#post-image-preview");
	img.alt = "";
	img.src = "";
	document.querySelector("#post-image-area").classList.remove("has-image");
}

function addPost(event) {
	event.preventDefault();

	let fields = formValues(this);
	clearFormInputFeedback(this);
	if (!validateInputLength(getFormInput(this, "content"), "Post", 1, CONTENT_MAXLEN))
		return;
	setLoading(document.querySelector('#post-btn'));
	sendAjaxPostRequestWithFiles('/api/post/', fields, postAddedHandler);
}

function postAddedHandler() {
	clearLoading(document.querySelector('#post-btn'));
	let response = JSON.parse(this.responseText);
	if (this.status == 200 || this.status == 201) {
		console.log(response);
		location.href = `/post/${response.content.content_id}-${response.content.author.name}`;
	}
	else {
		setInputError($('#post-rules').get(0), escapeHtml(response.message))
	}
}

function atBottom() {
	let url = (window.location.pathname === "/hot") ? "/api/hot" : "/api/feed";
	console.log("Asking for posts!");
	unsetAtBottomHandler();
	sendAjaxRequest("get", url, { offset: $('.post').length, limit: 20 }, postsReceivedHandler);
}

function postsReceivedHandler() {
	let response = JSON.parse(this.responseText);
	if (this.status !== 200)
		return;
	console.log("Got posts!");
	console.log(response);
	if (response.length === 0) {
		$('.spinner').remove()
		return;
	}


	response.forEach((post) => $('#post-list').append(makePost(post)));
	setAtBottomHandler(atBottom);
}


setAtBottomHandler(atBottom);
addEventListeners();



