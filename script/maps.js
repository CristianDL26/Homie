const inputField = document.querySelector('#bigSearchInput');

const mapContainers = document.querySelectorAll('.single-mini-map-container');

mapContainers.forEach(container => {
    container.addEventListener('click', () => {
        const paragraphText = container.querySelector('p').title;

        inputField.value = paragraphText;
        const miniMap2 = document.getElementsByClassName("single-mini-map-container");

        for (let i = 0; i < miniMap2.length; i++) {
            miniMap2[i].classList.remove("single-mini-map-container-active");
        }

        container.classList.add('single-mini-map-container-active');
        const resultsContainer = document.getElementById('resultsContainer');
        resultsContainer.innerHTML = '';
    });
});

