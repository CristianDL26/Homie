var displayedRequests = [];
var locationInterval;
var pollingIntervalPro;
var timeUpdateInterval;


function pollRequests() {
    fetch('utilities.php?action=getRequests')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.requests) {
                updateUIWithRequests(data.requests);
            } else {
                console.log('No new requests or error');
            }
        })
        .catch(error => console.error('Error fetching requests:', error));
}

function checkForAcceptedRequests() {
    fetch('utilities.php?action=getRequests') 
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const acceptedRequest = data.requests.find(request => request.status === 'accepted');
                if (acceptedRequest) {
                    handleAcceptedRequestPro(acceptedRequest.requestId);
                }
            }
        })
        .catch(error => console.error('Error fetching requests:', error));
}

function acceptRequest(requestId) {
    fetch('utilities.php?action=acceptRequest', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ requestId: requestId })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                handleAcceptedRequestPro(requestId);
                pollRequests();
            } else {
                alert('Errore nell\'accettare la richiesta');
            }
        })
        .catch(error => {
            console.error('Errore:', error);
        });
}

function updateUIWithRequests(requests) {
    const requestsContainer = document.querySelector('.map ul');
    const currentRequestIds = requests.map(request => request.requestId);

    const noRequestsMessage = document.querySelector('.no-requests');
    if (noRequestsMessage) {
        noRequestsMessage.remove();
    }

    displayedRequests.forEach((id) => {
        if (!currentRequestIds.includes(id) || requests.find(req => req.requestId === id && req.status === 'completed')) {
            const elementToRemove = document.getElementById(`request-${id}`);
            if (elementToRemove) {
                elementToRemove.remove();
            }
        }
    });
    displayedRequests = displayedRequests.filter(id => {
        return currentRequestIds.includes(id) && !requests.find(req => req.requestId === id && req.status === 'completed');
    });

    requests.forEach(request => {
        if (request.status !== 'completed' && !displayedRequests.includes(request.requestId)) {
            const requestElement = document.createElement('li');
            requestElement.id = `request-${request.requestId}`;
            requestElement.style = 'width: 100%;';
            requestElement.innerHTML = `
                <div class="request-container">
                    <div class="request">
                        <div class="img-det-container">
                            <div class="request-image">
                                <img draggable="false" src="img/default-avatar.png" alt="default-avatar">
                            </div>
                            <div class="request-details">
                                <h2>${request.userName}</h2>
                                <p>${request.userAddress}</p>
                                <p style="font-style: italic;">${timeSince(request.timestamp)}</p>
                            </div>
                        </div>
                        <div class="request-buttons">
                            ${request.status === 'pending' ? `<button class="default-button accept-request" onclick="acceptRequest('${request.requestId}')">Accetta</button>` : ''}
                            ${request.status === 'pending' ? `<button class="default-button reject-request" onclick="rejectRequest('${request.requestId}')">Rifiuta</button>` : ''}
                        </div>
                    </div>
                </div>`;
            requestsContainer.appendChild(requestElement);
            displayedRequests.push(request.requestId);
        }
    });

    if (requestsContainer.children.length === 0) {
        const noRequestsMessage = document.createElement('li');
        noRequestsMessage.textContent = 'Nessuna richiesta in attesa.';
        noRequestsMessage.className = 'no-requests';
        requestsContainer.appendChild(noRequestsMessage);
    }
}


function handleAcceptedRequestPro(requestId) {
    document.querySelector('.map.professionals-container').classList.add('hidden');
    const ongoingRequestContainer = document.querySelector('.ongoing-request-container');
    ongoingRequestContainer.classList.remove('hidden');
    clearInterval(pollingIntervalPro);

    fetchRequestDetails(requestId)
        .then(requestDetails => {

            sendCurrentLocation(requestDetails);  // VERIFICARAE //
            locationInterval = setInterval(() => sendCurrentLocation(requestDetails), 5000);
            updateOngoingRequestUI(requestDetails);

            clearInterval(timeUpdateInterval); 
            timeUpdateInterval = setInterval(() => updateTimeContainer(requestDetails.timestamp), 1000); 
        })
        .catch(error => {
            console.error('Error fetching request details:', error);
        });
}


