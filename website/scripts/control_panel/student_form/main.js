function setUpStudentForm() {
    const fileInputButton = document.querySelector('#student-table-file-button');
    const fileInput = document.querySelector('#student-table-file');
    fileInputButton.addEventListener('click', () => {
        fileInput.click();
    });
    fileInput.addEventListener('change', () => {
        fileInputButton.classList.toggle('form__input-button--success', true);
    });

    const form = document.querySelector('#student-form');
    form.addEventListener('submit', () => onFileFormSubmit('student'));
}