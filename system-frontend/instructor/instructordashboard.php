<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Instructor Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet" />
  <style>
    body {
      background: linear-gradient(135deg, #e0e7ff, #f5f7ff);
      font-family: 'Open Sans', sans-serif;
      margin: 0;
      padding: 0;
      color: #2c3e50;
    }

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
    }

    .navbar::-webkit-scrollbar {
      width: 6px;
    }

    .navbar::-webkit-scrollbar-thumb {
      background-color: #406ff3;
      border-radius: 10px;
    }

    h1 {
      margin-left: 290px;
      padding: 25px 20px 20px;
      font-size: 42px;
      font-weight: 800;
      color: #406ff3;
      text-shadow: 1px 1px 4px rgba(64, 111, 243, 0.4);
    }

    .dashboard {
      margin-left: 290px;
      padding: 0 30px 50px 30px;
      max-width: 1200px;
    }

    .card-container {
      display: flex;
      gap: 25px;
      flex-wrap: wrap;
      margin-bottom: 50px;
      justify-content: start;
    }

    .card {
      background: linear-gradient(145deg, #f9fbff, #dbe6ff);
      border-radius: 15px;
      box-shadow: 0 10px 20px rgba(64, 111, 243, 0.2);
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
    }

    .card:hover::before {
      background: rgba(64, 111, 243, 0.3);
    }

    .card h3 {
      margin: 0 0 18px 0;
      color: #2c3e50;
      font-weight: 700;
    }

    .card .number {
      font-size: 3.5rem;
      font-weight: 900;
      color: #406ff3;
      user-select: none;
    }

    /* Small Card Override */
    .card.small-card {
      flex: 0 0 200px;
      max-width: 500px;
      padding: 20px 20px;
    }

    .card.small-card .number {
      font-size: 2.5rem;
    }

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
    }

    .activity-list ul {
      list-style: none;
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
    }

    .activity-list li::before {
      content: '📝';
      color: #406ff3;
      font-size: 1.2rem;
    }

    .activity-list li:last-child {
      border-bottom: none;
    }

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

  <h1>Instructor Dashboard</h1>

  <main class="dashboard">
    <section class="card-container">
      <div class="card small-card" tabindex="0" aria-label="Active Students">
        <h3>Active Students</h3>
        <div id="total-students" class="number">0</div>
      </div>
    </section>

    <section class="activity-list" aria-label="Admin Reports">
      <h3>Admin Reports</h3>
      <ul id="report-list">
        <li>Loading reports...</li>
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

      async function fetchActiveStudents() {
        try {
          const res = await fetch("http://127.0.0.1:8000/api/students?status=active", {
            headers: { Authorization: `Bearer ${token}` },
          });
          if (!res.ok) throw new Error("Failed to fetch active students");
          return await res.json();
        } catch (err) {
          console.error(err);
          return [];
        }
      }

      async function fetchAdminReports() {
        try {
          const res = await fetch("http://127.0.0.1:8000/api/admin-reports", {
            headers: { Authorization: `Bearer ${token}` },
          });
          if (!res.ok) throw new Error("Failed to fetch admin reports");
          return await res.json();
        } catch (err) {
          console.error(err);
          return [];
        }
      }

      async function updateDashboard() {
        const [students, reports] = await Promise.all([
          fetchActiveStudents(),
          fetchAdminReports(),
        ]);

        const totalStudentsEl = document.getElementById("total-students");
        totalStudentsEl.textContent = students.length.toLocaleString();

        const reportList = document.getElementById("report-list");
        reportList.innerHTML = "";

        if (reports.length === 0) {
          reportList.innerHTML = "<li>No reports available.</li>";
        } else {
          reports.forEach(report => {
            const li = document.createElement("li");
            li.textContent = report.title || "Untitled Report";
            reportList.appendChild(li);
          });
        }
      }

      updateDashboard();
    });
  </script>

</body>
</html>
