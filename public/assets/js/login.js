// Select password field and toggle icon

const passwordField = document.getElementById('password');
const togglePassword = document.getElementById('togglePassword');

// add a click event listener to icon

togglePassword.addEventListener('click', function () {

    // Toggle the type attribute of password field

    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);

    // toggle the icon between eye and eye-slash

    this.querySelector('i').classList.toggle(show-alt);
    this.querySelector('i').classList.toggle(hide);
})