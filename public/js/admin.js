function addEventListeners() {
	$('#announcement-form').submit(sendAddAnnoucementRequest);
	$('.delete-announcement-btn').click(sendDeleteAnnoucementRequest);
	$('.reset-content-btn').click(sendResetReportsRequest);
	$('.delete-content-btn').click(sendDeleteContentRequest);
	$('.ban-user-form').submit(sendBanUserRequest);
	$('.unban-user-form').submit(sendUnbanUserRequest);

	let announcementInput = document.querySelector('#announcement-input');
	if (announcementInput)
		enableAutoResizeTextarea(announcementInput);
}

function sendAddAnnoucementRequest(event){
	event.preventDefault();
	let form = document.querySelector('#announcement-form');
	let textarea = form.querySelector('#announcement-input');
	let fields = formValues(form);
	clearInputFeedback(textarea);
	if (!validateInputLength(textarea, "Announcement", 1, ANNOUNCEMENT_MAXLEN))
		return
	setLoading(document.querySelector('#announcement-btn'));
	sendAjaxRequest('post', '/api/admin/announcements/', fields, announcementAddedHandler);
}

function announcementAddedHandler(){
	clearLoading(document.querySelector('#announcement-btn'));
	console.log(this.responseText);
	let response = JSON.parse(this.responseText);
	console.log(response);
	if (this.status === 200 || this.status === 201){
		let element = makeAnnouncement(response);
		console.log(element);
		$("#announcement-input").val("");
		if($('.no-announcements').length !== 0){
			$("#announcement-list").empty();
		}
		$("#announcement-list").prepend(element);
		element.style.animation = "growing-animation .8s";
		$(element).find('.delete-announcement-btn').click(sendDeleteAnnoucementRequest);
	}
}

function sendDeleteAnnoucementRequest(){
	let announcement_id = this.dataset.announcementId;
	let element = document.querySelector('article[data-announcement-id="'+announcement_id+'"]');
	let parent = element.parentNode;
	sendAjaxRequest('delete', '/api/admin/announcements/' + announcement_id, null, null);
	element.remove();
	if($('.announcement').length === 0)
		parent.innerHTML = '<div class="container text-center mt-5 h5 no-announcements">No active announcements</div>';
}

function sendResetReportsRequest(){
	let content_id = this.dataset.contentId;
	console.log(content_id);
	sendAjaxRequest('delete','/api/admin/reports/'+ content_id, null, null);
	let elem = $('td[data-content-id="' + content_id + '"]');
	elem.empty();
	elem.append('Reports Cleared');
	let elem2 = elem.parent().children(":first");
	console.log(elem2);
	elem2.html(0);
}

function sendDeleteContentRequest(event){
	let post_id = this.dataset.postId;
	let comment_id = this.dataset.commentId;
	if(comment_id === undefined) {
		console.log("Post! " + post_id);
		sendAjaxRequest('delete', '/api/post/' + post_id, null, contentDeletedHandler);
	}
	else {
		console.log("Comment! " + comment_id + " " +post_id);
		sendAjaxRequest('delete', '/api/post/'+ post_id +'/comment/' + comment_id, null, contentDeletedHandler);
	}
}

function contentDeletedHandler(){
	let response = JSON.parse(this.responseText);
	let content_id = response.comment_id;
	if(content_id === undefined) {
		content_id = response.post_id;
		$('button[data-post-id="'+content_id+'"]').each(function() {
			console.log('gamer');
			let parent = $('button[data-post-id="'+content_id+'"]').parent();
			console.log(parent);
			parent.empty();
			parent.append('Deleted Content');
		});
	}
	else {
		let elem = $('td[data-content-id="' + content_id + '"]');
		elem.empty();
		elem.append('Deleted Content');
	}
}

function sendBanUserRequest(event){
	event.preventDefault();
	let user_id = this.dataset.userId;
	let fields = formValues(this);
	clearFormInputFeedback(this);
	if (!validateInputLength(getFormInput(this, "reason"), "Ban reason", 0, BANREASON_MAXLEN))
		return;
	sendAjaxRequest('put', '/api/users/' + user_id + '/ban', fields, null); //

	// Edit HTML
	$('#ban-user'+ user_id).modal('hide');
	$('button[data-user-id="'+user_id+'"]').each(function() {
		let elem = htmlToElement(`<button class="btn btn-success btn-sm ml-auto mr-2"  title="Unban user" data-toggle="modal" data-target="#unban-user${user_id}" data-user-id="${user_id}"> <i class="fas fa-unlock fa-fw"></i></button>`);
		$('#unban-user-reason'+user_id).text(fields.reason);
		this.replaceWith(elem);
	});

}

function sendUnbanUserRequest(event){
	console.log("gaming");
	event.preventDefault();
	let user_id =this.dataset.userId;
	sendAjaxRequest('put', '/api/users/' + user_id + '/unban', null,  null); //

	// Edit HTML
	$('#unban-user'+ user_id).modal('hide');
	$('button[data-user-id="'+user_id+'"]').each(function() {
		let elem = htmlToElement(`<button class="btn btn-danger btn-sm ml-auto mr-2"  title="Ban user"  data-toggle="modal" data-user-id="${user_id}" data-target="#ban-user${user_id}"> <i class="fas fa-gavel fa-fw"></i></button>`);
		this.replaceWith(elem);
	});
}


////////////////////////
/*   Html templates   */
////////////////////////

function makeAnnouncement(announcement) {
	return htmlToElement(
		`<article class="card shadow-sm mx-md-3 my-3 announcement" data-announcement-id="${announcement.announcement_id}">
		    <header class="p-0 card-title bg-light d-flex align-items-center m-0">
		        <div class="card-title bg-light m-0">
					${document.querySelector('.profile-avatar').outerHTML}
		        </div>
			    <small class="ml-auto mr-2 text-muted">${announcement.time_left} left</small>
		        <button class="btn text-dark delete-announcement-btn" data-toggle="dropdown" data-announcement-id="${announcement.announcement_id}"> <i class="fas fa-trash"></i></button>                
		    </header>
		    <div class="card-body">    
		        <p class="card-text text-prewrap m-0">${announcement.content}</p>
		    </div>
		</article>`
	);
}

	addEventListeners();