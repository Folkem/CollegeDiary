window.addEventListener('load', () => {
    const nameInput = document.querySelector('#news-name-input');
    const dateStartInput = document.querySelector('#news-date-start');
    const dateEndInput = document.querySelector('#news-date-end');
    const keywordsInput = document.querySelector('#news-keywords');

    const newsItemObjects = Array
        .from(document.querySelectorAll('.news-item'))
        .map((elem, i, a) => {
            const linkElement = elem.children.item(0);
            const contentElement = linkElement.children.item(1);

            const container = elem;
            const date = Date.parse(contentElement.children.item(0).innerHTML.trim());
            const header = contentElement.children.item(1).innerHTML.trim();
            const keywordsText = contentElement.children.item(2).innerHTML.trim();
            const keywords = keywordsText
                .replace("Ключові слова: ", '')
                .toLowerCase()
                .replaceAll('<b>', '')
                .replaceAll('</b>', '')
                .split(', ');

            console.log(keywords);

            return {
                'container': container,
                'date': date,
                'header': header,
                'keywords': keywords
            };
        });

    nameInput.addEventListener('input', () => {
        const name = nameInput.value.toLowerCase();
        for (const newsItemObject of newsItemObjects) {
            if (newsItemObject['header'].toLowerCase().includes(name)) {
                newsItemObject['container'].classList.toggle('hidden', false);
            } else {
                newsItemObject['container'].classList.toggle('hidden', true);
            }
        }
    });
    const dateInputListener = () => {
        let dateStart = Date.parse(dateStartInput.value);
        let dateEnd = Date.parse(dateEndInput.value);

        dateStart = (isNaN(dateStart) ? 0 : dateStart);
        dateEnd = (isNaN(dateEnd) ? Infinity : dateEnd);

        for (const newsItemObject of newsItemObjects) {
            if (newsItemObject['date'] >= dateStart &&
                newsItemObject['date'] <= dateEnd) {
                newsItemObject['container'].classList.toggle('hidden', false);
            } else {
                newsItemObject['container'].classList.toggle('hidden', true);
            }
        }
    };
    dateStartInput.addEventListener('input', dateInputListener);
    dateEndInput.addEventListener('input', dateInputListener);
    keywordsInput.addEventListener('input', () => {
        const keywordsText = keywordsInput.innerHTML
            .trim()
            .toLowerCase()
            .replace('&nbsp;', '');
        const keywords = (keywordsText === '' ? [] : keywordsText.split(' '));

        console.log(keywords);

        for (const newsItemObject of newsItemObjects) {
            let containsAtLeastOneKeyword = false;

            if (keywords.length === 0) {
                newsItemObject['container'].classList.toggle('hidden', false);
            } else {
                for (const keyword of keywords) {
                    console.log(newsItemObject['keywords']);
                    if (newsItemObject['keywords'].includes(keyword)) {
                        containsAtLeastOneKeyword = true;
                        break;
                    }
                }

                if (containsAtLeastOneKeyword) {
                    newsItemObject['container'].classList.toggle('hidden', false);
                } else {
                    newsItemObject['container'].classList.toggle('hidden', true);
                }
            }
        }
    });
});