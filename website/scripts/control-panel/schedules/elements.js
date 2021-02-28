function createLessonScheduleItemElement(schedule, groupName, groupId) {
    const element = document.createElement('div');
    const groupElement = createLessonScheduleItemTitleElement(groupName, groupId);
    const headerElement = createLessonScheduleItemHeaderElement();
    const lessonsElement = createLessonScheduleItemLessonsElement(schedule, groupId);

    element.className = 'lesson-schedule__item';

    element.appendChild(groupElement);
    element.appendChild(headerElement);
    element.appendChild(lessonsElement);

    return element;
}

function createLessonScheduleItemTitleElement(group, groupId) {
    const element = document.createElement('div');
    const groupElement = document.createElement('div');
    const editButtonElement = createLessonScheduleItemEditButtonElement();

    element.className = 'lesson-schedule-item__title';

    groupElement.className = 'lesson-schedule-item__group';
    groupElement.innerHTML = group;

    editButtonElement.addEventListener('click',
        () => requestLessonScheduleUpdate(groupId, getScheduleObject(groupId)));

    element.appendChild(groupElement);
    element.appendChild(editButtonElement);

    return element;
}

function createLessonScheduleItemEditButtonElement() {
    const element = document.createElement('i');

    element.className = 'lesson-schedule-item__edit-button fa fa-edit fa-lg';

    return element;
}

function createLessonScheduleItemHeaderElement() {
    const element = document.createElement('div');
    const dayElement = document.createElement('div');
    const lessonNumberElement = document.createElement('div');
    const disciplineElement = document.createElement('div');
    const lessonVariantElement = document.createElement('div');

    element.className = 'lesson-schedule-item__header';

    dayElement.innerHTML = 'День';
    lessonNumberElement.innerHTML = 'Пара';
    disciplineElement.innerHTML = 'Дисципліна';
    lessonVariantElement.innerHTML = 'Варіант';

    element.append(dayElement);
    element.append(lessonNumberElement);
    element.append(disciplineElement);
    element.append(lessonVariantElement);

    return element;
}

function createLessonScheduleItemLessonsElement(schedule, groupId) {
    const element = document.createElement('div');

    element.className = 'lesson-schedule-item__lessons';

    for (const day in schedule) {
        // noinspection JSUnfilteredForInLoop
        const dayItemElement = createDayItemElement(day, schedule[day], groupId);

        element.appendChild(dayItemElement);
    }

    return element;
}

function createDayItemElement(day, daySchedule, groupId) {
    const element = document.createElement('div');
    const dayElement = document.createElement('div');
    const dayScheduleElement = createDayScheduleElement(day, daySchedule, groupId);

    element.className = 'day-item';
    dayElement.className = 'day-item__day';

    dayElement.innerHTML = day;
    element.appendChild(dayElement);
    element.appendChild(dayScheduleElement);

    return element;
}

function createDayScheduleElement(day, daySchedule, groupId) {
    const element = document.createElement('div');

    element.className = 'day-item__schedule';

    for (const lessonNumber in daySchedule) {
        // noinspection JSUnfilteredForInLoop
        const lessonItem = createLessonItem(day, lessonNumber, daySchedule[lessonNumber], groupId);

        element.appendChild(lessonItem);
    }

    return element;
}

function createLessonItem(day, lessonNumber, lessonDisciplines, groupId) {
    const element = document.createElement('div');
    const lessonNumberElement = document.createElement('div');
    const lessonDisciplinesElement = createLessonItemDisciplinesElement(day, lessonNumber, lessonDisciplines, groupId);

    element.className = 'lesson-item';
    lessonNumberElement.className = 'lesson-item__number';

    lessonNumberElement.innerHTML = lessonNumber;

    element.appendChild(lessonNumberElement);
    element.appendChild(lessonDisciplinesElement);

    return element;
}

