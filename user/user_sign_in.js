console.log("User Sign-In script loaded.");

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("loginForm");

    if (!form) return;

    form.addEventListener("submit", function(e) {
        e.preventDefault();
        console.log("Login form submitted.");

        const formData = new FormData(form);

        fetch("user_sign_in.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
        if(data.success){
            // Update the global isLoggedIn variable
            window.isLoggedIn = true;
            
            // Close the modal using the function
            closeSignInModal();
            
            // Also ensure it's hidden
            const modal = document.getElementById("signin-modal");
            if (modal) {
                modal.style.display = "none";
                modal.style.visibility = "hidden";
            }
            
            // Clear form fields
            form.reset();

            // Update UI: show user's name and replace sign-in/register with logout
            const status = document.querySelector('.user-status');
            if (status) {
                status.innerHTML = '<span class="status-badge logged-in">✓ Logged In</span> ' + (data.user_name || 'User');
            }

            const dropdown = document.querySelector('.user-dropdown');
            if (dropdown) dropdown.innerHTML = '<a href="user_logout.php">Logout</a>';

            // Update the Get Car button to be a submit button
            const submitBtn = document.querySelector('.btn-quote');
            if (submitBtn) {
                submitBtn.type = "submit";
                submitBtn.onclick = null;
            }

            // Also update the Send Message button if it exists
            const contactBtn = document.querySelector('.contact-form .btn-primary');
            if (contactBtn) {
                contactBtn.type = "submit";
                contactBtn.onclick = null;
            }

        } else {
            alert("Error: " + data.message);
        }
        })
        .catch(err => console.error(err));     
    });
});
