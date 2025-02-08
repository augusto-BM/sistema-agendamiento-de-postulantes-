document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('pass');
        const passwordType = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', passwordType);
        this.querySelector('i').classList.toggle('fa-eye-slash');
        this.querySelector('i').classList.toggle('fa-eye');
    });
  });
  