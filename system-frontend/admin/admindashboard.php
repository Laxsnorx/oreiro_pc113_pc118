<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet" />
  <style>
    /* --- Your existing CSS untouched --- */
    body {
      background: linear-gradient(135deg, #e0e7ff, #f5f7ff);
      font-family: 'Open Sans', sans-serif;
      margin: 0;
      padding: 0;
      color: #2c3e50;
    }

    /* Sidebar / Navbar */
    .navbar {
      position: fixed;
      top: 1rem;
      left: 1rem;
      background: #fff;
      border-radius: 15px;
      padding: 1.5rem 1rem;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.07);
      height: calc(100vh - 2rem);
      width: 270px;
      overflow-y: auto;
      transition: width 0.3s ease;
    }
    .navbar::-webkit-scrollbar {
      width: 6px;
    }
    .navbar::-webkit-scrollbar-thumb {
      background-color: #406ff3;
      border-radius: 10px;
    }

    /* Heading */
    h1 {
      margin-left: 290px;
      padding: 25px 20px 20px;
      font-size: 42px;
      font-weight: 800;
      color: #406ff3;
      text-shadow: 1px 1px 4px rgba(64, 111, 243, 0.4);
    }

    /* Dashboard container */
    .dashboard {
      margin-left: 290px;
      padding: 0 30px 50px 30px;
      max-width: 1200px;
    }

    /* Cards container */
    .card-container {
      display: flex;
      gap: 25px;
      flex-wrap: wrap;
      margin-bottom: 50px;
      justify-content: space-between;
    }

    /* Individual card */
    .card {
      background: linear-gradient(145deg, #f9fbff, #dbe6ff);
      border-radius: 15px;
      box-shadow: 0 10px 20px rgba(64, 111, 243, 0.2);
      padding: 25px 30px;
      flex: 1 1 250px;
      min-width: 280px;
      cursor: pointer;
      transition: transform 0.25s ease, box-shadow 0.25s ease;
      position: relative;
      overflow: hidden;
    }
    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 16px 30px rgba(64, 111, 243, 0.35);
    }
    .card::before {
      content: '';
      position: absolute;
      top: -40px;
      right: -40px;
      width: 120px;
      height: 120px;
      background: rgba(64, 111, 243, 0.15);
      border-radius: 50%;
      pointer-events: none;
      transition: background 0.3s ease;
    }
    .card:hover::before {
      background: rgba(64, 111, 243, 0.3);
    }

    .card h3 {
      margin: 0 0 18px 0;
      color: #2c3e50;
      font-weight: 700;
      letter-spacing: 0.03em;
    }

    .card .number {
      font-size: 3.5rem;
      font-weight: 900;
      color: #406ff3;
      letter-spacing: 0.05em;
      user-select: none;
    }

    /* Activity list */
    .activity-list {
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 10px 20px rgba(64, 111, 243, 0.15);
      padding: 30px 40px;
      max-width: 900px;
      margin: 0 auto;
    }

    .activity-list h3 {
      color: #406ff3;
      margin-bottom: 25px;
      font-weight: 800;
      font-size: 1.8rem;
      border-bottom: 3px solid #406ff3;
      padding-bottom: 8px;
      letter-spacing: 0.05em;
    }

    .activity-list ul {
      list-style-type: none;
      padding-left: 0;
      margin: 0;
    }

    .activity-list li {
      padding: 15px 0;
      border-bottom: 1px solid #e0e7ff;
      color: #34495e;
      font-size: 1.1rem;
      display: flex;
      align-items: center;
      gap: 12px;
      transition: color 0.3s ease;
    }

    .activity-list li:last-child {
      border-bottom: none;
    }

    .activity-list li:hover {
      color: #406ff3;
      font-weight: 600;
    }

    /* Checkmark icon for list items */
    .activity-list li::before {
      content: '✔️';
      font-size: 1.2rem;
      color: #406ff3;
      flex-shrink: 0;
    }

    /* Responsive tweaks */
    @media (max-width: 1024px) {
      h1 {
        margin-left: 20px;
        text-align: center;
      }
      .dashboard {
        margin-left: 0;
        padding: 0 15px 40px;
      }
      .navbar {
        position: relative;
        width: 100%;
        height: auto;
        box-shadow: none;
        border-radius: 0;
        padding: 15px 20px;
        margin-bottom: 20px;
      }
      .card-container {
        justify-content: center;
      }
    }

  </style>
</head>
<body>

  <?php include 'sidebar.php'; ?>

  <h1>Admin Dashboard</h1>

  <main class="dashboard">
    <section class="card-container">
      <div class="card" tabindex="0" aria-label="Total Users">
        <h3>Total Users</h3>
        <div id="total-users" class="number"></div>
      </div>
      <div class="card" tabindex="0" aria-label="Active Instructor">
        <h3>Active Instructor</h3>
        <div id="total-instructors" class="number"></div>
      </div>
      <div class="card" tabindex="0" aria-label="Student Enrolled">
        <h3>Student Enrolled</h3>
        <div id="total-students" class="number"></div>
      </div>
    </section>

    <section class="activity-list" aria-label="Recent Activities">
      <h3>Recent Activities</h3>
      <ul>
        <li id="latest-student">New student added: /li>
        <li>Grade records updated</li>
      </ul>
    </section>
  </main>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
  const token = localStorage.getItem("token");
  if (!token) {
    window.location.href = "login.php";
    return;
  }

  async function fetchCount(url) {
    try {
      const res = await fetch(url, {
        headers: { Authorization: `Bearer ${token}` },
      });
      if (!res.ok) throw new Error("Failed to fetch " + url);
      const data = await res.json();
      return Array.isArray(data) ? data.length : 0;
    } catch (err) {
      console.error(err);
      return 0;
    }
  }

  async function fetchLatestStudent() {
    try {
      const res = await fetch("http://127.0.0.1:8000/api/students?sort=latest", {
        headers: { Authorization: `Bearer ${token}` },
      });
      if (!res.ok) throw new Error("Failed to fetch latest student");
      const data = await res.json();
      // Assuming data is an array sorted by latest student first
      return Array.isArray(data) && data.length > 0 ? data[0].name : null;
    } catch (err) {
      console.error(err);
      return null;
    }
  }

  async function updateCounts() {
    const [users, instructors, students, latestStudentName] = await Promise.all([
      fetchCount("http://127.0.0.1:8000/api/user"),
      fetchCount("http://127.0.0.1:8000/api/instructors"),
      fetchCount("http://127.0.0.1:8000/api/students"),
      fetchLatestStudent()
    ]);

    const totalUsersEl = document.getElementById("total-users");
    const totalInstructorsEl = document.getElementById("total-instructors");
    const totalStudentsEl = document.getElementById("total-students");

    totalUsersEl.textContent = users.toLocaleString();
    totalInstructorsEl.textContent = instructors.toLocaleString();
    totalStudentsEl.textContent = students.toLocaleString();

    totalUsersEl.parentElement.setAttribute('aria-label', `Total Users: ${users}`);
    totalInstructorsEl.parentElement.setAttribute('aria-label', `Active Instructor: ${instructors}`);
    totalStudentsEl.parentElement.setAttribute('aria-label', `Student Enrolled: ${students}`);

    // Update latest student activity line
    const latestStudentLi = document.getElementById("latest-student");
    if (latestStudentName) {
      latestStudentLi.textContent = `New student added: ${latestStudentName}`;
    } else {
      latestStudentLi.textContent = `New student added: None`;
    }
  }

  updateCounts();
});

  </script>

</body>
</html>
