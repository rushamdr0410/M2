document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.edit-form');
    const username = document.getElementById('username');
    const email = document.getElementById('email');
    const currentPassword = document.getElementById('current_password');
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('confirm_password');
    const location = document.getElementById('location');
    const bio = document.getElementById('bio');

    // Error message elements
    const usernameError = document.createElement('div');
    usernameError.className = 'error-message';
    username.parentNode.appendChild(usernameError);

    const emailError = document.createElement('div');
    emailError.className = 'error-message';
    email.parentNode.appendChild(emailError);

    const passwordError = document.createElement('div');
    passwordError.className = 'error-message';
    newPassword.parentNode.appendChild(passwordError);

    const confirmPasswordError = document.createElement('div');
    confirmPasswordError.className = 'error-message';
    confirmPassword.parentNode.appendChild(confirmPasswordError);

    const locationError = document.createElement('div');
    locationError.className = 'error-message';
    location.parentNode.appendChild(locationError);

    const bioError = document.createElement('div');
    bioError.className = 'error-message';
    bio.parentNode.appendChild(bioError);

    // Validation functions
    function validateUsername() {
        const usernameValue = username.value.trim();
        if (usernameValue === '') {
            usernameError.textContent = 'Username is required';
            return false;
        }
        if (usernameValue.length < 3) {
            usernameError.textContent = 'Username must be at least 3 characters long';
            return false;
        }
        if (!/^[a-zA-Z0-9_]+$/.test(usernameValue)) {
            usernameError.textContent = 'Username can only contain letters, numbers, and underscores';
            return false;
        }
        usernameError.textContent = '';
        return true;
    }

    function validateEmail() {
        const emailValue = email.value.trim();
        if (emailValue === '') {
            emailError.textContent = 'Email is required';
            return false;
        }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailValue)) {
            emailError.textContent = 'Please enter a valid email address';
            return false;
        }
        emailError.textContent = '';
        return true;
    }

    function validatePassword() {
        const newPasswordValue = newPassword.value;
        if (newPasswordValue === '') {
            passwordError.textContent = '';
            return true;
        }
        if (newPasswordValue.length < 8) {
            passwordError.textContent = 'Password must be at least 8 characters long';
            return false;
        }
        if (!/(?=.*[a-z])/.test(newPasswordValue)) {
            passwordError.textContent = 'Password must contain at least one lowercase letter';
            return false;
        }
        if (!/(?=.*[A-Z])/.test(newPasswordValue)) {
            passwordError.textContent = 'Password must contain at least one uppercase letter';
            return false;
        }
        if (!/(?=.*\d)/.test(newPasswordValue)) {
            passwordError.textContent = 'Password must contain at least one number';
            return false;
        }
        if (!/(?=.*[!@#$%^&*])/.test(newPasswordValue)) {
            passwordError.textContent = 'Password must contain at least one special character';
            return false;
        }
        passwordError.textContent = '';
        return true;
    }

    function validateConfirmPassword() {
        const confirmPasswordValue = confirmPassword.value;
        const newPasswordValue = newPassword.value;
        
        if (newPasswordValue === '' && confirmPasswordValue === '') {
            confirmPasswordError.textContent = '';
            return true;
        }
        
        if (confirmPasswordValue !== newPasswordValue) {
            confirmPasswordError.textContent = 'Passwords do not match';
            return false;
        }
        confirmPasswordError.textContent = '';
        return true;
    }

    function validateLocation() {
        const locationValue = location.value.trim();
        if (locationValue === '') {
            locationError.textContent = '';
            return true;
        }
        if (locationValue.length < 3) {
            locationError.textContent = 'Location must be at least 3 characters long';
            return false;
        }
        locationError.textContent = '';
        return true;
    }

    function validateBio() {
        const bioValue = bio.value.trim();
        if (bioValue.length > 500) {
            bioError.textContent = 'Bio must be less than 500 characters';
            return false;
        }
        bioError.textContent = '';
        return true;
    }

    // Event listeners for real-time validation
    username.addEventListener('input', validateUsername);
    email.addEventListener('input', validateEmail);
    newPassword.addEventListener('input', validatePassword);
    confirmPassword.addEventListener('input', validateConfirmPassword);
    location.addEventListener('input', validateLocation);
    bio.addEventListener('input', validateBio);

    // Form submission validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const isUsernameValid = validateUsername();
        const isEmailValid = validateEmail();
        const isPasswordValid = validatePassword();
        const isConfirmPasswordValid = validateConfirmPassword();
        const isLocationValid = validateLocation();
        const isBioValid = validateBio();

        if (isUsernameValid && isEmailValid && isPasswordValid && 
            isConfirmPasswordValid && isLocationValid && isBioValid) {
            
            // If current password is provided, it must be verified
            if (currentPassword.value.trim() !== '') {
                // Here you would typically make an AJAX call to verify the current password
                // For now, we'll just submit the form
                form.submit();
            } else if (newPassword.value.trim() !== '') {
                // If new password is provided but current password is not
                alert('Please enter your current password to change your password');
                return false;
            } else {
                // No password changes, submit the form
                form.submit();
            }
        }
    });
}); 