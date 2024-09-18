function checkPassword() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const errorElement = document.getElementById('password_error');

    const minLength = 8;
    const hasUpperCase = /[A-Z]/.test(password);
    const hasLowerCase = /[a-z]/.test(password);
    const hasNumber = /[0-9]/.test(password);
    const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);

    let errorMessage = '';

    if (password.length < minLength) {
        errorMessage = `La password deve contenere almeno ${minLength} caratteri.`;
    } else if (!hasUpperCase) {
        errorMessage = 'La password deve contenere almeno una lettera maiuscola.';
    } else if (!hasLowerCase) {
        errorMessage = 'La password deve contenere almeno una lettera minuscola.';
    } else if (!hasNumber) {
        errorMessage = 'La password deve contenere almeno un numero.';
    } else if (!hasSpecialChar) {
        errorMessage = 'La password deve contenere almeno un carattere speciale.';
    } else if (password !== confirmPassword) {
        errorMessage = 'Le password non corrispondono.';
    }else errorMessage = '';

    if (errorMessage) {
        errorElement.textContent = errorMessage;
        document.querySelector('.login-button').disabled = true;
    } else {
        errorElement.textContent = '';
        document.querySelector('.login-button').disabled = false;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('password').addEventListener('keyup', checkPassword);
    document.getElementById('confirm_password').addEventListener('keyup', checkPassword);
});