function createLessonItemDisciplinesElement(day, lessonNumber, lessonDisciplines, groupId) {
    const element = document.createElement('div');
    const dropdownsElement = document.createElement('div');
    const variantElement = createLessonItemVariantElement(lessonDisciplines);

    element.className = 'lesson-item__discipline discipline';
    dropdownsElement.className = 'discipline__dropdowns';

    let firstDiscipline, secondDiscipline = null;

    if (lessonDisciplines.length === 0) {
        firstDiscipline = secondDiscipline = null;
    } else if (lessonDisciplines.hasOwnProperty('3')) {
        firstDiscipline = lessonDisciplines['3']['discipline'];
    } else {
        firstDiscipline = lessonDisciplines['1']?.['discipline'];
        secondDiscipline = lessonDisciplines['2']?.['discipline'];
    }

    const mainDisciplineDropdown = createDisciplineDropdown(firstDiscipline, groupId, day, lessonNumber);
    const secondaryDisciplineDropdown = createDisciplineDropdown(secondDiscipline, groupId, day, lessonNumber, true);

    if (lessonDisciplines.hasOwnProperty('3') || firstDiscipline == null) {
        mainDisciplineDropdown.setAttribute('data-variant', '3');
    }

    [mainDisciplineDropdown, secondaryDisciplineDropdown].forEach(dropdown => {
        dropdown?.addEventListener('input', () => {

        });
    });

    variantElement.addEventListener('input', () => {
        const onlyOneVariant = variantElement.value === '3';
        if (onlyOneVariant) {
            mainDisciplineDropdown.setAttribute('data-variant', '3');
            secondaryDisciplineDropdown.removeAttribute('data-variant');
            secondaryDisciplineDropdown.classList.toggle('hidden', true);
        } else {
            mainDisciplineDropdown.setAttribute('data-variant', '1');
            secondaryDisciplineDropdown.setAttribute('data-variant', '2');
            secondaryDisciplineDropdown.classList.toggle('hidden', false);
        }
    });

    dropdownsElement.appendChild(mainDisciplineDropdown);
    dropdownsElement.appendChild(secondaryDisciplineDropdown);

    element.appendChild(dropdownsElement);
    element.appendChild(variantElement);

    return element;
}

function createDisciplineDropdown(discipline, groupId, day, lessonNumber, isSecondary = false) {
    const element = document.createElement('select');
    const emptyOption = document.createElement('option');

    element.className = 'discipline__dropdown';
    if (discipline === null && isSecondary) {
        element.classList.toggle('hidden', true);
    }

    element.setAttribute('data-group-id', groupId);
    element.setAttribute('data-day', day);
    element.setAttribute('data-lesson-number', lessonNumber);
    if (isSecondary) {
        if (discipline != null) {
            element.setAttribute('data-variant', '2');
        }
    } else {
        element.setAttribute('data-variant', '1');
    }

    emptyOption.value = '-1';
    emptyOption.innerHTML = 'Вікно';

    element.appendChild(emptyOption);

    if (discipline != null) {
        const disciplineOption = document.createElement('option');

        disciplineOption.value = discipline['id'];
        disciplineOption.innerHTML = discipline['subject'] + ' — ' + discipline['teacher'];
        disciplineOption.selected = true;

        element.appendChild(disciplineOption);
    }

    return element;
}

function createLessonItemVariantElement(lessonDisciplines) {
    const element = document.createElement('select');

    element.className = 'discipline__variant';

    const variableOption = document.createElement('option');
    const constantOption = document.createElement('option');

    variableOption.value = '1-2';
    constantOption.value = '3';

    variableOption.innerHTML = 'По варіанту';
    constantOption.innerHTML = 'Постійно';

    element.appendChild(variableOption);
    element.appendChild(constantOption);

    if (lessonDisciplines.hasOwnProperty('1') || lessonDisciplines.hasOwnProperty('2')) {
        variableOption.selected = true;
    } else {
        constantOption.selected = true;
    }

    return element;
}