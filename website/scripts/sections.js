function headerAuthorize() {
    const formLabels = Array.from(document.querySelectorAll('.header-form__label'));
    const formInputs = formLabels.map(((value) => value.children[0]));

    const formData = new FormData();
    formData.append('email', formInputs[0].value);
    formData.append('password', formInputs[1].value);

    const ajaxRequest = new XMLHttpRequest();
    ajaxRequest.open('POST', '/util/authorize.php');
    ajaxRequest.onload = function () {
        const parsedJsonResponse = JSON.parse(this.response);
        console.log(parsedJsonResponse['message']);
        if (parsedJsonResponse['action'] === 'reload') {
            location.reload();
        }
    };
    ajaxRequest.send(formData);

    return false;
}