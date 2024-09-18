document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("changePasswordForm");
    const responseMessage = document.getElementById("passwordresponseMessage");
    const passwordError = document.getElementById("password_error");

    form.addEventListener("submit", function (event) {
        event.preventDefault();
        const formData = new FormData(this);

         clearMessages();


        const oldPassword = document.getElementById("old_password").value;
        const newPassword = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirm_password").value;

        if (newPassword !== confirmPassword) {
            passwordError.textContent = "Le nuove password non coincidono.";
            return;
        }

        fetch('change_password.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text()) 
        .then(data => {
            try {
                const jsonData = JSON.parse(data);  
                if (jsonData.success) {
                    showMessage(responseMessage, 'Password aggiornata con successo!', 'green');
                    clearForm();
                } else {
                    if (jsonData.message === "La vecchia password non è corretta.") {
                        showError(passwordError, jsonData.message);
                    } else {
                        showMessage(responseMessage, 'Errore durante l\'aggiornamento del profilo.', 'red');
                    }
                }
            } catch (e) {
                showMessage(responseMessage, 'Errore durante la richiesta: ' + data, 'red');
                console.error('Error:', data);
            }
        })
        .catch(error => {
            showMessage(responseMessage, 'Errore durante la richiesta: ' + error.message, 'red');
            console.error('Error:', error);
        });
    });

    function showMessage(element, message, color) {
        element.textContent = message;
        element.style.color = color;
    }

    function showError(element, message) {
        element.textContent = message;
    }

    function clearMessages() {
        passwordError.textContent = '';
        responseMessage.textContent = '';
    }

    function clearForm() {
        document.getElementById("old_password").value = '';
        document.getElementById("password").value = '';
        document.getElementById("confirm_password").value = '';
    }
});