function addEventListeners() {
    $('#post-filter').change(displayDates);
}

let resultsDiv = document.getElementById("resultsDiv");
let dateFrom = document.getElementById("startDate");
let dateTo = document.getElementById("endDate");

function displayDates() {
    if (this.checked) {
        dateFrom.disabled = "";
        dateTo.disabled = "";
    } else {
        dateFrom.disabled = "disabled";
        dateTo.disabled = "disabled";
    }
}

function displayResults(json) {
    for(let i = 0; i < json.length; i++) {
        if(json[i].content_id !== undefined) { //if it is a post
            let postResultElem = createPostResult(json[i]);
            resultsDiv.appendChild(postResultElem);

            postResultElem.querySelector('.react-btn-upvote').addEventListener("click", toggleUpvote);
	        postResultElem.querySelector('.react-btn-downvote').addEventListener("click", toggleDownvote);
        }
        else { //if it is a user
            let userResultElem = createUserResult(json[i]);
            resultsDiv.appendChild(userResultElem);
        }
    }
}

function atBottom() {
    unsetAtBottomHandler();
    let fields = formValues(document.querySelector('#search-form'));

    let resultCount = countResults();
    fields.userOffset = resultCount.numUsers;
    fields.postOffset = resultCount.numPosts;
    fields.limit = 10;

    console.log("Asking for posts/users!");
    sendAjaxRequest('get', '/api/search', fields, searchHandler);
}

function countResults() {
    let resultCount = {};
    resultCount.numPosts = 0;
    resultCount.numUsers = 0;

    $('.search-result').each(function(i, val) {
        if(val.matches(".post")) resultCount.numPosts++;
        else resultCount.numUsers++;
    });

    return resultCount;
}

function searchHandler() {
    console.log(this.responseText);
    let response = JSON.parse(this.responseText);
    if (this.status !== 200)
        return;
    console.log("Got posts/users!");
    console.log(response);
    if (response.length === 0) {
        $('.spinner').remove()
        return;
    }

    displayResults(response);
    setAtBottomHandler(atBottom);
}


setAtBottomHandler(atBottom);
addEventListeners();

////////////////////////
/*   Html templates   */
////////////////////////

function createPostResult(post) {
    console.log(post);
    return htmlToElement(
        `<article class="card shadow-sm mx-md-3 my-3 search-result post" data-content-id="${post.content_id}">
            <header class="p-2 card-title bg-light d-flex align-items-center m-0">
                ${avatarStr(post.author)}
                <small class="ml-auto mr-2 text-muted">${post.content_date_short}</small>
            </header>
            <a href="${post.url}" class="card-body">    
                <p class="card-text text-prewrap text-dark m-0"> ${post.content}</p>
            </a>
        
            <div class="d-flex post-stats border-top">
                <span class="react-btn react-btn-upvote ${post.is_liked ? 'react-btn-highlight' : ''}" data-content-id="${post.content_id}">
                    <i class="fas fa-thumbs-up"></i>
                    <span class="p-0">${post.likes}</span>
                </span>
                <span class="react-btn react-btn-downvote ${post.is_disliked ? 'react-btn-highlight' : ''}" data-content-id="${post.content_id}">
                    <i class="fas fa-thumbs-down"></i>
                    <span class="p-0">${post.dislikes}</span>
                </span>
                <a href="${post.url}" class="btn-comments ml-auto">
                    <i class="fas fa-comment"></i>
                    <span class="p-0">${post.comments}</span>
                </a>
            </div>
        </article>`
    );
}

function createUserResult(user) {
    console.log(user);
    return htmlToElement(
        `<div class="list-group-item search-result shadow-sm mx-md-3 my-3 p-2">
            ${avatarStr(user)}
        </div>`
    );
}