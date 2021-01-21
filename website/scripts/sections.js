function headerAuthorize() {
    const formLabels = Array.from(document.querySelectorAll('.header-form__label'));
    const formInputs = formLabels.map(((value) => value.children[0]));

    const formData = new FormData();
    formData.append('email', formInputs[0].value);
    formData.append('password', formInputs[1].value);

    const ajaxRequest = new XMLHttpRequest();
    ajaxRequest.open('POST', '/util/actions/authorize.php');
    ajaxRequest.onload = () => {
        const parsedJsonResponse = JSON.parse(ajaxRequest.response);
        alert(parsedJsonResponse['message']);
        if (parsedJsonResponse['action'] === 'reload') {
            location.reload();
        }
    };
    ajaxRequest.send(formData);

    return false;
}

window.onload = () => {
    const userExitButton = document.querySelector('.user-exit-button');
    if (userExitButton !== null) {
        userExitButton.onclick = () => {
            const ajaxRequest = new XMLHttpRequest();
            ajaxRequest.open('GET', '/util/actions/logout.php');
            ajaxRequest.onload = () => {
                location.reload();
            }
            ajaxRequest.send();
        };
    }
}