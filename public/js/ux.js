function addEventListeners() {
    $('.copy-button').click(copyTextArea);
}

function copyTextArea() {
    copyToClipboard(this.parentNode.querySelector('textarea').value);
}

function copyToClipboard(toCopy) {
    const el = document.createElement('textarea');
    el.value = toCopy;
    document.body.appendChild(el);
    el.select();
    document.execCommand('copy');
    document.body.removeChild(el);
}

/* Generic Functions */
function validateInputLength(input, type, minLength, maxLength, setSuccess) {
    let length = input.value.length;
    if (length < minLength) {
        let missing = minLength - length;
        if (minLength === 1)
            setInputError(input, `${type} cannot be empty!`)
        else setInputError(input, `${type} is too small, try adding abooout ${missing} character${missing===1?'':'s'} to it.`)
        return false;
    }
    else if (length > maxLength) {
        let overflow = length - maxLength;
        setInputError(input, `${type} is too large, try shrinking it by abooout ${overflow} character${overflow===1?'':'s'}.`)
        return false;
    }
    else {
        if (setSuccess)
            setInputSuccess(input, '');
        return true;
    }
}

function setInputError(element, message) {
    clearInputFeedback(element);    
    element.classList.add('is-invalid');

    let div = document.createElement('div');
    div.classList.add('invalid-feedback');
    div.classList.add('text-left');
    div.innerHTML = message;
    element.parentNode.insertBefore(div, element.nextSibling);

}

function setInputSuccess(element, message) {
    clearInputFeedback(element);    
    element.classList.add('is-valid');

    let div = document.createElement('div');
    div.classList.add('valid-feedback');
    div.classList.add('text-left');
    div.innerHTML = message;
    element.parentNode.insertBefore(div, element.nextSibling);
}

function clearInputFeedback(element) {
    element.classList.remove('is-valid');
    element.classList.remove('is-invalid');

    $(element.parentNode).find('.invalid-feedback').remove();
    $(element.parentNode).find('.valid-feedback').remove();
}

function clearFormInputFeedback(form) {
    form.querySelectorAll('.is-valid, .is-invalid').forEach(clearInputFeedback);
}

function setLoading(button, text = "Loading...") {
    button.disabled = true;
    button.oldInnerHTML = button.innerHTML;
    button.innerHTML = 
        '<div class="d-flex align-items-center">' +
            '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>' +
            ' ' + text
        '</div>';
}

function clearLoading(button) {
    if (button.oldInnerHTML === undefined)
        return;
    button.disabled = false;
    button.innerHTML = button.oldInnerHTML;
    delete button.oldInnerHTML;
}

function addButtonAnimation(button, newColor, newText, oldText) {
    if (oldText === undefined)
        oldText = button.innerHTML;
    button.style.backgroundColor = newColor;
    button.style.borderColor = newColor;
    button.innerHTML = newText;
    setTimeout(() => {
        button.style.transition = "background-color 2.7s linear, border-color 2.7s ease";
        button.style.backgroundColor = "";
        button.style.borderColor = "";
    }, 250);
    setTimeout(() => {
        if (button.innerHTML === newText)
            button.innerHTML = oldText;
        button.style.transition = "";
    }, 3000);
}

function previewImageInput(input, dest) {
    let reader = new FileReader();
    reader.onload = function(){
        dest.src = reader.result;
    };
    reader.readAsDataURL(input.files[0]);
}

function setAtBottomHandler(handler) {
    $(window).on("scroll.bottom", function() {
        if($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
            handler();
        }
    });
}

function unsetAtBottomHandler() {
    $(window).off("scroll.bottom");
}

addEventListeners();

