document.getElementById("togglePassword").addEventListener("click", () => {
    let passwordInput = document.getElementById("password");
    passwordInput.type = passwordInput.type === "password" ? "text" : "password";
    });

    if (localStorage.getItem("token")) {
    window.location.href = "dashboard.php";
    }

document
    .getElementById("loginForm")
    .addEventListener("submit", async function (event) {
    event.preventDefault();

    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value.trim();
    let errorMessage = document.getElementById("errorMessage");
    let loader = document.getElementById("loader");
    let loginButton = document.getElementById("loginButton");

    if (!email || !password) {
        errorMessage.textContent = "Both fields are required.";
        errorMessage.classList.remove("hidden");
        return;
    }

    errorMessage.classList.add("hidden");
    loader.style.display = "block"; // Show loader
    loginButton.disabled = true; // Disable button

    try {
        let response = await fetch("http://127.0.0.1:8000/api/login", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
        },
        body: JSON.stringify({ email, password }),
        });

        let data = await response.json();

        if (!response.ok) {
        throw new Error(data.message || "Login failed. Please try again.");
        }

        localStorage.setItem("token", data.token);
        window.location.href = "dashboard.php";
    } catch (error) {
        console.error("Error:", error);
        errorMessage.textContent =
        error.message || "An unexpected error occurred.";
        errorMessage.classList.remove("hidden");
    }
    });
