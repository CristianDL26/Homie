function checkEmailExists() {
    const email= document.getElementById('email').value;

    const query = `SELECT COUNT(*) AS count FROM homie.user_data WHERE email = '${email}'`;

    const result = executeQuery(query);

    if (result.count > 0) {
        var emailError = 'This email is already registered';
        document.getElementById('email_error').innerHTML = emailError;
    } 
}