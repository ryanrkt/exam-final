document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("#registerForm");
    if (!form) return;

    // Champs
    const nom = document.querySelector("#nom");
    const prenom = document.querySelector("#prenom");
    const email = document.querySelector("#email");
    const password = document.querySelector("#password");
    const confirmPassword = document.querySelector("#confirm-password");

    // Zones d'erreur (Bootstrap invalid-feedback)
    const nomError = document.querySelector("#nomError");
    const prenomError = document.querySelector("#prenomError");
    const emailError = document.querySelector("#emailError");
    const passwordError = document.querySelector("#passwordError");
    const confirmPasswordError = document.querySelector("#confirmPasswordError");

    // Regex email simple
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // Utilitaires
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

    // Validation des champs
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

    // Validation du formulaire
    form.addEventListener("submit", (e) => {
        e.preventDefault();
        let valid = true;
        valid &= validateNom();
        valid &= validatePrenom();
        valid &= validateEmail();
        valid &= validatePassword();
        valid &= validateConfirmPassword();

        if (valid) {
            form.submit();
        }
    });

    // Ajout des écouteurs d'événements blur pour la validation en direct
    nom.addEventListener("blur", validateNom);
    prenom.addEventListener("blur", validatePrenom);
    email.addEventListener("blur", validateEmail);
    password.addEventListener("blur", validatePassword);
    confirmPassword.addEventListener("blur", validateConfirmPassword);
});
