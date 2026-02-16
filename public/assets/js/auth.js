document.addEventListener("DOMContentLoaded", () => {
    // ==================== INSCRIPTION ====================
    const registerForm = document.querySelector("#registerForm");
    if (registerForm) {
        const nom = document.querySelector("#nom");
        const prenom = document.querySelector("#prenom");
        const email = document.querySelector("#email");
        const password = document.querySelector("#password");
        const confirmPassword = document.querySelector("#confirm-password");

        const nomError = document.querySelector("#nomError");
        const prenomError = document.querySelector("#prenomError");
        const emailError = document.querySelector("#emailError");
        const passwordError = document.querySelector("#passwordError");
        const confirmPasswordError = document.querySelector("#confirmPasswordError");

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        function setFieldError(field, errorBox, message) {
            field.classList.add("is-invalid");
            field.classList.remove("is-valid");
            if (errorBox) errorBox.textContent = message;
        }

        function setFieldValid(field, errorBox) {
            field.classList.remove("is-invalid");
            field.classList.add("is-valid");
            if (errorBox) errorBox.textContent = "";
        }

        function validateNom() {
            const v = nom.value.trim();
            if (v.length < 2) {
                setFieldError(nom, nomError, "Le nom doit contenir au moins 2 caractères.");
                return false;
            }
            setFieldValid(nom, nomError);
            return true;
        }

        function validatePrenom() {
            const v = prenom.value.trim();
            if (v.length < 2) {
                setFieldError(prenom, prenomError, "Le prénom doit contenir au moins 2 caractères.");
                return false;
            }
            setFieldValid(prenom, prenomError);
            return true;
        }

        function validateEmail() {
            const v = email.value.trim();
            if (v === "") {
                setFieldError(email, emailError, "L'email est obligatoire.");
                return false;
            }
            if (!emailRegex.test(v)) {
                setFieldError(email, emailError, "L'email n'est pas valide.");
                return false;
            }
            setFieldValid(email, emailError);
            return true;
        }

        function validatePassword() {
            const v = password.value;
            if (v.length < 8) {
                setFieldError(password, passwordError, "Le mot de passe doit contenir au moins 8 caractères.");
                return false;
            }
            setFieldValid(password, passwordError);
            return true;
        }

        function validateConfirmPassword() {
            const p = password.value;
            const c = confirmPassword.value;
            if (c !== p) {
                setFieldError(confirmPassword, confirmPasswordError, "Les mots de passe ne correspondent pas.");
                return false;
            }
            setFieldValid(confirmPassword, confirmPasswordError);
            return true;
        }

        registerForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            
            let valid = true;
            valid &= validateNom();
            valid &= validatePrenom();
            valid &= validateEmail();
            valid &= validatePassword();
            valid &= validateConfirmPassword();

            if (valid) {
                const formData = new FormData();
                formData.append('nom', nom.value.trim());
                formData.append('prenom', prenom.value.trim());
                formData.append('email', email.value.trim());
                formData.append('password', password.value);
                formData.append('confirm_password', confirmPassword.value);

                try {
                    const response = await fetch('/register', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();
                    
                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        alert(data.message || 'Erreur lors de l\'inscription');
                    }
                } catch (error) {
                    console.error('Erreur:', error);
                    alert('Erreur de connexion au serveur');
                }
            }
        });

        // Validation en temps réel
        nom.addEventListener("blur", validateNom);
        prenom.addEventListener("blur", validatePrenom);
        email.addEventListener("blur", validateEmail);
        password.addEventListener("blur", validatePassword);
        confirmPassword.addEventListener("blur", validateConfirmPassword);
    }

    // ==================== CONNEXION ====================
    const loginForm = document.querySelector("#loginForm");
    if (loginForm) {
        loginForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            
            const emailField = document.querySelector("#loginEmail");
            const passwordField = document.querySelector("#loginPassword");
            
            const formData = new FormData();
            formData.append('email', emailField.value.trim());
            formData.append('password', passwordField.value);

            try {
                const response = await fetch('/login', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    alert(data.message || 'Erreur de connexion');
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur de connexion au serveur');
            }
        });
    }
});
