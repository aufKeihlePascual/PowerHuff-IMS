// On page load, check cookies and auto-fill username and password
window.onload = function() {
    const username = getCookie('username');
    const password = getCookie('password');
    if (username) document.getElementById('username').value = username;
    if (password) document.getElementById('password').value = password;
    if (username && password) document.getElementById('remember').checked = true;
};

// Get cookie value by name
function getCookie(name) {
    let nameEQ = name + "=";
    let ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i].trim();
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

// Toggle password visibility
function togglePassword() {
    const passwordField = document.getElementById('password');
    const type = passwordField.type === 'password' ? 'text' : 'password';
    passwordField.type = type;
}

// Handle Remember Me on form submission
const form = document.querySelector('form');
form.onsubmit = function() {
    if (document.getElementById('remember').checked) {
        document.cookie = "username=" + document.getElementById('username').value + "; max-age=" + 60 * 60 * 24 * 30;
        document.cookie = "password=" + document.getElementById('password').value + "; max-age=" + 60 * 60 * 24 * 30;
    } else {
        document.cookie = "username=; max-age=0";
        document.cookie = "password=; max-age=0";
    }
};

// Forgot password alert
function forgotPassword() {
    alert('You have successfully submitted a reset password request to the admin.');
}
