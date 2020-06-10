function addEventListeners() {
    $('.to-edit-info-btn').click(switchToEditInfo);
    $('.to-edit-email-btn').click(switchToEditEmail);
    $('.to-edit-password-btn').click(switchToEditPassword);

    $('#edit-profile-form').submit(editProfile);
    $('#delete-acc-btn').click(deleteAccount);
    $('#unfriend-btn').click(unfriendUser);
    $('#request-btn').click(requestUser);
    $('#accept-btn').click(acceptRequest);

    $('#picture-input').change(updateAvatarPreview)
    $('#banner-input').change(updateBannerPreview)
}

function switchToEditInfo() {
    $('#edit-info-form').removeClass('d-none');
    $('#edit-email-form').addClass('d-none');
    $('#edit-password-form').addClass('d-none');
    $('#delete-acc-btn').addClass('d-none');
    $('#edit-profile-form').attr('data-edit-action', 'info');
    setPhotoInputs(true)

    setButtonIfGoogleAcc($('#edit-profile-btn'), true)
}

function switchToEditEmail() {
    $('#edit-info-form').addClass('d-none');
    $('#edit-email-form').removeClass('d-none');
    $('#edit-password-form').addClass('d-none');
    $('#delete-acc-btn').removeClass('d-none');
    $('#edit-profile-form').attr('data-edit-action', 'email');
    setPhotoInputs(false)

    setButtonIfGoogleAcc($('#edit-profile-btn'), false)
}

function switchToEditPassword() {
    $('#edit-info-form').addClass('d-none');
    $('#edit-email-form').addClass('d-none');
    $('#edit-password-form').removeClass('d-none');
    $('#delete-acc-btn').removeClass('d-none');
    $('#edit-profile-form').attr('data-edit-action', 'password');
    setPhotoInputs(false)

    setButtonIfGoogleAcc($('#edit-profile-btn'), false)
}

function setPhotoInputs(enabled) {
    let fileLabels = $('label[for="picture-input"], label[for="banner-input"]');
    if (enabled) {
        fileLabels.addClass('image-input').css('pointer-events', '')
    }
    else {
        fileLabels.removeClass('image-input').css('pointer-events', 'none')
    }
}

function setButtonIfGoogleAcc(button, enable) {
    if (enable)
        button.removeAttr('disabled');
    else if (button.data('isGoogle'))
        button.attr('disabled', 'disabled');

}

function enableLoading(isLoading) {
    $('.to-edit-info-btn').prop('disabled', isLoading);
    $('.to-edit-email-btn').prop('disabled', isLoading);
    $('.to-edit-password-btn').prop('disabled', isLoading);

    if (isLoading)
        setLoading(document.querySelector('#edit-profile-btn'))
    else clearLoading(document.querySelector('#edit-profile-btn'));
}


function updateAvatarPreview() {
    previewImageInput(this, document.querySelector('#edit-profile-form .profile-picture'));
}

function updateBannerPreview() {
    previewImageInput(this, document.querySelector('#edit-profile-form .banner-photo'));
}


function editProfile(event) {
    event.preventDefault();
    let action = this.dataset.editAction;
    let fields = formValues(this);

    clearFormInputFeedback(this);


    switch (action) {
        case 'info':
            let nameValid = validateInputLength(getFormInput(this, 'name'), "User name", 1, NAME_MAXLEN);
            let locationValid = validateInputLength(getFormInput(this, 'location'), "User location", 0, LOCATION_MAXLEN)
            if (!nameValid || !locationValid)
                return;
            enableLoading(true);
            sendAjaxPostRequestWithFiles('/api/user/info', fields, infoEditedHandler);
            break;
        case 'email':
            if (!validateInputLength(getFormInput(this, 'new_email'), "Email address", 1, EMAIL_MAXLEN))
                return;
            enableLoading(true);
            sendAjaxRequest("put", "/api/user/email", fields, emailEditedHandler);
            break;
        case 'password':
            if (!validateInputLength(getFormInput(this, 'new_password'), "New password", PASSWORD_MINLEN, PASSWORD_MAXLEN))
                return;
            if (fields.new_password !== fields.new_password_confirmation) {
                setInputError(getFormInput(this, 'new_password_confirmation'), 'Passwords do not match!')
                return;
            }
            enableLoading(true);
            sendAjaxRequest("put", "/api/user/password", fields, passwordEditedHandler);
            break;
    }
}

