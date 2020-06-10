function addEventListeners() {
    $('.conversation-preview').click(selectConversation);
    $('.conversation-preview').click(openConversation);
    $('#back-btn').click(closeConversation);
    $('#send-msg-form').submit(sendMessage);
    $('#send-msg-form textarea').keydown(checkSend);
    $('.delete-msg').click(deleteMessage);
    listenMessages();

    enableAutoResizeTextarea(document.querySelector('#send-msg-form textarea'), 0, 150);

    if(document.location.pathname.startsWith('/conversation')) {
        openConversation();
        scrollToBottomMessage(true);
    }
}

function checkSend(event) {
    if(event.which == 13 && !event.shiftKey) {
        event.preventDefault();
        $(this).parent().submit();
    }
}

function openConversation() {
    if(this == window) {
        let user_id = $('#send-msg-form').attr('data-user-id');
        let existingPreview = $(`li[data-user-id="${user_id}"]`);
        console.log(existingPreview.length);
        if(existingPreview.length != 0)
            if(existingPreview.attr('data-is-friends') == 1) {
                $('#send-msg-form').find('textarea, button').removeAttr('disabled')
                sendAjaxRequest('post', `/api/messages/${user_id}/seen`, null, null);
            }
            else
                $('#send-msg-form').find('textarea, button').attr('disabled', 'disabled')
        else
            $('#send-msg-form').find('textarea, button').removeAttr('disabled')
        existingPreview.find('p').removeClass('font-weight-bolder');
        existingPreview.addClass('selected');
    } else {
        let user_id = $(this).attr('data-user-id');
        let is_friends = $(this).attr('data-is-friends');
        $(this).find('p').removeClass('font-weight-bolder');
        $(this).addClass('selected');
        if(is_friends) {
            sendAjaxRequest('post', `/api/messages/${user_id}/seen`, null, null);
            $('#send-msg-form').find('textarea, button').removeAttr('disabled')
        }
        else
            $('#send-msg-form').find('textarea, button').attr('disabled', 'disabled')
    }

    if ($(window).width() < 991.98) {
        $('#conversations').addClass('conversation-open');
        $('#message-history').addClass('conversation-open');
        $('#bottom-bar').addClass('d-none');
    }
}

function closeConversation() {
    if ($(window).width() < 991.98) {
        $('#conversations').removeClass('conversation-open');
        $('#message-history').removeClass('conversation-open');
        $('#bottom-bar').removeClass('d-none');
    }
}

function selectConversation() {
    let user_id = this.dataset.userId;
    let message_list = $('#message-list');
    $('.conversation-preview').removeClass('selected');
    message_list.empty();

    let message_history = $('#message-history div').first();
    let user_photo = $(this).find('img').attr('src');
    let user_name = $(this).find('section h3').text();
    message_history.empty();
    message_history.append(`<button id="back-btn" class="btn"><i class="fas fa-arrow-left"></i></button>`)
    message_history.append(
        `<img class="profile-picture-xsmall"
            src="${user_photo}"
        alt="profilePic" />`);
    message_history.append(`<h2 class="py-3 m-0 text-truncate">${user_name}</h2>`);

    $('#back-btn').click(closeConversation);

    $('#send-msg-form').attr('data-user-id', user_id);

    message_list.append(
    `<div id="message-spinner" class="d-flex justify-content-center spinner transformable my-4">
        <div class="spinner-border" role="status"></div>
     </div>`);
    sendAjaxRequest('get', `/api/messages/${user_id}`, null, onMessagesReceived);
}

function onMessagesReceived() {
    let messages = JSON.parse(this.response);
    let message_list = $('#message-list');
    message_list.empty();
    messages.forEach(message => {
        message_list.append(`<li data-message-id="${message.message_id}" class="my-1 mx-3 p-2 ${message.was_sent?"message-sent":"message-received"}">${message.was_sent? `<i class="delete-msg clickable fa fa-trash" aria-hidden="true"></i>` : ""}<p class="m-0">${message.content}</p></li>`);
        $(`li[data-message-id="${message.message_id}"]`).find('.delete-msg').click(deleteMessage);
    })
    scrollToBottomMessage(true);
}

