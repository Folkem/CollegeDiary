function requestWorkDistributionRecordAdd(record) {
    const logMessageElement = document.querySelector('#work-distribution-form-log');

    const formData = new FormData();
    formData.append('subject', record['subject'].innerHTML.trim());
    formData.append('teacher', record['teacher'].value);
    formData.append('group', record['group'].value);

    const ajaxRequest = new XMLHttpRequest();
    ajaxRequest.open('POST', '/util/actions/control_panel/add_work_distribution.php', true);
    ajaxRequest.onload = () => {
        try {
            const responseObject = JSON.parse(ajaxRequest.response);
            const status = responseObject['status'];
            const logMessage = responseObject['log_message'];
            const newRecordId = responseObject['record-id'];

            if (status === 'success') {
                record['parent'].classList.toggle('work-distribution-item--not-added', false);
                record['addButton'].remove();
                delete record['addButton'];
                record['updateButton'].classList.toggle('hidden', false);
                record['updateButton'].classList.toggle('work-distribution-item__button-update--allowed', false);
                record['id'].innerHTML = newRecordId;
                sortWorkDistributionRecordsByColumn();
            } else {
                logMessageElement.innerHTML = logMessage;
            }
        } catch (e) {
            alert('Вибачте, виникла ще не оброблена помилка. Зверніться до адміністратора');
            console.log(e);
        }
    };
    ajaxRequest.send(formData);
}

function requestWorkDistributionRecordUpdate(record) {
    const logMessageElement = document.querySelector('#work-distribution-form-log');

    const formData = new FormData();
    formData.append('id', record['id'].innerHTML.trim());
    formData.append('subject', record['subject'].innerHTML.trim());
    formData.append('teacher', record['teacher'].value);
    formData.append('group', record['group'].value);

    const ajaxRequest = new XMLHttpRequest();
    ajaxRequest.open('POST', '/util/actions/control_panel/update_work_distribution.php', true);
    ajaxRequest.onload = () => {
        try {
            const responseObject = JSON.parse(ajaxRequest.response);
            const status = responseObject['status'];
            const logMessage = responseObject['log_message'];

            if (status === 'success') {
                record['updateButton'].classList.toggle('work-distribution-item__button-update--allowed', false);
            } else {
                logMessageElement.innerHTML = logMessage;
            }
        } catch (e) {
            alert('Вибачте, виникла ще не оброблена помилка. Зверніться до адміністратора');
            console.log(e);
        }
    };
    ajaxRequest.send(formData);
}

function requestWorkDistributionRecordDelete(record) {
    const logMessageElement = document.querySelector('#work-distribution-form-log');

    const id = record['id'].innerHTML.trim();

    if (record['parent'].classList.contains('work-distribution-item--not-added')) {
        record['parent'].remove();
        const records = this['records'];
        records.splice(records.indexOf(record));
    } else {
        const formData = new FormData();
        formData.append('id', id);

        const ajaxRequest = new XMLHttpRequest();
        ajaxRequest.open('POST', '/util/actions/control_panel/delete_work_distribution.php', true);
        ajaxRequest.onload = () => {
            try {
                const responseObject = JSON.parse(ajaxRequest.response);
                const status = responseObject['status'];
                const logMessage = responseObject['log_message'];

                if (status === 'success') {
                    const recordList = document.querySelector('.work-distribution-form__records-list');
                    const records = this['records'];
                    recordList.removeChild(record['parent']);
                    records.splice(records.indexOf(record), 1);
                } else if (status === 'failure') {
                    logMessageElement.innerHTML = logMessage;
                }
            } catch (e) {
                alert('Виникла необроблена досі помилка. Зверніться до адміністратора');
                console.log(e);
            }
        };
        ajaxRequest.send(formData);
    }
}