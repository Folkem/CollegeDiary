function saveChanges() {
    const oldPassword = document.querySelector("#currentPassword").value;
    const newPassword = document.querySelector("#newPassword").value;
    const repeatedNewPassword = document.querySelector("#repeatedNewPassword").value;

    if (newPassword !== repeatedNewPassword) {
        alert('Паролі не співпадають!')
        return false;
    }

    const formData = new FormData();
    formData.append('action', 'change password');
    formData.append('oldPassword', oldPassword);
    formData.append('newPassword', newPassword);

    const ajaxRequest = new XMLHttpRequest();
    ajaxRequest.open('POST', '/util/actions/profile_update.php');
    ajaxRequest.onload = () => {
        try {
            const parsedJsonResponse = JSON.parse(ajaxRequest.response);
            alert(parsedJsonResponse['message']);
            if (parsedJsonResponse['action'] === 'reload') {
                location.reload();
            }
        } catch (e) {
            alert('error, look in console');
            console.log(ajaxRequest.response);
        }
    };
    ajaxRequest.send(formData);

    return false;
}