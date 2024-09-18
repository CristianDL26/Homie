document.addEventListener('DOMContentLoaded', function() {
    const displayedAddress = document.querySelector('.address-button .address');
    const addressButton = document.querySelector('.address-submit');

    window.handleAddressChange = function(addressButton) {
        const newAddress = addressButton.textContent.trim();
        console.log(newAddress);
        if(newAddress) {
            fetch('utilities.php?action=updateAddress', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ address: newAddress })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    updateMap(parseFloat(data.lat), parseFloat(data.lng));
                    document.querySelector('.address').textContent = newAddress;
                } else {
                    console.error('Error updating address:', data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }

    function updateMap(lat, lng) {
        console.log(lat, lng);
        const newPosition = { lat, lng };
        userMarker.setPosition(newPosition);
        map.setCenter(newPosition);
    }

    

    fetch('utilities.php?action=getAddress')
    .then(response => response.json())
    .then(data => {
        console.log(data);
        if(data.success) {
            displayedAddress.innerHTML = data.address;
            document.addEventListener('MapInitialized', function() {
                
                updateMap(data.lat, data.lng);
            });
        } else {
            console.error('Failed to fetch address');
        }
    });
});
