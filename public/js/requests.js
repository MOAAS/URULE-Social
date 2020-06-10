function addEventListeners() {
	$('.friend-accept').click(acceptFriendRequest);
	$('.friend-deny').click(denyFriendRequest);
}

function acceptFriendRequest() {
	let user_id = this.dataset.userId;
	sendAjaxRequest('post', '/api/friends/requests/' + user_id, { accept: 1 }, null);
	clearFriendRequest(user_id, true);
}

function denyFriendRequest() {
	let user_id = this.dataset.userId;
	sendAjaxRequest('post', '/api/friends/requests/' + user_id, { accept: 0 }, null);
	clearFriendRequest(user_id, false);
}

function clearFriendRequest(user_id, accept) {
	let element = document.querySelector('button[data-user-id="'+user_id+'"]').parentNode;
	if (accept) {
		element.classList.add("bg-success");
		element.style.transform = "translate(150%, 0)";
	}
	else {
		element.classList.add("bg-danger");
		element.style.transform = "translate(-150%, 0)";
	}
	$(element).find('button').remove();
	setTimeout(() => {
		element.remove();
		if ($('#friend-requests-page > ul > li').length != 0)
			return;
		document.querySelector('#friend-requests-page ul').appendChild(emptyList());
	}, 800);
}

////////////////////////
/*   Html templates   */
////////////////////////

function emptyList() {
    return htmlToElement('<div class="container text-center mt-5 h3">No friend requests available.</div>');
}


addEventListeners();
