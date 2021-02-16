function requestCallScheduleItemUpdate(item) {
    const id = item['id'].innerHTML.trim();
    const timeStart = item['time-start'].value;
    const timeEnd = item['time-end'].value;
    const saveButtonElement = item['save-button'];

    const formData = new FormData();
    formData.append('id', id);
    formData.append('time-start', timeStart);
    formData.append('time-end', timeEnd);

    const ajaxRequest = new XMLHttpRequest();
    ajaxRequest.open('POST', '/util/actions/control_panel/update_call_schedule.php');
    ajaxRequest.onload = () => {
        try {
            const parsedResponse = JSON.parse(ajaxRequest.response);
            const status = parsedResponse['status'];
            const logMessage = parsedResponse['log-message'];

            if (status === 'success') {
                saveButtonElement.classList.toggle('call-schedule-item__save-button--allowed', false);
            } else {
                alert(`Status: ${status}. Message: ${logMessage}`);
            }
        } catch (e) {
            console.log(e);
            alert('Вибачте, виникла ще не оброблена помилка. Зверніться до адміністратора, будь ласка');
        }
    };
    ajaxRequest.send(formData);
}