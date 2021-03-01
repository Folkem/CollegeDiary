// todo: remake work-distribution/elements.js

function addNewWorkDistributionRecordElement() {
    const recordItemsContainer = document.querySelector('.work-distribution-form__records-list');
    const newRecordItemElement = buildWorkDistributionRecordElement();
    if (recordItemsContainer.hasChildNodes()) {
        recordItemsContainer.insertBefore(newRecordItemElement, recordItemsContainer.firstChild);
    } else {
        recordItemsContainer.appendChild(newRecordItemElement);
    }

    const recordsForm = document.querySelector('.work-distribution-form');
    recordsForm.scroll({top: 0});
}

function buildWorkDistributionRecordElement() {
    const recordItemElement = document.createElement('div');

    const idElement = document.createElement('div');
    const subjectElement = document.createElement('div');
    const teacherElement = document.createElement('div');
    const groupElement = document.createElement('div');
    const buttonsElement = document.createElement('div');
    const addButtonElement = document.createElement('div');
    const updateButtonElement = document.createElement('div');
    const deleteButtonElement = document.createElement('div');

    recordItemElement.className = 'work-distribution-item work-distribution-item--not-added';
    idElement.className = 'work-distribution-item__component work-distribution-item__id';
    subjectElement.className = 'work-distribution-item__component work-distribution-item__subject';
    teacherElement.className = 'work-distribution-item__component work-distribution-item__teacher';
    groupElement.className = 'work-distribution-item__component work-distribution-item__group';
    buttonsElement.className = 'work-distribution-item__component work-distribution-item__buttons';
    addButtonElement.className = 'work-distribution-item__button work-distribution-item__button-add fa fa-plus fa-2x';
    updateButtonElement.className = 'work-distribution-item__button work-distribution-item__button-update fa fa-edit fa-2x hidden';
    deleteButtonElement.className = 'work-distribution-item__button work-distribution-item__button-delete fa fa-trash fa-2x';

    subjectElement.setAttribute('contenteditable', 'true');
    teacherElement.appendChild(buildWorkDistributionRecordTeachersElement());
    groupElement.appendChild(buildWorkDistributionRecordGroupElement());
    buttonsElement.appendChild(addButtonElement);
    buttonsElement.appendChild(updateButtonElement);
    buttonsElement.appendChild(deleteButtonElement);

    const recordItem = {
        'parent': recordItemElement,
        'id': idElement,
        'subject': subjectElement,
        'teacher': teacherElement.children.item(0),
        'group': groupElement.children.item(0),
        'addButton': addButtonElement,
        'updateButton': updateButtonElement,
        'deleteButton': deleteButtonElement
    };

    for (const recordItemKey in recordItem) {
        const recordItemValue = recordItem[recordItemKey];
        recordItemValue.addEventListener('input', () => {
            updateButtonElement.classList.toggle('work-distribution-item__button-update--allowed', true);
        });
    }

    this['records'].push(recordItem);

    addButtonElement.addEventListener('click', () => {
        requestWorkDistributionRecordAdd(recordItem);
    });
    updateButtonElement.addEventListener('click', () => {
        if (updateButtonElement.classList.contains('work-distribution-item__button-update--allowed')) {
            requestWorkDistributionRecordUpdate(recordItem);
        }
    });
    deleteButtonElement.addEventListener('click', () => {
        requestWorkDistributionRecordDelete(recordItem);
    });

    recordItemElement.appendChild(idElement);
    recordItemElement.appendChild(subjectElement);
    recordItemElement.appendChild(teacherElement);
    recordItemElement.appendChild(groupElement);
    buttonsElement.appendChild(updateButtonElement);
    buttonsElement.appendChild(deleteButtonElement);
    recordItemElement.appendChild(buttonsElement);

    return recordItemElement;
}

function buildWorkDistributionRecordTeachersElement() {
    const teachers = this['teachers'];
    const selectElement = document.createElement('select');
    for (const teacherIndex in teachers) {
        const newOptionElement = document.createElement('option');
        newOptionElement.value = teacherIndex;
        // noinspection JSUnfilteredForInLoop
        newOptionElement.innerHTML = teachers[teacherIndex];
        selectElement.appendChild(newOptionElement);
    }
    return selectElement;
}

function buildWorkDistributionRecordGroupElement() {
    const groups = this['groups'];
    const selectElement = document.createElement('select');
    const defaultOption = document.createElement('option');
    defaultOption.selected = true;
    selectElement.appendChild(defaultOption);
    for (const groupIndex in groups) {
        const newOptionElement = document.createElement('option');
        newOptionElement.value = groupIndex;
        // noinspection JSUnfilteredForInLoop
        newOptionElement.innerHTML = groups[groupIndex];
        selectElement.appendChild(newOptionElement);
    }
    return selectElement;
}