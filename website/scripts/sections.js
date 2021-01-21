function headerAuthorize() {
    const formLabels = Array.from(document.querySelectorAll('.login-form__label'));
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

function onUserExit() {
    const ajaxRequest = new XMLHttpRequest();
    ajaxRequest.open('GET', '/util/actions/logout.php');
    ajaxRequest.onload = () => {
        location.reload();
    }
    ajaxRequest.send();
}

function toggleUserMenu() {
    const caretElement = document.querySelector("#profile-caret");
    const dropdownElement = document.querySelector("#profile-dropdown");
    dropdownElement.classList.toggle("hidden");
    console.log(caretElement);
    caretElement.classList.toggle("fa-caret-down");
    caretElement.classList.toggle("fa-caret-up");
}

function showLoginMenu() {
    const loginMenuElement = document.querySelector("#login-menu");
    loginMenuElement.classList.toggle("hidden", false);
}

function hideLoginMenu(event) {
    const loginMenuElement = document.querySelector("#login-menu");
    if (event.target !== loginMenuElement) return;

    loginMenuElement.classList.toggle("hidden", true);
}

window.addEventListener('load', () => {
    const showLoginMenuButton = document.querySelector("#show-login-menu-button");
    const loginMenuElement = document.querySelector("#login-menu");
    const toggleUserMenuButton = document.querySelector("#toggle-user-menu-button");
    const userExitButton = document.querySelector("#user-exit-button");
    const loginForm = document.querySelector("#login-form");

    loginForm?.addEventListener('submit', headerAuthorize);
    showLoginMenuButton?.addEventListener('click', showLoginMenu);
    loginMenuElement?.addEventListener('click', hideLoginMenu);
    toggleUserMenuButton?.addEventListener('click', toggleUserMenu);
    userExitButton?.addEventListener('click', onUserExit);
});