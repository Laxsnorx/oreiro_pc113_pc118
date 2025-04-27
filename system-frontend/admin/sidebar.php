<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tailwind Sidebar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#DCE2F0] min-h-screen flex justify-center items-start">

    <!-- Top Banner for Grading System -->
    <div class="w-full bg-purple-600 text-white py-4 text-center font-semibold text-3xl">
        Student Grading System
    </div>

    <!-- Sidebar -->
    <div class="fixed top-0 left-0 h-full w-64 bg-gray-100 shadow-lg flex flex-col pt-8">
        <ul class="space-y-3 px-4">
            <li>
                <a href="admindashboard.php" class="block text-center py-3 rounded-lg text-[#50586C] text-lg font-semibold bg-[#a268ff] text-white hover:bg-[#7e54ff] transition-all duration-300 relative">
                    <span class="absolute left-4 text-2xl text-[#a268ff]">•</span>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="users.php" class="block text-center py-3 rounded-lg text-[#50586C] text-lg font-semibold hover:bg-[#7e54ff] hover:text-white transition-all duration-300 relative">
                    <span class="absolute left-4 text-2xl text-[#a268ff]">•</span>
                    Manage Users
                </a>
            </li>
            <li>
                <a href="system.php" class="block text-center py-3 rounded-lg text-[#50586C] text-lg font-semibold hover:bg-[#7e54ff] hover:text-white transition-all duration-300 relative">
                    <span class="absolute left-4 text-2xl text-[#a268ff]">•</span>
                    System Settings
                </a>
            </li>
            <li>
                <a href="settings.php" class="block text-center py-3 rounded-lg text-[#50586C] text-lg font-semibold hover:bg-[#7e54ff] hover:text-white transition-all duration-300 relative">
                    <span class="absolute left-4 text-2xl text-[#a268ff]">•</span>
                    General Settings
                </a>
            </li>
            <li>
                <a href="reports.php" class="block text-center py-3 rounded-lg text-[#50586C] text-lg font-semibold hover:bg-[#7e54ff] hover:text-white transition-all duration-300 relative">
                    <span class="absolute left-4 text-2xl text-[#a268ff]">•</span>
                    Generate Reports
                </a>
            </li>
            <li>
                <a href="logout.php" class="block text-center py-3 rounded-lg text-[#50586C] text-lg font-semibold hover:bg-red-500 hover:text-white transition-all duration-300 relative">
                    <span class="absolute left-4 text-2xl text-red-400">•</span>
                    Logout
                </a>
            </li>
        </ul>
    </div>

</body>
</html>
