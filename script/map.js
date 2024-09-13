var markers = [];
var favoritesList = [];
var professionals = [];
var map;
var openInfoWindow;
var currentlySelectedProfessional;
var executed = false;
var userMarker;
var pollingInterval;

const customStyle =
  [
    {
      "featureType": "all",
      "elementType": "all",
      "stylers": [
        {
          "saturation": "32"
        },
        {
          "lightness": "-3"
        },
        {
          "visibility": "on"
        },
        {
          "weight": "1.18"
        }
      ]
    },
    {
      "featureType": "administrative",
      "elementType": "labels",
      "stylers": [
        {
          "visibility": "off"
        }
      ]
    },
    {
      "featureType": "landscape",
      "elementType": "labels",
      "stylers": [
        {
          "visibility": "off"
        }
      ]
    },
    {
      "featureType": "landscape.man_made",
      "elementType": "all",
      "stylers": [
        {
          "saturation": "-70"
        },
        {
          "lightness": "14"
        }
      ]
    },
    {
      "featureType": "poi",
      "elementType": "labels",
      "stylers": [
        {
          "visibility": "off"
        }
      ]
    },
    {
      "featureType": "road",
      "elementType": "labels",
      "stylers": [
        {
          "visibility": "off"
        }
      ]
    },
    {
      "featureType": "transit",
      "elementType": "labels",
      "stylers": [
        {
          "visibility": "off"
        }
      ]
    },
    {
      "featureType": "water",
      "elementType": "all",
      "stylers": [
        {
          "saturation": "100"
        },
        {
          "lightness": "-14"
        }
      ]
    },
    {
      "featureType": "water",
      "elementType": "labels",
      "stylers": [
        {
          "visibility": "off"
        },
        {
          "lightness": "12"
        }
      ]
    }
  ];


function initMap(userLocation = { lat: 41.9028, lng: 12.4964 }) {
  map = new google.maps.Map(document.getElementById('map'), {
    zoom: 15,
    styles: customStyle,
    center: { lat: userLocation.lat, lng: userLocation.lng },
    streetViewControl: false,
    mapTypeControl: false
  });

  userMarker = new google.maps.Marker({
    position: { lat: userLocation.lat, lng: userLocation.lng },
    map: map,
    title: "La tua posizione",
    icon: {
      url: 'img/icons/house_pointer.png',
      scaledSize: new google.maps.Size(50, 50)
    }
  });

  openInfoWindow = null;
  currentlySelectedProfessional = null;



  professionals.forEach(professional => {
    var marker = new google.maps.Marker({
      position: { lat: professional.lat, lng: professional.lng },
      map: map,
      professional: professional,
      icon: { url: 'img/marker.png', scaledSize: new google.maps.Size(35, 35) }
    });

    var infoWindow = new google.maps.InfoWindow({
      content: `<h3>${professional.nome}</h3><p>${professional.professione}</p>`
    });

    marker.addListener('click', function () {
      if (openInfoWindow) openInfoWindow.close();
      if (currentlySelectedProfessional) currentlySelectedProfessional.classList.remove('professional-selected');
      var professionalElement = document.querySelector(`.worker-entry[data-name='${professional.nome}']`);
      professionalElement.classList.add('professional-selected');
      currentlySelectedProfessional = professionalElement;
      map.setCenter(marker.getPosition());
      infoWindow.open(map, marker);
      openInfoWindow = infoWindow;
      professionalElement.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    });
    document.querySelector(`.worker-entry[data-name='${professional.nome}']`).addEventListener('click', function () {
      google.maps.event.trigger(marker, 'click');
    });
    markers.push(marker);
  });
  document.dispatchEvent(new CustomEvent('MapInitialized'));
}

