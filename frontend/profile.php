<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile Update</title>
    <link rel="stylesheet" href="styles/profile.css?v=1.0.2" />
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
document.addEventListener("DOMContentLoaded", function () {
    const token = localStorage.getItem("token");

    if (!token) {
        window.location.href = "login.php";
        return;
    }


    fetch("http://127.0.0.1:8000/api/profile", {
        method: "GET",
        headers: {
            Authorization: `Bearer ${token}`,
            Accept: "application/json",
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Failed to load profile");
        }
        return response.json();
    })
    .then(user => {
        document.getElementById("userId").value = user.id;
        document.getElementById("name").value = user.name;
        document.getElementById("email").value = user.email;
        document.getElementById("role").value = user.role;

        const welcomeMsg = document.getElementById("welcome-msg");
        if (welcomeMsg) {
            welcomeMsg.textContent = `Logged in as: ${user.name} (${user.email})`;
        }
    })
    .catch(error => {
        console.error("Failed to load profile:", error);
        alert("Error loading profile. Please login again.");
        localStorage.removeItem("token");
        window.location.href = "login.php";
    });


    const profileForm = document.getElementById("profileForm");

    profileForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = {
            name: document.getElementById("name").value,
            email: document.getElementById("email").value,
            password: document.getElementById("password").value,
            role: document.getElementById("role").value,
        };


        if (!formData.password) {
            delete formData.password;
        }

        fetch("http://127.0.0.1:8000/api/profile/update", {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`,
                Accept: "application/json",
            },
            body: JSON.stringify(formData),
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(() => {
            alert("Profile updated successfully!");
            document.getElementById("password").value = ""; 
        })
        .catch(error => {
            console.error("Update error:", error);

            let message = "Failed to update profile.";
            if (error.message) {
                message += " " + error.message;
            }

            alert(message);
        });
    });
});

</script>

</body>
</html>
