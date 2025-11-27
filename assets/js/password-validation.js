document.addEventListener('DOMContentLoaded', function() {
    const newPasswordInput = document.getElementById('new_password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const passwordStrength = document.getElementById('passwordStrength');
    const passwordStrengthText = document.getElementById('passwordStrengthText');
    const passwordMatch = document.getElementById('passwordMatch');
    const form = document.getElementById('changePasswordForm');

    if (newPasswordInput) {
        newPasswordInput.addEventListener('input', function() {
            validatePasswordStrength(this.value);
            checkPasswordMatch();
        });
    }

    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', function() {
            checkPasswordMatch();
        });
    }

    function validatePasswordStrength(password) {
        let strength = 0;
        let feedback = [];

        // Length check
        if (password.length >= 8 && password.length <= 16) {
            strength += 20;
        } else {
            feedback.push('Panjang 8-16 karakter');
        }

        // Uppercase check
        if (/[A-Z]/.test(password)) {
            strength += 20;
        } else {
            feedback.push('Huruf besar');
        }

        // Lowercase check
        if (/[a-z]/.test(password)) {
            strength += 20;
        } else {
            feedback.push('Huruf kecil');
        }

        // Digit check
        if (/[0-9]/.test(password)) {
            strength += 20;
        } else {
            feedback.push('Angka');
        }

        // Special character check
        if (/[!@#$%^&*()\-_=+,.?]/.test(password)) {
            strength += 20;
        } else {
            feedback.push('Karakter khusus');
        }

        // Update progress bar
        passwordStrength.style.width = strength + '%';
        
        // Update color and text
        if (strength < 40) {
            passwordStrength.className = 'progress-bar bg-danger';
            passwordStrengthText.textContent = 'Sangat Lemah';
            passwordStrengthText.className = 'text-danger';
        } else if (strength < 60) {
            passwordStrength.className = 'progress-bar bg-warning';
            passwordStrengthText.textContent = 'Lemah';
            passwordStrengthText.className = 'text-warning';
        } else if (strength < 80) {
            passwordStrength.className = 'progress-bar bg-info';
            passwordStrengthText.textContent = 'Sedang';
            passwordStrengthText.className = 'text-info';
        } else if (strength < 100) {
            passwordStrength.className = 'progress-bar bg-primary';
            passwordStrengthText.textContent = 'Kuat';
            passwordStrengthText.className = 'text-primary';
        } else {
            passwordStrength.className = 'progress-bar bg-success';
            passwordStrengthText.textContent = 'Sangat Kuat';
            passwordStrengthText.className = 'text-success';
        }

        if (feedback.length > 0 && password.length > 0) {
            passwordStrengthText.textContent += ' - Perlu: ' + feedback.join(', ');
        }
    }

    function checkPasswordMatch() {
        const newPassword = newPasswordInput.value;
        const confirmPassword = confirmPasswordInput.value;

        if (confirmPassword.length === 0) {
            passwordMatch.textContent = '';
            passwordMatch.className = 'form-text';
            return;
        }

        if (newPassword === confirmPassword) {
            passwordMatch.textContent = '✓ Password cocok';
            passwordMatch.className = 'form-text text-success';
        } else {
            passwordMatch.textContent = '✗ Password tidak cocok';
            passwordMatch.className = 'form-text text-danger';
        }
    }

    // Form validation
    if (form) {
        form.addEventListener('submit', function(e) {
            const newPassword = newPasswordInput.value;
            const confirmPassword = confirmPasswordInput.value;

            // Check password strength
            let strength = 0;
            if (newPassword.length >= 8 && newPassword.length <= 16) strength += 20;
            if (/[A-Z]/.test(newPassword)) strength += 20;
            if (/[a-z]/.test(newPassword)) strength += 20;
            if (/[0-9]/.test(newPassword)) strength += 20;
            if (/[!@#$%^&*()\-_=+,.?]/.test(newPassword)) strength += 20;

            if (strength < 100) {
                e.preventDefault();
                alert('Password harus memenuhi semua kriteria: 8-16 karakter, huruf besar, huruf kecil, angka, dan karakter khusus');
                return false;
            }

            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('Password baru dan konfirmasi password tidak cocok');
                return false;
            }
        });
    }
});

