document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.increment-button, .decrement-button').forEach(button => {
        button.addEventListener('click', function () {
            const input = this.parentNode.previousElementSibling;
            const isIncrement = this.classList.contains('increment-button');
            const newValue = isIncrement ? parseInt(input.value) + 1 : parseInt(input.value) - 1;
            if (newValue >= 0) {
                input.value = newValue;
                updatePrice(input.id, newValue);
            }
        });
    });

    document.getElementById('active-pro').addEventListener('change', function () {
        updateActiveStatus(this.checked);
    });



    function updatePrice(priceType, value) {
        const payload = {
            priceType: priceType,
            value: value
        };

        fetch('utilities.php?action=updatePrice', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
            .then(response => response.json())
            .then(data => {
                console.log(data);
            })
            .catch(error => console.error('Error:', error));
    }


    function updateActiveStatus(isActive) {
        const payload = {
            isActive: isActive
        };

        fetch('utilities.php?action=updateActive', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
            .then(response => response.json())
            .then(data => {
                console.log(data);
            })
            .catch(error => console.error('Error:', error));
    }


});