function populateProfessionalsList(professionalList) {
  console.log('propagating');
  const professionalsList = document.querySelector('.professionals-container ul');
  professionalsList.innerHTML = '';

  markers.forEach(marker => marker.setMap(null));

  professionalList.forEach(professional => {
    const listItem = document.createElement('li');

    if (professional.rating == 0) {
      professional.rating = '-';
    }



    listItem.innerHTML = generateProfessionalHTML(professional);
    professionalsList.appendChild(listItem);

    const marker = markers.find(m => m.professional.piva === professional.piva);
    if (marker) {
      marker.setMap(map);
    }

    listItem.querySelector('.worker-entry').addEventListener('click', () => {
      google.maps.event.trigger(marker, 'click');
    });

    listItem.querySelector('.heart-container .checkbox').addEventListener('change', (event) => {
      handleFavoriteToggle(event, professional.piva);
    });
  });

  if (professionalsList.children.length === 0) {
    const noProfessionalsMessage = document.createElement('li');
    noProfessionalsMessage.textContent = 'Nessun professionista attivo disponibile.';
    noProfessionalsMessage.className = 'no-professionals';
    professionalsList.appendChild(noProfessionalsMessage);
  } else {
    attachEventToRequestButtons();
  }

}


function generateProfessionalHTML(professional) {
  const isChecked = favoritesList.includes(professional.piva) ? 'checked' : '';

  return `<div class="worker-entry" data-lat="${professional.lat}" data-lng="${professional.lng}" data-id="${professional.piva}" data-name="${professional.nome}" data-category="${professional.professione}" data-rating="${professional.rating}" data-img="img/professionals/${professional.image}" data-callPrice="${professional.prezzo_chiamata}" data-hourPrice="${professional.prezzo_orario}">
  <div class="external-container">
      <div class="img-infos-container">
          <div class="internal-container">
              <div class="image-container">
                  <img draggable="false" src="img/professionals/${professional.image}" alt="${professional.image.split(".")[0]}" class="square-image">
              </div>
              <div class="favourite">
                  <div class="heart-container" title="Like">
                      <input type="checkbox" class="checkbox" id="heart" ${isChecked}>
                      <div class="svg-container">
                          <svg viewBox="0 0 24 24" class="svg-outline" xmlns="http://www.w3.org/2000/svg">
                              <path d="M17.5,1.917a6.4,6.4,0,0,0-5.5,3.3,6.4,6.4,0,0,0-5.5-3.3A6.8,6.8,0,0,0,0,8.967c0,4.547,4.786,9.513,8.8,12.88a4.974,4.974,0,0,0,6.4,0C19.214,18.48,24,13.514,24,8.967A6.8,6.8,0,0,0,17.5,1.917Zm-3.585,18.4a2.973,2.973,0,0,1-3.83,0C4.947,16.006,2,11.87,2,8.967a4.8,4.8,0,0,1,4.5-5.05A4.8,4.8,0,0,1,11,8.967a1,1,0,0,0,2,0,4.8,4.8,0,0,1,4.5-5.05A4.8,4.8,0,0,1,22,8.967C22,11.87,19.053,16.006,13.915,20.313Z"></path>
                          </svg>
                          <svg viewBox="0 0 24 24" class="svg-filled" xmlns="http://www.w3.org/2000/svg">
                              <path d="M17.5,1.917a6.4,6.4,0,0,0-5.5,3.3,6.4,6.4,0,0,0-5.5-3.3A6.8,6.8,0,0,0,0,8.967c0,4.547,4.786,9.513,8.8,12.88a4.974,4.974,0,0,0,6.4,0C19.214,18.48,24,13.514,24,8.967A6.8,6.8,0,0,0,17.5,1.917Z"></path>
                          </svg>
                          <svg class="svg-celebrate" width="100" height="100" xmlns="http://www.w3.org/2000/svg">
                              <polygon points="10,10 20,20"></polygon>
                              <polygon points="10,50 20,50"></polygon>
                              <polygon points="20,80 30,70"></polygon>
                              <polygon points="90,10 80,20"></polygon>
                              <polygon points="90,50 80,50"></polygon>
                              <polygon points="80,80 70,70"></polygon>
                          </svg>
                      </div>
                  </div>
              </div>
          </div>
          <div class="infos-container">
              <div class="worker-info">
                  <span class="worker-name">${professional.nome}</span>
                  <span class="worker-category">${professional.professione}</span>
                  <div class="worker-price">
                      <div class="call-price">
                          <svg fill="#457B9D" width="800px" height="800px" viewBox="0 0 36 36" version="1.1" preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                              <title>phone-handset-solid</title>
                              <path class="clr-i-solid clr-i-solid-path-1" d="M15.22,20.64a20.37,20.37,0,0,0,7.4,4.79l3.77-3a.67.67,0,0,1,.76,0l7,4.51a2,2,0,0,1,.33,3.18l-3.28,3.24a4,4,0,0,1-3.63,1.07,35.09,35.09,0,0,1-17.15-9A33.79,33.79,0,0,1,1.15,8.6a3.78,3.78,0,0,1,1.1-3.55l3.4-3.28a2,2,0,0,1,3.12.32L13.43,9a.63.63,0,0,1,0,.75l-3.07,3.69A19.75,19.75,0,0,0,15.22,20.64Z"></path>
                              <rect x="0" y="0" width="36" height="36" fill-opacity="0" />
                          </svg>
                          <span>${professional.prezzo_chiamata}</span>
                      </div>
                      <div class="hour-price">
                          <div class="separator-point">·</div>
                          <span>${professional.prezzo_orario}</span>
                      </div>
                      <div class="hour-price-open">
                          <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <circle cx="12" cy="12" r="9" stroke="#457B9D" stroke-width="2" />
                              <path d="M16.5 12H12.25C12.1119 12 12 11.8881 12 11.75V8.5" stroke="#457B9D" stroke-width="2" stroke-linecap="round" />
                          </svg>
                          <span>${professional.prezzo_orario}</span>
                      </div>
                  </div>
                  <div class="worker-position">
                      <svg width="25px" height="25px" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path fill-rule="evenodd" clip-rule="evenodd" d="M7.5 8.49478C8.604 8.49478 9.5 7.59934 9.5 6.49606C9.5 5.39278 8.604 4.49738 7.5 4.49738C6.396 4.49738 5.5 5.39278 5.5 6.49606C5.5 7.59934 6.396 8.49478 7.5 8.49478Z" stroke="#457B9D" stroke-linecap="square" />
                          <path fill-rule="evenodd" clip-rule="evenodd" d="M13.5 6.49606C13.5 11.4928 8.5 14.4909 7.5 14.4909C6.5 14.4909 1.5 11.4928 1.5 6.49606C1.5 3.18522 4.187 0.5 7.5 0.5C10.813 0.5 13.5 3.18522 13.5 6.49606Z" stroke="#457B9D" stroke-linecap="square" />
                      </svg>
                      <span>${professional.position}</span>
                  </div>
              </div>
              <div class="worker-rating">
                  <span class="rating">${professional.rating}</span>
                  <svg fill="#E63946" width="22px" height="22px" id="star" data-name="Flat Color" xmlns="http://www.w3.org/2000/svg" class="icon flat-color">
                      <path id="primary" d="M22,9.81a1,1,0,0,0-.83-.69l-5.7-.78L12.88,3.53a1,1,0,0,0-1.76,0L8.57,8.34l-5.7.78a1,1,0,0,0-.82.69,1,1,0,0,0,.28,1l4.09,3.73-1,5.24A1,1,0,0,0,6.88,20.9L12,18.38l5.12,2.52a1,1,0,0,0,.44.1,1,1,0,0,0,1-1.18l-1-5.24,4.09-3.73A1,1,0,0,0,22,9.81Z" style="fill: #E63946"></path>
                  </svg>
              </div>
          </div>
      </div>
      <div class="worker-menu">
          <button class="default-button call-worker-button">Richiedi</button>
      </div>
  </div>
</div>
`;

}