function sendMessage(event) {
    event.preventDefault();

    let user_id = this.dataset.userId;
    if(user_id == null)
        return;
    let fields = formValues(this);
    $('#send-msg-form').find('textarea').val("");
    $('#send-msg-form').find('textarea').height('auto');
    if(fields.content == "")
        return;
    let message_list = $('#message-list');
    message_list.append(`<li class="my-1 mx-3 p-2 message-sent"><i class="delete-msg clickable fa fa-trash" aria-hidden="true"></i><p class="m-0">${fields.content}</p></li>`);
    let element = $('#message-list li').last();
    scrollToBottomMessage(true);
    let request = sendAjaxRequest(`post`, `/api/messages/${user_id}`, fields, messageSentHandler);
    request.element = element;
}

function messageSentHandler() {
    let message = JSON.parse(this.response);
    
    this.element.attr('data-message-id', message.message_id);
    $(this.element).find('.delete-msg').click(deleteMessage);

    let user_tab = $(`li[data-user-id="${message.receiver_id}"]`);
    if (user_tab.length == 0) {
        $('#preview-list > div').remove();
        let newPreview = createConversationPreview(message, true);
        $('#preview-list').prepend(newPreview);
    } else {
        user_tab.find('p').html("<i class=\"far fa-check-circle\"></i> " + message.content);
        user_tab.find('small').text(message.timestamp);
        user_tab.parent().prepend(user_tab);
    }
}

function deleteMessage() {
    let message_id = $(this).parent().attr('data-message-id');

    sendAjaxRequest(`delete`, `/api/messages/${message_id}`, null, null);
    $(this).parent().remove();
}

function listenMessages() {
    let user_id = $('#user-info').attr('data-id');
    Echo.private('messages.' + user_id).listen('NewMessage', (content) => {
        let user_tab = $(`li[data-user-id="${content.message.sender_id}"]`);

        if(user_tab.length != 0) {
            user_tab.find('p').text(content.message.content);
            user_tab.find('small').text(content.message.timestamp);
            user_tab.parent().prepend(user_tab);
        }

        if(user_tab.hasClass('selected')) {
            let message_list = $('#message-list');
            let isAtBottom = checkIfAtBottom();
            message_list.append(`<li class="my-1 mx-3 p-2 message-received"><p class="m-0">${content.message.content}</p></li>`);
            sendAjaxRequest('post', `/api/messages/${content.message.sender_id}/seen`, null, null);
            scrollToBottomMessage(isAtBottom);
        } else if (user_tab.length == 0) {
            $('#preview-list > div').remove();
            let newPreview = createConversationPreview(content.message, false);
            $('#preview-list').prepend(newPreview);
        } else {
            user_tab.find('p').addClass('font-weight-bolder');
        }
    });

    Echo.private('seen.' + user_id).listen('SeenMessage', (content) => {
        let other_id = content.own_id;

        let user_tab = $(`li[data-user-id="${other_id}"]`);
        if(user_tab.length != 0) {
            console.log(user_tab.find('p').html());
            let text = user_tab.find('p');
            text.html(text.html().replace(`<i class="far fa-check-circle"></i>`, `<i class="fas fa-check-circle"></i>`));
        }
        console.log(content);
    });
}

function createConversationPreview(message, sent) {
    return `<li data-user-id=${sent ? message.receiver_id : message.sender_id} class="conversation-preview py-3 px-1 m-0 rounded-0 border-bottom border-black row align-items-center">
        <div class="col-2">
            <img class="profile-picture-small"
                    src="${sent ? message.receiver.avatar : message.author.avatar}"
                    alt="profilePic" />
        </div>
        <section class="col-7 text-truncate">
            <h3 class="m-0 text-truncate font-weight-bold conversation-username">${sent ? message.receiver.name : message.author.name}</h3>
            <p class="m-0 text-truncate ${sent ? "" : "font-weight-bolder"}">${(sent ? `<i class="far fa-check-circle"></i> ` : "") + message.content}</p>
        </section>
        <small class="col-3 text-center">${message.timestamp}</small>
    </li>`;
}

function scrollToBottomMessage(isAtBottom) {
    let message_list = document.querySelector('#message-list')
    if(isAtBottom)
        message_list.scrollTop = message_list.scrollHeight;
}

function checkIfAtBottom() {
    let message_list = document.querySelector('#message-list')
    return message_list.scrollTop == message_list.scrollHeight - message_list.offsetHeight;
}

addEventListeners();