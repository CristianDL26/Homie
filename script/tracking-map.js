let trackingMap;
let professionalMarker;
let positionUpdateInterval;

function initTrackingMap(details) {
    const mapContainer = document.getElementById('tracking-map');
    if (mapContainer) {
        trackingMap = new google.maps.Map(mapContainer, {
            zoom: 15,
            center: { lat: 41.9028, lng: 12.4964 },
            styles: customStyle,
            streetViewControl: false,
            mapTypeControl: false
        });

        professionalMarker = new google.maps.Marker({
            position: { lat: 41.9028, lng: 12.4964 },
            map: trackingMap,
            title: details.professionalName,
            icon: {
                url: 'img/marker.png',
                scaledSize: new google.maps.Size(50, 50)
            }
        });


        userMarker = new google.maps.Marker({
            position: {lat: details.userLat, lng: details.userLng},
            map: trackingMap,
            title: 'Sei qui!',
            icon: {
                url: 'img/icons/house_pointer.png',
                scaledSize: new google.maps.Size(50, 50)
            }
        });

    }
}

function updateProfessionalPosition(lat, lng) {
    if (trackingMap && professionalMarker) {
        const newPosition = { lat, lng };
        professionalMarker.setPosition(newPosition);
        trackingMap.setCenter(newPosition);
    }
}

function startProfessionalTracking(details) {
    const professionalId = details.professionalId;

    function fetchAndUpdatePosition() {
        fetch(`utilities.php?action=getProfessionalPosition`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ professionalId: professionalId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateProfessionalPosition(data.lat, data.lng);
            }
        })
        .catch(error => console.error('Error fetching professional position:', error));
    }

    if (positionUpdateInterval) {
        clearInterval(positionUpdateInterval);
    }

    fetchAndUpdatePosition(); 

    positionUpdateInterval = setInterval(fetchAndUpdatePosition, 5000);
}

function stopProfessionalTracking() {
    if (positionUpdateInterval) {
        clearInterval(positionUpdateInterval);
        positionUpdateInterval = null;
        console.log('Professional tracking stopped.');
    }
}

document.addEventListener('RequestAccepted', event => {
    const details = event.detail.details;
    if (!trackingMap) {
        initTrackingMap(details);
    }
    startProfessionalTracking(details);
});
