window.addEventListener('load', () => {
    const commentList = document.querySelector("#comment-list");
    const commentForm = document.querySelector("#comment-form");
    const commentTextarea = document.querySelector("#comment-form-text");
    const newsItemId = document.querySelector("#news-item__id");

    commentForm?.addEventListener('submit', () => {
        const formData = new FormData();
        const ajaxRequest = new XMLHttpRequest();

        formData.append('news-item-id', newsItemId.innerHTML);
        formData.append('comment-text', commentTextarea.value);

        ajaxRequest.open('POST', '/php/actions/news_comments.php');
        ajaxRequest.onload = () => {
            const responseBlock = document.querySelector("#comment-form-response");
            let message, commentWasAdded, commentContent;

            try {
                const parsedJsonResponse = JSON.parse(ajaxRequest.response);
                message = parsedJsonResponse['message'];
                commentWasAdded = parsedJsonResponse['commentWasAdded'];
                commentContent = parsedJsonResponse['commentContent'];
            } catch (e) {
                message = "Вибачте, виникла помилка під час обробки запиту. " +
                    "спробуйте пізніше або зверніться до техпідтримки.";
                commentWasAdded = false;
                commentContent = "";
            }

            if (commentWasAdded) {
                const commentItemElement = buildCommentItemElement(commentContent);
                commentList.appendChild(commentItemElement);
                commentTextarea.value = "";
                responseBlock.innerHTML = message;
            } else {
                responseBlock.innerHTML = message;
            }
        };
        ajaxRequest.send(formData);
    });
});

function buildCommentItemElement(commentContent) {
    const commentItemElement = document.createElement('div');

    const id = commentContent['id'];
    const postDate = commentContent['postDate'];
    const userFullName = commentContent['userData']['fullName'];
    const commentText = commentContent['commentText'];

    const idElement = document.createElement('div');
    const postDateElement = document.createElement('div');
    const userFullNameElement = document.createElement('div');
    const commentTextElement = document.createElement('div');

    commentItemElement.className = 'comment-item';
    idElement.className = 'hidden comment-item__id';
    postDateElement.className = 'comment-item__date';
    userFullNameElement.className = 'comment-item__user-data';
    commentTextElement.className = 'comment-item__comment-content';

    idElement.innerHTML = id;
    postDateElement.innerHTML = postDate;
    userFullNameElement.innerHTML = 'by '.concat(userFullName);
    commentTextElement.innerHTML = commentText;

    commentItemElement.appendChild(idElement);
    commentItemElement.appendChild(postDateElement);
    commentItemElement.appendChild(userFullNameElement);
    commentItemElement.appendChild(commentTextElement);

    return commentItemElement;
}