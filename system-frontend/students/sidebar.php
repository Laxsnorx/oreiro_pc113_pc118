<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
 /* Reset and Body Styling */
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: 'Segoe UI', sans-serif;
    background-color: #DCE2F0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

/* Sidebar Styling */
.sidebar {
    width: 250px;
    background-color: #f0f0f0; /* Light gray background */
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    padding-top: 20px;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
}

.sidebar-menu {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.sidebar-menu li {
    text-align: center;
    margin-bottom: 10px;
}

.sidebar-menu li a {
    display: block;
    padding: 15px;
    color: #50586C; /* Dark text for contrast */
    text-decoration: none;
    font-size: 1.1rem;
    border-radius: 5px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.sidebar-menu li a:hover {
    background-color: #7e54ff;
    color: white;
}

.sidebar-menu li a.active {
    background-color: #a268ff;
    color: white;
}

/* Optional: For a cleaner look, add icons to the sidebar (optional) */
.sidebar-menu li a::before {
    content: "\2022"; /* Adds a bullet point */
    margin-right: 10px;
    font-size: 1.5rem;
    color: #a268ff;
}

/* For small screen responsiveness */
@media (max-width: 480px) {
    .sidebar {
        width: 200px;
    }

    .sidebar-menu li a {
        font-size: 1rem;
    }
}


    </style>
</head>
<body>
    
    <div class="sidebar">
    <ul class="sidebar-menu">
        <li><a href="admindashboard.php" class="active">Dashboard</a></li>
        <li><a href="profile.php">Profile</a></li>
        <li><a href="grades.php">View grades</a></li>
        <li><a href="reports.php">View Reports</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>
</body>
</html>