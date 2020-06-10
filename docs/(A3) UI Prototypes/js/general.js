$('#hamburger-btn').click(() => $("#collapsable-sidebar").addClass('open'));
$('#hamburger-btn-close').click(() => $("#collapsable-sidebar").removeClass('open'));



$('#post-settings-toggler').click(toggleSettings.bind(this, "post"));
$('#search-settings-toggler').click(toggleSettings.bind(this, "search"));

function toggleSettings(name) {
    $('#' + name + '-settings').toggleClass('hide-settings');
    $('#' + name + '-settings-toggler i').toggleClass('fa-angle-down');
    $('#' + name + '-settings-toggler i').toggleClass('fa-angle-up');
    $('#' + name + '-settings-label').toggleClass('d-none');
}

$('.clickable-post').click(() => document.location.href = "post.php");
$('.conversation-preview').click(openConversation); 
$('#back-btn').click(closeConversation); 

function openConversation() {
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