function handleFavoriteToggle(event, piva) {
  const isChecked = event.target.checked;
  if (isChecked) {
    if (!favoritesList.includes(piva)) {
      favoritesList.push(piva);
    }
  } else {
    const index = favoritesList.indexOf(piva);
    if (index !== -1) {
      favoritesList.splice(index, 1);
    }
  }

  fetch('utilities.php?action=addFavorite', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ professionalId: piva, isFavorite: isChecked })
  })
    .then(response => response.json())
    .then(data => {
      if (!data.success) {
        console.error('Errore durante l\'aggiornamento dei preferiti:', data.error);
      }
    })
    .catch(error => {
      console.error('Errore durante l\'aggiornamento dei preferiti:', error);
    });
}


function toggleFavorites() {
  if (!executed) {
    console.log('Favorites toggle clicked');
    this.classList.toggle('active');
    let filteredProfessionals = professionals;

    const activeCategoryButton = document.querySelector('.category-button.active');
    if (activeCategoryButton) {
      const category = activeCategoryButton.getAttribute('data-category').toLowerCase();
      filteredProfessionals = filteredProfessionals.filter(p => p.professione.toLowerCase() === category);
    }

    if (this.classList.contains('active')) {
      filteredProfessionals = filteredProfessionals.filter(professional => favoritesList.includes(professional.piva));
    }

    console.log('Filtered professionals:', filteredProfessionals);
    populateProfessionalsList(filteredProfessionals);
    executed = true;
  } else {
    executed = false;
  }
}

