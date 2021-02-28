function setUpCallSchedules() {
    window['call-schedule-elements'] = [];

    const callScheduleItemElements = document.querySelectorAll('.call-schedule-item');
    callScheduleItemElements.forEach((element) => {
        const itemParts = Array.from(element.children);
        const idElement = itemParts.filter(
            itemPart => itemPart.className.includes('call-schedule-item__id')
        )[0];
        const timeStartElement = itemParts.filter(
            itemPart => itemPart.className.includes('call-schedule-item__time-start')
        )[0].children.item(0).children.item(0);
        const timeEndElement = itemParts.filter(
            itemPart => itemPart.className.includes('call-schedule-item__time-end')
        )[0].children.item(0).children.item(0);
        const saveButtonElement = itemParts.filter(
            itemPart => itemPart.className.includes('call-schedule-item__save-button')
        )[0];

        const callScheduleItem = {
            'id': idElement,
            'time-start': timeStartElement,
            'time-end': timeEndElement,
            'save-button': saveButtonElement
        };

        const editListener = () => saveButtonElement.classList.toggle('call-schedule-item__save-button--allowed', true);

        timeStartElement.addEventListener('input', editListener);
        timeEndElement.addEventListener('input', editListener);

        saveButtonElement.addEventListener('click', () => {
            if (validateCallSchedule()) {
                requestCallScheduleItemUpdate(callScheduleItem);
            }
        });

        window['call-schedule-elements'].push(callScheduleItem);
    });
}

function validateCallSchedule() {
    // todo: add call schedule validation

    return true;
}

function setUpLessonSchedules() {
    const lessonScheduleList = document.querySelector('.lesson-schedule__list');

    const groups = window['groups'];
    const lessonSchedulesByGroups = window['lesson-schedules'];

    for (const groupKey in groups) {
        // noinspection JSUnfilteredForInLoop
        const groupName = groups[groupKey];
        // noinspection JSUnfilteredForInLoop
        const groupSchedule = lessonSchedulesByGroups[groupKey];

        // noinspection JSUnfilteredForInLoop
        const lessonScheduleItem = createLessonScheduleItemElement(groupSchedule, groupName, groupKey);
        lessonScheduleList.appendChild(lessonScheduleItem);
    }
}

function setUpReadableDisciplines() {
    const disciplines = window['readable-disciplines'];

    const dropdownsArray = Array.from(document.querySelectorAll('.discipline__dropdown'));

    for (const dropdown of dropdownsArray) {
        const groupId = dropdown.getAttribute('data-group-id');
        const alreadyPresentDisciplineId = parseInt(dropdown.children.item(1)?.value);
        const groupDisciplines = Array.from(disciplines[groupId])
            .filter(discipline => discipline['id'] !== alreadyPresentDisciplineId);

        for (const discipline of groupDisciplines) {
            const disciplineElement = document.createElement('option');
            disciplineElement.value = discipline['id'];
            disciplineElement.innerHTML = discipline['subject-teacher'];

            dropdown.appendChild(disciplineElement);
        }
    }
}

function getScheduleObject(groupId) {
    const dropdowns = document.querySelectorAll(`.discipline__dropdown:not(.hidden)[data-group-id="${groupId}"][data-variant]`);
    const weekDaysReversed = {
        'Понеділок': '1',
        'Вівторок': '2',
        'Середа': '3',
        'Четверг': '4',
        'П\'ятниця': '5',
    };

    const groupedDropdowns = {
        '1': {
            '1': {'1': null, '2': null, '3': null},
            '2': {'1': null, '2': null, '3': null},
            '3': {'1': null, '2': null, '3': null},
            '4': {'1': null, '2': null, '3': null},
            '5': {'1': null, '2': null, '3': null},
            '6': {'1': null, '2': null, '3': null},
            '7': {'1': null, '2': null, '3': null},
        },
        '2': {
            '1': {'1': null, '2': null, '3': null},
            '2': {'1': null, '2': null, '3': null},
            '3': {'1': null, '2': null, '3': null},
            '4': {'1': null, '2': null, '3': null},
            '5': {'1': null, '2': null, '3': null},
            '6': {'1': null, '2': null, '3': null},
            '7': {'1': null, '2': null, '3': null},
        },
        '3': {
            '1': {'1': null, '2': null, '3': null},
            '2': {'1': null, '2': null, '3': null},
            '3': {'1': null, '2': null, '3': null},
            '4': {'1': null, '2': null, '3': null},
            '5': {'1': null, '2': null, '3': null},
            '6': {'1': null, '2': null, '3': null},
            '7': {'1': null, '2': null, '3': null},
        },
        '4': {
            '1': {'1': null, '2': null, '3': null},
            '2': {'1': null, '2': null, '3': null},
            '3': {'1': null, '2': null, '3': null},
            '4': {'1': null, '2': null, '3': null},
            '5': {'1': null, '2': null, '3': null},
            '6': {'1': null, '2': null, '3': null},
            '7': {'1': null, '2': null, '3': null},
        },
        '5': {
            '1': {'1': null, '2': null, '3': null},
            '2': {'1': null, '2': null, '3': null},
            '3': {'1': null, '2': null, '3': null},
            '4': {'1': null, '2': null, '3': null},
            '5': {'1': null, '2': null, '3': null},
            '6': {'1': null, '2': null, '3': null},
            '7': {'1': null, '2': null, '3': null},
        }
    };

    for (const dropdown of dropdowns) {
        if (dropdown.value !== '-1') {
            const day = weekDaysReversed[dropdown.getAttribute('data-day')];
            const lessonNumber = dropdown.getAttribute('data-lesson-number');
            const variant = dropdown.getAttribute('data-variant');
            groupedDropdowns[day][lessonNumber][variant] = dropdown.value;
        }
    }

    return groupedDropdowns;
}

function showLessonScheduleContent() {
    const loader = document.querySelector('#lesson-schedule-loader');
    const content = document.querySelector('#lesson-schedule');

    loader.classList.toggle('loader--hidden', true);
    content.classList.toggle('hidden', false);
}

window.addEventListener('load', () => {
    setUpMenu();
    uploadCallSchedule()
        .then(setUpCallSchedules);
    uploadGroups()
        .then(() => uploadLessonSchedules()
            .then(setUpLessonSchedules)
            .then(() => uploadReadableDisciplines()
                .then(setUpReadableDisciplines))
            .then(showLessonScheduleContent));
});