function encodeForAjax(data) {
    if (data == null) return null;
    return Object.keys(data).map(function(k){
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
}

function toFormData(data) {
    let formData = new FormData();
    if (data == null) return null;
    for (let name in data) {
        formData.append(name, data[name]);
    }
    return formData;
}

function sendAjaxPostRequestWithFiles(url, data, handler) {
    $.ajax({
        url: url,
        beforeSend: function(request) {
            request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
        },
        data: toFormData(data),
        type: 'POST',
        contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
        processData: false, // NEEDED, DON'T OMIT THIS
        complete: (req) => {
            req.reqData = data;
            handler.call(req)
        },
    });
}

function sendAjaxRequest(method, url, data, handler) {
    if (data == null)
        data = {};

    let request = new XMLHttpRequest();
    if(method === "get")
        url += "?" + encodeForAjax(data);

    request.open(method, url, true);
    request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    request.reqData = data;

    console.log(data);

    if (handler != null)
        request.addEventListener('load', handler);
    if(method === "get")
        request.send();
    else request.send(encodeForAjax(data));
    return request;
}

function escapeHtml(text) {
    return text
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function htmlToElement(html) {
    let element = document.createElement('div');    
    element.innerHTML = html;
    return element.firstChild;
}

function formValues(form) {
    let values = {};

    let inputs = form.querySelectorAll('input, textarea');
    inputs.forEach((input) => {
        switch (input.getAttribute('type')) {
            case 'submit': return;
            case 'checkbox': values[input.getAttribute('name')] = (input.checked ? 1 : 0); return;
            case 'radio': 
                if (input.checked)
                    values[input.getAttribute('name')] = input.value;
                return;
            case 'file':
                if (input.files.length === 0)
                    return;
                if (input.getAttribute('multiple'))
                    values[input.getAttribute('name')] = input.files;
                values[input.getAttribute('name')] = input.files[0];
                return;
            default: values[input.getAttribute('name')] = input.value; return; 
        }
    });
    let selects = form.querySelectorAll('select');
    selects.forEach((select) => {
        values[select.getAttribute('name')] = select.options[select.selectedIndex].value;
    });
    
    return values;
}

function getFormInput(form, name) {
    return form.querySelector(`[name="${name}"]`);
}

function submitRequest(path, params, method) {
    const form = document.createElement('form');
    form.method = "post";
    form.action = path;

    const methodField = document.createElement('input');
    methodField.type = 'hidden';
    methodField.name="_method";
    methodField.value= method;
    form.appendChild(methodField);

    const csrfField = document.createElement('input');
    csrfField.type = 'hidden';
    csrfField.name= "_token";
    csrfField.value= document.querySelector('meta[name="csrf-token"]').content;
    form.appendChild(csrfField);

    for (const key in params) {
        if (params.hasOwnProperty(key)) {
            const hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = key;
            hiddenField.value = params[key];

            form.appendChild(hiddenField);
        }
    }

    console.log(form);
    document.body.appendChild(form);
    form.submit();
}