function handleLocationError(hasGeolocation) {
  var errorText = hasGeolocation ? 'The Geolocation service failed.' : 'Your browser doesn\'t support geolocation.';
  console.error(errorText);
}



const favoritesToggleButton = document.querySelector('.favorites-toggle-button');
favoritesToggleButton.addEventListener('click', toggleFavorites);



document.addEventListener('DOMContentLoaded', function () {
  fetch('utilities.php?action=getFavorites')
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        favoritesList = data.favorites
      }
    })
    .catch(error => console.error('Error loading favorites:', error));

  fetch('utilities.php?action=getProfessionals')
    .then(response => response.json())
    .then(data => {


      data.forEach(professional => {
        if (professional.is_active == 1) {
          professionals.push(professional);
        }
      });

      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
          var userLocation = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
          };
          console.log('User location:', userLocation);

          populateProfessionalsList(professionals); // Popola la lista dei professionisti
          initMap(userLocation); // Inizializza la mappa con la posizione dell'utente
        }, function () {
          handleLocationError(true);
          populateProfessionalsList(professionals);
          initMap();
        });
      } else {
        handleLocationError(false);
        populateProfessionalsList(professionals);
        initMap();
      }
    })
    .catch(error => console.error('Error loading professionals:', error));

  fetch('utilities.php?action=getUserRequests')
    .then(response => response.json())
    .then(data => {
      if (data.success && data.requests.length > 0) {
        data.requests.forEach(request => {
          console.log('Found active request:', request);

          if (request.status === 'pending') {
            const map = document.getElementById('map');
            if (!map.classList.contains('hidden')) { map.classList.add('hidden'); }

            handleSentRequest(request.professionalName, request.professionalId, request.requestId, 'past');

            startPolling(request.requestId);
          } else if (request.status === 'accepted') {

            document.addEventListener('MapInitialized', function () {
              document.querySelectorAll('.call-worker-button').forEach(button => {
                button.disabled = true;
                button.classList.add('request-sent');
                if (button.closest('.worker-entry').dataset.id === request.professionalId) {
                  button.textContent = 'Richiesta inviata';
                }
              });
            });

            startPolling(request.requestId);
          }
        });
      }
    });
});



document.querySelectorAll('.category-button').forEach(button => {
  button.addEventListener('click', function () {
    const category = this.getAttribute('data-category').toLowerCase();
    let filteredProfessionals;

    if (this.classList.contains('active')) {
      this.classList.remove('active');
      filteredProfessionals = professionals;
    } else {
      document.querySelectorAll('.category-button').forEach(b => b.classList.remove('active'));
      this.classList.add('active');
      filteredProfessionals = professionals.filter(p => p.professione.toLowerCase() === category);
    }

    if (document.querySelector('.favorites-toggle-button').classList.contains('active')) {
      filteredProfessionals = filteredProfessionals.filter(p => favoritesList.includes(p.id));
    }

    populateProfessionalsList(filteredProfessionals);
  });
});

/* document.addEventListener("DOMContentLoaded", function () {
  // Gestione dell'inizializzazione della mappa
  document.addEventListener('MapInitialized', function () {
    attachEventToRequestButtons();
  });
}); */

function attachEventToRequestButtons() {
  const professionistiButtons = document.querySelectorAll('.worker-menu .call-worker-button');

  professionistiButtons.forEach(button => {
    button.addEventListener('click', function () {
      showProfessionalDetails(this);
    });
  });

  const sendRequestButton = document.querySelector('.send-request-button');
  sendRequestButton.addEventListener('click', sendRequest);
}

