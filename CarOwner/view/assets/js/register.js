document.getElementById("registerForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const fullName = document.getElementById("fullName").value.trim();
    const email = document.getElementById("email").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;

    const errorMsg = document.getElementById("errorMsg");
    errorMsg.style.color = "#ff4d4d"; 
    errorMsg.textContent = "";

    // NAME: alphabets + spaces only
    const namePattern = /^[A-Za-z ]+$/;
    if (!namePattern.test(fullName) || fullName.length < 3) {
        errorMsg.textContent = "Name must contain only letters and be at least 3 characters.";
        return;
    }

    const emailPattern = /^[A-Za-z0-9._-]+@[A-Za-z]+\.[A-Za-z]{2,3}$/;
    if (!emailPattern.test(email)) {
        errorMsg.textContent = "Please enter a valid email address.";
        return;
    }

    if (!/^01\d{9}$/.test(phone)) {
        errorMsg.textContent = "Phone number must be 11 digits and start with 01.";
        return;
    }

    // PASSWORD
    if (password.length < 6) {
        errorMsg.textContent = "Password must be at least 6 characters.";
        return;
    }

    // CONFIRM PASSWORD
    if (password !== confirmPassword) {
        errorMsg.textContent = "Passwords do not match.";
        return;
    }

    // SUCCESS
    errorMsg.style.color = "rgb(0, 190, 0)";
    errorMsg.textContent = "Registration successful!";
    this.submit();
});
