document.getElementById("submit-form").addEventListener("submit", function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    
    try {
        // Check if logged in
        if (!window.isLoggedIn) {
            alert("Please sign in to book a car.");
            openSignInModal();
            return false;
        }
        
        // Get all form values
        const fullname = document.getElementsByName("fullname")[0]?.value.trim() || "";
        const email = document.getElementsByName("email")[0]?.value.trim() || "";
        const contact = document.getElementsByName("phone")[0]?.value.trim() || "";
        const cartype = document.getElementsByName("cartype")[0]?.value || "";
        const service = document.getElementsByName("service")[0]?.value || "";
        const pickupLoc = document.getElementsByName("pickup_loc")[0]?.value.trim() || "";
        const pickupVal = document.getElementsByName("pickup_dt")[0]?.value || "";
        const returnVal = document.getElementsByName("return_dt")[0]?.value || "";
        const licenseInput = document.querySelector('input[type="file"]');
        
        // Validate all required fields
        if (!fullname) {
            alert("Please enter your full name.");
            return false;
        }

        if (!email || !contact || !pickupVal || !returnVal || !cartype || !service || !pickupLoc) {
            alert("Please fill in all required fields.");
            return false;
        }

        // Driver's License Check
        if (!licenseInput || licenseInput.files.length === 0) {
            alert("Please upload a copy of your Driver's License.");
            return false;
        }

        // Email Verification
        const emailRegex = /^[a-zA-Z0-9._%+-]+@(sample|google|example|gmail|yahoo)\.(com|net|org|ph)$/i;
        if (!emailRegex.test(email)) {
            alert("Please use a valid email domain (e.g., @google.com, @gmail.com, @yahoo.com).");
            return false;
        }

        // Contact Number Verification
        const contactRegex = /^(09|\+639)\d{9}$/;
        if (!contactRegex.test(contact)) {
            alert("Please enter a valid contact number (e.g., 09123456789 or +639123456789).");
            return false;
        }

        // Pickup Date Logic (1 day in the future)
        const pickupDate = new Date(pickupVal);
        if (isNaN(pickupDate.getTime())) {
            alert("Invalid pickup date format.");
            return false;
        }

        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        tomorrow.setHours(0, 0, 0, 0);

        if (pickupDate < tomorrow) {
            alert("Pickup must be at least 1 day in the future.");
            return false;
        }

        // Return Time Logic
        const returnDate = new Date(returnVal);
        if (isNaN(returnDate.getTime())) {
            alert("Invalid return date format.");
            return false;
        }

        const diffInHours = (returnDate - pickupDate) / (1000 * 60 * 60);

        if (diffInHours < 8) {
            alert("Return time must be at least 8 hours after pickup.");
            return false;
        }

        // All validations passed - proceed with submission
        const submitBtn = document.querySelector('#submit-form button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;

        const formData = new FormData(this);

        fetch("submit_form.php", {
            method: "POST",
            body: formData
        })
        .then(res => {
            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }
            return res.json();
        })
        .then(data => {
            if(data.success) {
                alert("✅ Request Submitted Successfully!");
                document.getElementById("submit-form").reset();
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(err => {
            console.error("Submission error:", err);
            alert("Error submitting form: " + err.message);
        })
        .finally(() => {
            const submitBtn = document.querySelector('#submit-form button[type="submit"]');
            if (submitBtn) submitBtn.disabled = false;
        });

        return false;

    } catch (error) {
        console.error("Unexpected form error:", error);
        alert("An unexpected error occurred. Please try again.");
        return false;
    }
}, true);