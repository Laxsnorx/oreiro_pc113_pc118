$(document).ready(function () {
    if (localStorage.getItem("token")) {
    window.location.href = "dashboard.php";
    return;
    }


    $("#togglePassword").on("click", function () {
    const passwordInput = $("#password");
    const type =
        passwordInput.attr("type") === "password" ? "text" : "password";
    passwordInput.attr("type", type);
    });

    $("#loginForm").on("submit", function (e) {
    e.preventDefault();

    const email = $("#email").val().trim();
    const password = $("#password").val().trim();
    const errorMessage = $("#errorMessage");
    const successMessage = $("#successMessage");
    const loader = $("#loader");
    const loginButton = $("#loginButton");


    errorMessage.hide().text("");
    successMessage.hide().text("");

    if (!email || !password) {
        errorMessage.text("Both fields are required.").fadeIn();
        return;
    }

    loader.show();
    loginButton.prop("disabled", true);


    $.ajax({
        url: "http://127.0.0.1:8000/api/login",
        method: "POST",
        contentType: "application/json",
        headers: {
        Accept: "application/json",
        },
        data: JSON.stringify({ email, password }),
        success: function (data) {

        localStorage.setItem("token", data.token);
        successMessage.text("Logged in successfully!").fadeIn();

        setTimeout(() => {
            window.location.href = "dashboard.php";
        }, 1000);
        },
        error: function (xhr) {
        const err =
            xhr.responseJSON?.message || "Login failed. Please try again.";
        errorMessage.text(err).fadeIn();
        },
        complete: function () {
        loader.hide();
        loginButton.prop("disabled", false);
        },
        });
    });
});
