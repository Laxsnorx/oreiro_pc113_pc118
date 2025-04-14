<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile Update</title>
    <link rel="stylesheet" href="styles/profile.css?v=1.0.1" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php include 'partials/sidebar.php'; ?>

    <div class="form-container">
        <h2>Update Profile</h2>
        <p id="welcome-msg" style="font-style: italic; color: #555;"></p>

        <form id="profileForm">
            <input type="hidden" name="id" id="userId">

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Enter full name" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter email" required>
            </div>

            <div class="form-group">
                <label for="password">Password <small>(Leave blank to keep current password)</small></label>
                <input type="password" id="password" name="password" placeholder="New password">
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>

            <button type="submit">Update Profile</button>
        </form>
    </div>

<script>
$(document).ready(function () {
    const token = localStorage.getItem("token");

    if (!token) {
        window.location.href = "login.php";
        return;
    }

    // Fetch user profile
    $.ajax({
        url: "http://127.0.0.1:8000/api/profile", // Adjust if needed
        method: "GET",
        headers: {
            Authorization: `Bearer ${token}`,
            Accept: "application/json",
        },
        success: function (user) {
            $("#userId").val(user.id);
            $("#name").val(user.name);
            $("#email").val(user.email);
            $("#role").val(user.role);

            // Optional: show welcome message
            $("#welcome-msg").text(`Logged in as: ${user.name} (${user.email})`);
        },
        error: function (xhr) {
            console.error("Failed to load profile:", xhr.responseText);
            alert("Error loading profile. Please login again.");
            localStorage.removeItem("token");
            window.location.href = "login.php";
        },
    });

    // Update form handler
    $("#profileForm").submit(function (e) {
        e.preventDefault();

        const formData = {
            name: $("#name").val(),
            email: $("#email").val(),
            password: $("#password").val(),
            role: $("#role").val(),
        };

        // Remove empty password from submission
        if (!formData.password) {
            delete formData.password;
        }

        $.ajax({
            url: "http://127.0.0.1:8000/api/profile/update",
            method: "PUT",
            contentType: "application/json",
            data: JSON.stringify(formData),
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: "application/json",
            },
            success: function () {
                alert("Profile updated successfully!");
                $("#password").val(""); // Clear password field
            },
            error: function (xhr) {
                console.error("Update error:", xhr);

                let message = "Failed to update profile.";
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.message) {
                        message += " " + response.message;
                    }
                } catch (e) {
                    message += " " + xhr.responseText;
                }

                alert(message);
            },
        });
    });
});
</script>

</body>
</html>
