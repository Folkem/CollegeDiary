function setUpTeacherForm() {
    const fileInputButton = document.querySelector('#teacher-table-file-button');
    const fileInput = document.querySelector('#teacher-table-file');
    fileInputButton.addEventListener('click', () => {
        fileInput.click();
    });
    fileInput.addEventListener('change', () => {
        fileInputButton.classList.toggle('form__input-button--success', true);
    });

    const form = document.querySelector('#teacher-form');
    form.addEventListener('submit', () => onFileFormSubmit('teacher'));
}