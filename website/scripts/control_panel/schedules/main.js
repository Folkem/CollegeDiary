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

window.addEventListener('load', () => {
    uploadCallSchedule();
    uploadGroups();
    setUpMenu();
    setUpCallSchedules();
});