
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Grades</title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700,800&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      background: #eaeef6;
      font-family: 'Open Sans', sans-serif;
      margin: 0;
      padding: 0;
    }
    h1 {
      margin-left: 290px;
      padding: 25px 20px 20px;
      font-size: 42px;
      font-weight: 800;
      color: #406ff3;
      text-shadow: 1px 1px 4px rgba(64, 111, 243, 0.4);
    }
    table {
      width: calc(100% - 290px);
      margin-left: 290px;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    table, th, td {
      border: 1px solid #ddd;
    }
    th, td {
      padding: 8px;
      text-align: left;
    }
    th {
      background-color: #406ff3;
      color: white;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    .grade-box {
      display: inline-block;
      padding: 5px 10px;
      border-radius: 4px;
      font-weight: bold;
      color: white;
    }
    .no-grade {
      background-color: #e74c3c;
    }
    .has-grade {
      background-color: #27ae60;
    }
  </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<h1>My Grades</h1>

<table>
  <thead>
    <tr>
      <th>#</th>
      <th>Code</th>
      <th>Description</th>
      <th>Instructor</th>
      <th>Midterm Grade</th>
      <th>Final Grade</th>
      <th>Re-exam</th>
    </tr>
  </thead>
  <tbody id="grades-table-body">
    <tr><td colspan="7">Loading...</td></tr>
  </tbody>
</table>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const token = localStorage.getItem("token");
  const studentId = <?= json_encode($studentId) ?>;

  if (!token) {
    Swal.fire("Unauthorized", "Please login to view this page.", "error").then(() => {
      window.location.href = "login.php";
    });
    return;
  }

  async function fetchGrades() {
    try {
      const [gradesRes, subjectsRes] = await Promise.all([
        fetch(`http://127.0.0.1:8000/api/students/${studentId}/grades`, {
          headers: { Authorization: `Bearer ${token}` }
        }),
        fetch('http://127.0.0.1:8000/api/subjects', {
          headers: { Authorization: `Bearer ${token}` }
        })
      ]);

      if (!gradesRes.ok || !subjectsRes.ok) {
        throw new Error("Failed to fetch data.");
      }

      const gradesData = await gradesRes.json();
      const subjectsData = await subjectsRes.json();

      const subjectsMap = {};
      subjectsData.forEach(subject => {
        subjectsMap[subject.id] = subject;
      });

      const tbody = document.getElementById("grades-table-body");
      tbody.innerHTML = "";

      gradesData.forEach((grade, index) => {
        const subject = subjectsMap[grade.subject_id] || {};
        const instructor = subject.instructor?.name || 'N/A';

        const row = `
          <tr>
            <td>${index + 1}</td>
            <td>${subject.code || '—'}</td>
            <td>${subject.name || subject.description || '—'}</td>
            <td>${instructor}</td>
            <td><span class="grade-box ${grade.midterm !== null ? 'has-grade' : 'no-grade'}">${grade.midterm ?? 'No Grade Yet'}</span></td>
            <td><span class="grade-box ${grade.final !== null ? 'has-grade' : 'no-grade'}">${grade.final ?? 'No Grade Yet'}</span></td>
            <td><span class="grade-box ${grade.re_exam !== null ? 'has-grade' : 'no-grade'}">${grade.re_exam ?? 'No Grade Yet'}</span></td>
          </tr>
        `;
        tbody.insertAdjacentHTML('beforeend', row);
      });

      if (gradesData.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7">No grades available.</td></tr>';
      }

    } catch (error) {
      console.error(error);
      document.getElementById("grades-table-body").innerHTML =
        '<tr><td colspan="7">⚠️ Failed to load grades. Please try again later.</td></tr>';
    }
  }

  fetchGrades();
});
</script>

</body>
</html>
