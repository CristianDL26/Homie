async function fetchData(input) {
    try {
        const response = await fetch(`http://64.226.72.222:8080/https://maps.googleapis.com/maps/api/place/autocomplete/json?input=${input}&language=it&components=country:it&key=AIzaSyD8NKq8Y7ZWcdnprjHHsH153OrNT3HyVmk`,
        {
            headers: {
            'Access-Control-Allow-Origin': '*'
            }
        });
        var data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching data:', error);
        return null;
    }
}

async function handleInputMain() {
    const inputField = document.getElementById('bigSearchInput'); 
    console.log(inputField.value);
    const userInput = inputField.value;

    const miniMap2 = document.getElementsByClassName("single-mini-map-container");

    for (let i = 0; i < miniMap2.length; i++) {
        miniMap2[i].classList.remove("single-mini-map-container-active");
    }


    const resultsContainer = document.getElementById('resultsContainer');

    if (userInput.length < 3){
        resultsContainer.innerHTML = ''; s
        resultsContainer.classList.remove("results-container-active")
        return;
    }

    const results = await fetchData(userInput);

    if (results) {
        resultElement = document.createElement('ul');
        resultsContainer.innerHTML = '';
        resultsContainer.classList.add("results-container-active")
        
        if (results['predictions'].length == 0){
            const resultElement = document.createElement('li');
            resultElement.textContent = "Nessun risultato trovato";
            resultElement.className = "suggest-item light_text";
            resultElement.id = "no-results";
            resultsContainer.appendChild(resultElement);
        }

        for(let i in results['predictions']){
            const resultElement = document.createElement('li');
            resultElement.textContent = results['predictions'][i]['description'];
            resultElement.className = "suggest-item light_text";
            resultElement.addEventListener('click', function() {
                inputField.value = results['predictions'][i]['description'];
                resultsContainer.innerHTML = '';
                resultsContainer.classList.remove("results-container-active")

                const miniMap = document.getElementsByClassName("single-mini-map-container");

                var selectedCity = results['predictions'][i]['description'].split(",").reverse()[2].trim();
                console.log(selectedCity);
                if (["Roma", "Milano", "Napoli", "Torino"].includes(selectedCity)) {
                    for (let i = 0; i < miniMap.length; i++) {
                        console.log(miniMap[i].outerText);
                        if (miniMap[i].outerText.includes(selectedCity)) {
                            miniMap[i].classList.add("single-mini-map-container-active");
                        }
                    }
                }
                
            });
            resultsContainer.appendChild(resultElement);

        };
    }
}


async function handleInputB() {
    const inputField = document.getElementById('address'); 
    console.log(inputField.value);
    const userInput = inputField.value;


    const resultsContainer = document.getElementById('address-entries-container');

    if (userInput.length < 3){
        resultsContainer.innerHTML = '';
        resultsContainer.classList.remove("address-entries-container")
        return;
    }

    const results = await fetchData(userInput);

    if (results) {
        resultElement = document.createElement('ul');
        resultsContainer.innerHTML = ''; 
        resultsContainer.classList.add("active")
        
        if (results['predictions'].length == 0){
            const resultElement = document.createElement('li');
            resultElement.textContent = "Nessun risultato trovato";
            resultElement.className = "entry";
            resultElement.id = "no-results";
            resultsContainer.appendChild(resultElement);
        }

        var cnt = 0;
        for(let i in results['predictions']){
            const resultElement = document.createElement('li');
            resultElement.textContent = results['predictions'][i]['description'];
            resultElement.className = "entry address-submit";
            resultElement.addEventListener('click', function(event) {
                event.preventDefault();
                handleAddressChange(resultElement);
            });
            resultElement.addEventListener('click', function() {
                inputField.value = results['predictions'][i]['description'];
                resultsContainer.innerHTML = '';
                resultsContainer.classList.remove("results-container-active")
            });
            if (cnt < 4){resultsContainer.appendChild(resultElement);}
            cnt++;
        };
    }
}


