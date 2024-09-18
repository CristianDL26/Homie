document.addEventListener('DOMContentLoaded', function () {
    const editButton = document.getElementById('editButton');
    const saveButton = document.getElementById('saveButton');
    const cancelButton = document.getElementById('cancelButton');
    const inputs = document.querySelectorAll('#profileForm input');
    const responseMessage = document.getElementById('responseMessage');
    document.querySelector(".navigation-bar button.active-btn").click();

    editButton.addEventListener('click', function () {
        inputs.forEach(input => input.disabled = false);
        editButton.style.display = 'none';
        saveButton.style.display = 'inline-block';
        cancelButton.style.display = 'inline-block';
    });

    cancelButton.addEventListener('click', function () {
        inputs.forEach(input => {
            input.disabled = true;
            input.value = input.defaultValue;
        });
        editButton.style.display = 'inline-block';
        saveButton.style.display = 'none';
        cancelButton.style.display = 'none';
    });

    document.getElementById('profileForm').addEventListener('submit', function (event) {
        event.preventDefault();
        const formData = new FormData(this);
        console.log('Form data:', ...formData.entries()); 

        fetch('update_profile.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                responseMessage.textContent = 'Profilo aggiornato con successo!';
                responseMessage.style.color = 'green';

                inputs.forEach(input => input.defaultValue = input.value);

                inputs.forEach(input => input.disabled = true);
                editButton.style.display = 'inline-block';
                saveButton.style.display = 'none';
                cancelButton.style.display = 'none';

                window.location.reload(); 
            } else {
                responseMessage.textContent = 'Errore durante l\'aggiornamento del profilo.';
                responseMessage.style.color = 'red';
            }
        })
        .catch(error => {
            responseMessage.textContent = 'Errore durante la richiesta: ' + error.message;
            responseMessage.style.color = 'red';
            console.error('Error:', error);
        });
    });
});