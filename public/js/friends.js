function addEventListeners() {
    $('.unfriend-btn').click(unfriendUser);
    $('.ungroup-btn').click(ungroupUser);    
    $('.add-to-group-btn').click(addGroupMember);    
    $('#add-group-form').submit(addGroup);  
    $('.edit-group-form').submit(renameGroup);    
    $('.delete-group-form').submit(deleteGroup);  
}

function unfriendUser() {
    if (this.textContent != "Confirm")
        return addButtonAnimation(this, "var(--danger)", "Confirm");
	let userId = this.dataset.userId;
	sendAjaxRequest('delete', `/api/friends/${userId}`, null, null);
	removeRegularFriend(this.parentNode, userId);
}

function ungroupUser() {
	let userId = this.dataset.userId;
	let groupId = this.dataset.groupId;
    sendAjaxRequest('delete', `/api/friends/groups/${groupId}/${userId}`, null, null);
	removeGroupFriend(this.parentNode, userId);
}

function addGroupMember() {
	let userId = this.dataset.userId;
	let groupId = this.dataset.groupId;
    sendAjaxRequest('put', `/api/friends/groups/${groupId}/${userId}`, null, null);

    let userAvatar = this.firstElementChild.innerHTML;
    let avatar = htmlToElement(`<a href="http://localhost:8000/users/${userId}-${userName.split(' ').join('')}" class="p-2 text-truncate profile-avatar">${userAvatar}</a>`);

    addToGroup(userId, groupId, avatar);
    this.remove();
}

function renameGroup(event) {
    event.preventDefault();    
    let groupId = this.dataset.groupId;
    
    let fields = formValues(this);
    let input = getFormInput(this, "name");
    let modal = $(`#edit-group${groupId}`);
    clearInputFeedback(input);
    if (!validateInputLength(input, "Group name", 1, GROUP_MAXLEN))
        return;
    if (document.querySelector(`.friend-group[data-group-id="${groupId}"] h4`).textContent === fields.name)
        modal.modal('hide'); // Hide modal if group name didnt change
    else if (groupExists(fields.name))
        setInputError(input, "Duplicate group name!");
    else {
        sendAjaxRequest('put', `/api/friends/groups/${groupId}`, fields, null);

        modal.modal('hide');
        getGroup(groupId).find('h4').text($(`#group-name${groupId}`).get(0).value);    
    }
}

function addGroup(event) {
    event.preventDefault();    

    let fields = formValues(this);
    let input = getFormInput(this, "name");
    clearInputFeedback(input);
    if (!validateInputLength(input, "Group name", 1, GROUP_MAXLEN))
        return;
    if (groupExists(fields.name))
        setInputError(input, "Duplicate group name!");
    else {
        setLoading(this.querySelector('#add-group-btn'));
        sendAjaxRequest('post', '/api/friends/groups', fields, groupAddedHandler);
    }
}

function deleteGroup(event) {
    event.preventDefault();
    let groupId = this.dataset.groupId;
    
    sendAjaxRequest('delete', `/api/friends/groups/${groupId}`, formValues(this), null);

    $(`#delete-group${groupId}`).modal('hide');

    let groupElement = getGroup(groupId).get(0);
    let friendElement = groupElement.nextElementSibling;

    // Remove group members
    while (friendElement && friendElement.classList.contains('friend-item')) {
        let nextElement = friendElement.nextElementSibling;
        removeGroupFriend(friendElement, friendElement.dataset.userId);
        friendElement = nextElement;
    }

    // Remove empty message and group
    groupElement.nextElementSibling.remove();
    groupElement.remove();
}

////////////////////////
/*      Handlers      */
////////////////////////

function groupAddedHandler() {
    location.reload(true);
}

function groupRenamedHandler() {
    if (this.status == 200) return;
    setInputError(this.form.querySelector('#add-group-input'), JSON.parse(this.responseText).message)
}

////////////////////////
/*  Helper functions  */
////////////////////////

function getGroup(groupId) {
    return $(`.friend-group[data-group-id="${groupId}"]`);
}

function getMainGroup() {
    return $('.friend-group:not([data-group-id])');
}

function groupExists(name) {
    let groupTitles = document.querySelectorAll('.friend-group[data-group-id] h4');
    for (let i = 0; i < groupTitles.length; i++) {
        if (name == groupTitles[i].textContent)
            return true;
    }
    return false;
}


function removeFriend(element) {
    if (element == null)
        return;
    let next = element.nextElementSibling;
    let previous = element.previousElementSibling;        
    if ((next == null || next.classList.contains('friend-group')) && previous.classList.contains('friend-group')) {
        element.parentNode.insertBefore(emptyGroup(), element)
    }
    element.remove();
}

function removeRegularFriend(element, friendId) {
    $(element).find('button').remove();
	element.style.animation = "shrinking-animation .3s"
    $(`.add-to-group-btn[data-user-id="${friendId}"]`).remove();
    setTimeout(() => removeFriend(element), 300);
}

function removeGroupFriend(element, friendId) {
    let groupItem = element.previousElementSibling;
    while (!groupItem.classList.contains('friend-group'))
        groupItem = groupItem.previousElementSibling;
    let groupId = groupItem.dataset.groupId;
    let avatarElement = element.querySelector('.profile-avatar');

    // AddToGroup modal update
    $(`#add-to-group${groupId} ul`).append(addToGroupEntry(groupId, friendId, avatarElement));

    // All friends have been deleted from their groups
    if ($(`.friend-item[data-user-id=${friendId}]`).length == 1) {
        addToGroup(friendId, undefined, avatarElement);
    }

    removeFriend(element);
}

function addToGroup(userId, groupId, avatarElement) {
    let group = groupId === undefined ? getMainGroup() : getGroup(groupId);
    
    $(group).find(`+ .friend-group-empty`).remove();
  
    // Remove from regular friend list
    removeFriend($(`.unfriend-btn[data-user-id="${userId}"]`).parent().get(0));
    group.after(friendEntry(userId, groupId, avatarElement));
    group.find(`#add-to-group-btn[data-user-id="${userId}"]`).remove();
}

////////////////////////
/*   Html templates   */
////////////////////////


function friendEntry(userId, groupId, avatarElement) {
    let element = htmlToElement(
        `<li class="list-group-item d-flex align-items-center friend-item" data-user-id="${userId}">` + 
            `${avatarElement.outerHTML}` +
            (groupId === undefined ? 
            `<button class="btn btn-primary ml-auto unfriend-btn" data-user-id="${userId}">Unfriend</button>` :
            `<button class="btn btn-danger ml-auto ungroup-btn" data-user-id="${userId}" data-group-id="${groupId}"><i class="fa fa-trash"></i></button>`) + 
        `</li>`);
    $(element).find('.unfriend-btn').click(unfriendUser);
    $(element).find('.ungroup-btn').click(ungroupUser);
    return element;
}

function addToGroupEntry(groupId, userId, avatarElement) {
    let element = htmlToElement(
        `<li class="add-to-group-btn list-group-item d-flex align-items-center" data-group-id="${groupId}" data-user-id="${userId}">` +
            `<span class="m-2 text-truncate profile-avatar">${avatarElement.innerHTML}</span>` + 
        `</li>`
    );
    element.addEventListener('click', addGroupMember);
    return element;
}

function emptyGroup() {
    return htmlToElement('<li class="list-group-item friend-group-empty"><p class="m-0 h-3">This list is empty.</p></li>');
}

addEventListeners();