function showProfessionalDetails(button) {
  const workerEntry = button.closest('.worker-entry');
  const nome = workerEntry.dataset.name;
  const professione = workerEntry.dataset.category;
  const prezzoOrario = workerEntry.dataset.hourprice;
  const prezzoChiamata = workerEntry.dataset.callprice;
  const posizione = workerEntry.querySelector('.worker-position span').textContent;
  const img = workerEntry.dataset.img;

  // Nascondi la mappa
  const map = document.getElementById('map');
  map.classList.add('hidden');

  // Mostra i dettagli del professionista
  const detailsContainer = document.getElementById('professional-details');
  document.getElementById('professional-image').src = img;
  document.getElementById('professional-name').textContent = nome;
  document.getElementById('professional-category').textContent = professione;
  document.getElementById('professional-address').textContent = posizione;
  document.getElementById('professional-call-price').textContent = prezzoChiamata;
  document.getElementById('professional-hour-price').textContent = prezzoOrario;
  detailsContainer.dataset.id = workerEntry.dataset.id;
  detailsContainer.dataset.name = nome;
  detailsContainer.dataset.callprice = prezzoChiamata;
  detailsContainer.dataset.hourprice = prezzoOrario;
  detailsContainer.classList.remove('hidden');
}

function sendRequest() {
  const detailsContainer = document.getElementById('professional-details');

  const professionalId = detailsContainer.dataset.id;
  const professionalName = detailsContainer.dataset.name;
  const callPrice = detailsContainer.dataset.callprice;
  const hourPrice = detailsContainer.dataset.hourprice;
  const details = document.getElementById('request').value;

  const requestData = {
    professionalId,
    professionalName,
    callPrice,
    hourPrice,
    details,
    userLng: userMarker.getPosition().lng(),
    userLat: userMarker.getPosition().lat()
  };

  fetch('utilities.php?action=addRequest', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(requestData)
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        handleSentRequest(professionalName, professionalId, data.requestId, 'live');
        startPolling(data.requestId);
      } else {
        alert('Errore durante invio della richiesta');
      }
    })
    .catch(error => {
      console.error('Errore:', error);
    });
}

function handleSentRequest(professionalName, professionalId, requestId, type) {
  document.getElementById('professional-details').classList.add('hidden');
  document.querySelector('.request-pending-container').classList.remove('hidden');
  document.querySelector('.professional-name-post').textContent = professionalName;


  const requestAcceptedContainer = document.querySelector('.request-pending-container');
  requestAcceptedContainer.dataset.requestId = requestId;

  if (type === 'past') {
    document.addEventListener('MapInitialized', function () {
      document.querySelectorAll('.call-worker-button').forEach(button => {
        button.disabled = true;
        button.classList.add('request-sent');
        if (button.closest('.worker-entry').dataset.id === professionalId) {
          button.textContent = 'Richiesta inviata';
        }
      });
    });
  } else {
    document.querySelectorAll('.call-worker-button').forEach(button => {
      button.disabled = true;
      button.classList.add('request-sent');
      if (button.closest('.worker-entry').dataset.id === professionalId) {
        button.textContent = 'Richiesta inviata';
      }
    });
  }
}

function handleFinishedRequest() {
  console.log('Request has been closed');

  document.querySelectorAll('.call-worker-button').forEach(button => {
    button.disabled = false;
    button.classList.remove('request-sent');
    button.textContent = 'Richiedi';
  });
  const rejectedContainer = document.querySelector('.request-rejected-container');
  if (rejectedContainer.classList.contains('hidden')) {
    document.getElementById('map').classList.remove('hidden');
  }
}


function startPolling(requestId) {
  const pollRequest = () => {
    fetch(`utilities.php?action=getRequestDetails&requestId=${requestId}`)
      .then(response => response.json())
      .then(data => {
        console.log(data);
        const map = document.getElementById('map');
        if (!map.classList.contains('hidden')) { map.classList.add('hidden'); }

        if (data.success && data.requestDetails) {
          if (data.requestDetails.status === 'accepted') {
            handleRequestAccept(data.requestDetails);
          } else if (data.requestDetails.status === 'completed') {
            updateClientOnCompletion(requestId);
          } else if (data.requestDetails.status === 'canceled') {
            clearInterval(pollingInterval);
            clearCanceledRequest(requestId);
          }
        } else if (!data.success && data.error === 'Request not found') {
          handleRequestRemoval();
        }
      })
      .catch(error => console.error('Error fetching request details:', error));
  };

  pollRequest();
  pollingInterval = setInterval(pollRequest, 5000);
}

