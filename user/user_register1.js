document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("registerForm");
    if (!form) return;

    form.addEventListener("submit", function(e) {
        e.preventDefault();

        //grab values
        const email = document.getElementById("reg-email").value.trim();
        const password = document.getElementById("reg-password").value;
        const confirm = document.getElementById("reg-confirm").value;

        //email domain check
        const emailRegex = /^[a-zA-Z0-9._%+-]+@(google\.com|sample\.com|gmail\.com|yahoo\.com|example\.com)$/i;
        
        if (!emailRegex.test(email)) {
            alert("Registration Failed: Invalid Email Domain. Please use @google.com, @sample.com, or @gmail.com.");
            return;
        }

        //password matching check
        if (password !== confirm) {
            alert("Passwords do not match!");
            return; // EXIT HERE
        }

        //password complexity check
        const passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{6,}$/;
        if (!passwordRegex.test(password)) {
            alert("Weak Password! Use 6+ characters with a Capital letter, Number, and Symbol.");
            return;
        }

        //data submission
        const formData = new FormData(this);
        fetch("user_register.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                alert("Register successful!");
                closeRegisterModal();
                window.location.reload();
            } else {
                alert("Server Error: " + data.message);
            }
        })
        .catch(err => {
            console.error("Connection error:", err);
            alert("System Error: Could not reach the server.");
        });
    });
});