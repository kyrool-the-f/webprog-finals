document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("registerForm");

    if (!form) return;

    form.addEventListener("submit", function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch("user_register.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
        if(data.success){
            closeRegisterModal();
            alert("Register successful!");
            window.location.href = "main.php";
        } else {
            alert("Error: " + data.message);
        }
        })
        .catch(err => console.error(err));
    });
});