function clearCanceledRequest() {
  const requestContainer = document.querySelector('.request-pending-container');
  const requestId = requestContainer.dataset.requestId;

  fetch('utilities.php?action=clearCanceledRequest', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ requestId })
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        console.log('Request cleared');
      } else {
        console.error('Error deleting request:', data.error);
      }
    })
    .catch(error => console.error('Error deleting request:', error));
}

function handleRequestAccept(details) {
  console.log('Request has been accepted');
  document.dispatchEvent(new CustomEvent('RequestAccepted', { detail: { details: details } }));
  console.log(details);
  document.querySelector('.request-pending-container').classList.add('hidden');
  document.querySelector('.request-accepted-container').classList.remove('hidden');
  document.querySelector('.professional-name-banner').textContent = details.professionalName;
  document.querySelector('.banner-internal-container img').src = `img/professionals/${details.professionalName.split(' ')[0].toLowerCase() + '-' + details.professionalName.split(' ')[1].toLowerCase() + '-' + details.professionalId}.jpeg`;
}


function handleRequestRemoval() {
  console.log('Request has been rejected or removed');
  document.querySelector('.request-pending-container').classList.add('hidden');
  document.querySelector('.request-rejected-container').classList.remove('hidden');
  document.querySelector('.professional-name-rejected').textContent = document.querySelector('.professional-name-post').textContent;

  const closeRejectedButton = document.querySelector('#close-rejected-button');
  closeRejectedButton.addEventListener('click', function () {
    document.querySelector('.request-rejected-container').classList.add('hidden');
    handleFinishedRequest();
  });

  clearInterval(pollingInterval);
}

function updateClientOnCompletion(requestId) {
  console.log('Request has been completed');
  document.querySelector('.request-accepted-container').classList.add('hidden');

  clearInterval(pollingInterval);
  stopProfessionalTracking();

  fetch('utilities.php?action=deleteRequest', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ requestId })
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        console.log('Request deleted');
      } else {
        console.error('Error deleting request:', data.error);
      }
    })
    .catch(error => console.error('Error deleting request:', error));

  const ratingModal = document.getElementById('rating-modal')
  ratingModal.classList.remove('hidden');
  const submitRatingButton = document.getElementById('submit-rating');
  submitRatingButton.addEventListener('click', function () {
    const selectedRating = document.querySelector('input[name="rating"]:checked').value;
    console.log('Valutazione selezionata:', selectedRating);

    fetch('utilities.php?action=submitRating', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ requestId, rating: selectedRating })
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          ratingModal.classList.add('hidden');
          handleFinishedRequest();
        } else {
          console.error('Errore durante l\'invio della valutazione:', data.error);
        }
      })
      .catch(error => {
        console.error('Errore durante l\'invio della valutazione:', error);
      });
    
  });



}

function chiudiDettagli() {
  document.getElementById('map').classList.remove('hidden');
  document.getElementById('professional-details').classList.add('hidden');
}


function setupCancellation() {
  const cancelButton = document.querySelector('#cancel-button');
  cancelButton.addEventListener('click', function () {
    cancelRequest();
    clearCanceledRequest();
  });
}

function cancelRequest() {
  const requestContainer = document.querySelector('.request-pending-container');
  const requestId = requestContainer.dataset.requestId;

  fetch('utilities.php?action=cancelRequest', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ requestId })
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        closeRequestAccepted();
      } else {
        alert('Errore durante annullamento della richiesta.');
      }
    })
    .catch(error => {
      console.error('Errore:', error);
    });
}

function closeRequestAccepted() {
  const requestAcceptedContainer = document.querySelector('.request-pending-container');
  requestAcceptedContainer.classList.add('hidden');
  document.getElementById('map').classList.remove('hidden');

  document.querySelectorAll('.call-worker-button').forEach(button => {
    button.disabled = false;
    button.classList.remove('request-sent');
    button.textContent = 'Richiedi';
  });
  clearInterval(pollingInterval);
}

document.addEventListener("DOMContentLoaded", function () {
  setupCancellation();
});