function fetchRequestDetails(requestId) {
    return fetch(`utilities.php?action=getRequestDetails&requestId=${requestId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                return data.requestDetails;
            } else {
                throw new Error('Impossibile ottenere i dettagli della richiesta');
            }
        });
}

function updateOngoingRequestUI(details) {
    const container = document.querySelector('.ongoing-request-container');
    container.innerHTML = `
        <div class="accepted-request-details">
            <div class="external-title-details-container">
                <div class="external-title-container">
                    <div class="title-container-1">
                        <div class="title-container">
                            <h2>Richiesta in Corso</h2>
                        </div>
                        <p> ${details.userName} ti sta aspettando!</p>
                    </div>
                    <div class="container-3">
                        <div class="is-active">
                            <div class="pulsing-circle"></div>
                            <p>${details.userName.split(' ')[0]} può vedere dove ti trovi!</p>
                        </div>
                    </div>
                    <div class="time-container">
                        <h2>${timeSince(details.timestamp).split(' ')[0]}</h2>
                        <p>${timeSince(details.timestamp).split(' ')[1]} fa</p>
                    </div>
                </div>
                <div class="address-request-container-1">
                    <h4>Recati a:</h4>
                    <div class="address-request-container">
                        <p>${details.userAddress}</p>
                    </div>
                </div>
                <div class="request-details-container-1">
                    <h4>Dettagli della Richiesta:</h4>
                    <div class="request-details-container">
                        <p>${details.details}</p>
                    </div>
                </div>
            </div>
            <button class="default-button" onclick="endRequest('${details.requestId}')">Completa la Richiesta</button>
        </div>
    `;
}

function rejectRequest(requestId) {
    fetch('utilities.php?action=rejectRequest', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ requestId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector(`#request-${requestId}`).remove();
        } else {
            alert('Errore durante il rifiuto della richiesta: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Errore:', error);
    });
}

function rejectAll() {
    fetch('utilities.php?action=rejectAllRequests', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Tutte le richieste rifiutate');
            } else {
                alert('Errore durante il rifiuto delle richieste: ' + data.error);
            }
        })
        .catch(error => console.error('Error:', error));
}


function endRequest(requestId) {
    fetch('utilities.php?action=endRequest', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ requestId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector('.ongoing-request-container').classList.add('hidden');
            document.querySelector('.map.professionals-container').classList.remove('hidden');
            clearInterval(locationInterval);
            pollRequests();
            pollingIntervalPro = setInterval(pollRequests, 5000);
        } else {
            alert('Errore durante la conclusione della richiesta: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Errore:', error);
    });
}

function sendCurrentLocation(details) {
    console.log('Sending location...');
    navigator.geolocation.getCurrentPosition(position => {
        const coords = {
            lat: position.coords.latitude,
            lng: position.coords.longitude,
            professionalId: details.professionalId
        };

        fetch('utilities.php?action=updateLocation', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(coords)
        })
        .then(response => response.json())
        .then(data => {
            console.log('Location updated:', data);
        })
        .catch(error => console.error('Error updating location:', error));
    });
}


function updateTimeContainer(timestamp) {
    const timeContainer = document.querySelector('.time-container');
    if (timeContainer) {
        const timeSinceText = timeSince(timestamp).split(' ');
        timeContainer.querySelector('h2').textContent = timeSinceText[0];
        timeContainer.querySelector('p').textContent = `${timeSinceText[1]} fa`;
    }
}

function formatDate(date) {
    return new Date(date).toLocaleDateString('it-IT', {
        day: 'numeric', month: 'long', year: 'numeric'
    });
}

function timeSince(date) {
    var seconds = Math.floor((new Date() - new Date(date)) / 1000);
    var interval = seconds / 31536000;

    if (interval > 1) {
        return Math.floor(interval) + " anno/i fa";
    }
    interval = seconds / 2592000;
    if (interval > 1) {
        return Math.floor(interval) + " mese/i fa";
    }
    interval = seconds / 86400;
    if (interval > 1) {
        return Math.floor(interval) + " giorno/i fa";
    }
    interval = seconds / 3600;
    if (interval > 1) {
        return Math.floor(interval) + " ora/e fa";
    }
    interval = seconds / 60;
    if (interval > 1) {
        return Math.floor(interval) + " minuto/i fa";
    }
    return Math.floor(seconds) + " secondi fa";
}

document.addEventListener('DOMContentLoaded', function() {
    checkForAcceptedRequests();
    pollRequests();
    pollingIntervalPro = setInterval(pollRequests, 5000);
    
});