function infoEditedHandler() {
    enableLoading(false);

    let response = JSON.parse(this.responseText);
    if (this.status !== 200)
        return;

    if (this.reqData.picture) {
        console.log("Reloading pic");
        let newAvatar = `${response.avatar}?${new Date().getTime()}`;
        $('.profile-picture, .profile-picture-small').attr('src', newAvatar);
    }

    if (this.reqData.banner) {
        console.log("Reloading banner");
        let newBanner = `${response.banner}?${new Date().getTime()}`;
        $('.banner-photo').attr('src', newBanner);
    }

    getFormInput(document, 'picture').value = null;
    getFormInput(document, 'banner').value = null;

    $('#edit-profile-modal').modal('hide');
    $('#profile-name, .profile-avatar h3').text(response.name);
    $('#profile-location').html("<i class=\"fa fa-map-marker-alt mr-2\"></i>" + (response.location ? escapeHtml(response.location) : "Unknown"));
    $('#profile-birthday').text("🎂 " + response.birthday_short);
}

function emailEditedHandler() {
    enableLoading(false);


    if (this.status !== 200) {
        let response = JSON.parse(this.responseText);
        if (this.status === 401)
            setInputError(getFormInput(document, "curr_password_mail"), response.message);
        if (this.status === 422)
            setInputError(getFormInput(document, "new_email"), response.errors.new_email);
        console.log(response);
    }
    else {
        $('#edit-profile-modal').modal('hide');
        getFormInput(document, "curr_password_mail").value = "";
        getFormInput(document, "new_email").value = "";
    }
}

function passwordEditedHandler() {
    enableLoading(false);

    if (this.status !== 200) {
        let response = JSON.parse(this.responseText);
        if (this.status === 422)
            setInputError(getFormInput(document, "curr_password_pass"), response.message);
    }
    else {
        $('#edit-profile-modal').modal('hide');
        getFormInput(document, "curr_password_pass").value = "";
        getFormInput(document, "new_password").value = "";
        getFormInput(document, "new_password_confirmation").value = "";
    }
}



function deleteAccount() {
    if (this.textContent === "Delete Account")
        addButtonAnimation(this, "", "Are you sure?", "Delete Account")
    else if (this.textContent === "Are you sure?")
        addButtonAnimation(this, "", "There is no going back...", "Delete Account")
    else if (this.textContent === "There is no going back...")
        addButtonAnimation(this, "", "Next one will seal the deal.", "Delete Account")
    else if (this.textContent === "Next one will seal the deal.")
        addButtonAnimation(this, "", "One final chance...", "Delete Account")
    else if (this.textContent === "One final chance...") {
        setLoading(this, "I guess this is it...");
        setTimeout(() => {
            clearLoading(this);
            addButtonAnimation(this, "", "Fine, I'll allow you to reconsider.", "Delete Account")
        }, 3000)
    }
    else if (this.textContent === "Fine, I'll allow you to reconsider.")
        addButtonAnimation(this, "", "Ok, that was fast.", "Delete Account");
    else if (this.textContent === "Ok, that was fast.")
        addButtonAnimation(this, "", "Really?", "Delete Account");
    else if (this.textContent === "Really?")
        addButtonAnimation(this, "", "Why?", "Delete Account");
    else if (this.textContent === "Why?")
        addButtonAnimation(this, "", "Whyyyy?", "Delete Account");
    else if (this.textContent === "Whyyyy?")
        addButtonAnimation(this, "", "WHYYYYYYYYY?", "Delete Account");
    else if (this.textContent === "WHYYYYYYYYY?")
        addButtonAnimation(this, "", "We'll all be sad to see you go...", "Delete Account");
    else if (this.textContent === "We'll all be sad to see you go...")
        addButtonAnimation(this, "", "But if that's what you want,", "Delete Account");
    else if (this.textContent === "But if that's what you want,")
        addButtonAnimation(this, "", "I really have no choice.", "Delete Account");
    else if (this.textContent === "I really have no choice.")
        addButtonAnimation(this, "", "Here's the real button.", "Delete Account");
    else if (this.textContent === "Here's the real button.")
        addButtonAnimation(this, "", " Delete Account ", "Delete Account");
    else if (this.textContent === " Delete Account ") {
        setLoading(this, "Goodbye.");
        submitRequest("/user", {}, "delete");
    }
}

function unfriendUser() {
    this.disabled = true;
    sendAjaxRequest("delete", `/api/friends/${this.dataset.userId}`, null, null);
}

function requestUser() {
    this.disabled = true;
    sendAjaxRequest("post", "/api/friends/requests/", {id: this.dataset.userId}, null);
}

function acceptRequest() {
    this.disabled = true;
    this.classList.remove("bg-success");
    sendAjaxRequest("post", `/api/friends/requests/${this.dataset.userId}`, {accept: 1}, null);
}

function atBottom() {
    let userID = document.querySelector('#profile-page').dataset.userId;
    console.log("Asking for posts!");
    unsetAtBottomHandler();
    sendAjaxRequest("get", `/api/users/${userID}`, { offset: $('.post').length, limit: 20 }, postsReceivedHandler);
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

    response.forEach((post) => $('#profile-posts').append(makePost(post)));
    setAtBottomHandler(atBottom);
}


setAtBottomHandler(atBottom);
addEventListeners();