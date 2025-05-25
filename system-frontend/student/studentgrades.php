<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>My Grades</title>
  <style>
    body {
      background: #eaeef6;
      font-family: 'Open Sans', sans-serif;
    }

    .content-container {
      margin-left: 290px;
      padding: 20px;
    }

    h1 {
      font-size: 42px;
      font-weight: 800;
      color: #406ff3;
      margin: 0 0 10px 0;
    }

    .student-info {
      font-size: 18px;
      font-weight: 600;
      color: #333;
      margin-bottom: 10px;
    }

    .semester-info {
      font-size: 16px;
      font-weight: 500;
      color: #555;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #406ff3;
      color: white;
    }

    .grade-box {
      padding: 5px 10px;
      border-radius: 4px;
      font-weight: bold;
      color: white;
    }

    .no-grade { background-color: #e74c3c; }
    .has-grade { background-color: #27ae60; }
    .grade-fail { background-color: #e74c3c; }

    tfoot td {
      font-weight: bold;
      background-color: #f0f3f9;
    }

    .remark-box {
      display: inline-block;
      padding: 2px 8px;
      border-radius: 4px;
      font-size: 0.75rem;
      font-weight: 600;
      color: white;
      text-align: center;
      min-width: 50px;
    }

    .remark-passed {
      background-color: #27ae60;
    }

    .remark-failed {
      background-color: #e74c3c;
    }

    @media print {
      #sidebar, .sidebar, #print-btn {
        display: none !important;
      }

      .content-container {
        margin-left: 0 !important;
        padding: 0 !important;
        width: 100% !important;
      }

      h1 {
        color: black;
        font-size: 28pt;
        text-align: center;
        margin-bottom: 10px;
      }

      .student-info, .semester-info {
        font-size: 16pt;
        color: black;
        text-align: center;
        margin-bottom: 10px;
      }

      table {
        width: 100% !important;
        background: none !important;
        border: 1px solid #000;
        page-break-inside: avoid;
      }

      th, td {
        border: 1px solid #000 !important;
        color: black !important;
        background: white !important;
      }

      th {
        background-color: #ccc !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }

      .grade-box, .remark-box {
        color: black !important;
        background: none !important;
        font-weight: normal !important;
        padding: 0 !important;
        min-width: auto !important;
      }

      tfoot td {
        background-color: #ccc !important;
      }
    }

    #print-btn {
      margin-bottom: 10px;
      padding: 8px 20px;
      background-color: #406ff3;
      color: white;
      border: none;
      border-radius: 4px;
      font-weight: 600;
      cursor: pointer;
    }

    #print-btn:hover {
      background-color: #3055d6;
    }
  </style>
</head>
<body>
<div id="sidebar">
  <?php include 'sidebar.php'; ?>
</div>

<div class="content-container">
  <h1>My Grades</h1>
  <div class="student-info" id="student-info"></div>
  <div class="semester-info" id="semester-info"></div>

  <button id="print-btn" onclick="window.print()">Print Grades</button>

  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Code</th>
        <th>Description</th>
        <th>Instructor</th>
        <th>Midterm Grade</th>
        <th>Final Grade</th>
        <th>Remarks</th>
      </tr>
    </thead>
    <tbody id="grades-table-body">
      <tr><td colspan="7">Loading...</td></tr>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="6" style="text-align:right">Total Units:</td>
        <td id="total-units">0</td>
      </tr>
      <tr>
        <td colspan="6" style="text-align:right">GWA:</td>
        <td id="gwa">N/A</td>
      </tr>
    </tfoot>
  </table>
</div>

<script>
  const currentUser = JSON.parse(localStorage.getItem('user'));
  const token = localStorage.getItem('token');

  if (!currentUser || !token || currentUser.role !== 'student') {
    alert("Unauthorized. Please login again.");
    window.location.href = "../index.html";
  }

  document.getElementById('student-info').textContent = `${currentUser.name} — ${currentUser.course || 'No Course Info'}`;

  async function fetchGrades() {
    try {
      const studentId = currentUser.id;

      const [gradesRes, subjectsRes, instructorsRes] = await Promise.all([
        fetch(`http://127.0.0.1:8000/api/students/${studentId}/grades`, {
          headers: { Authorization: `Bearer ${token}` }
        }),
        fetch('http://127.0.0.1:8000/api/subjects', {
          headers: { Authorization: `Bearer ${token}` }
        }),
        fetch('http://127.0.0.1:8000/api/instructors', {
          headers: { Authorization: `Bearer ${token}` }
        }),
      ]);

      if (!gradesRes.ok || !subjectsRes.ok || !instructorsRes.ok) {
        throw new Error("Failed to fetch data.");
      }

      const gradesData = await gradesRes.json();
      const subjectsData = await subjectsRes.json();
      const instructorsData = await instructorsRes.json();

      const grades = Array.isArray(gradesData) ? gradesData : (gradesData.data || []);
      const subjects = Array.isArray(subjectsData) ? subjectsData : (subjectsData.data || []);
      const instructors = Array.isArray(instructorsData) ? instructorsData : (instructorsData.data || []);

      const subjectsMap = {};
      subjects.forEach(subject => {
        subjectsMap[subject.id] = subject;
      });

      const instructorsMap = {};
      instructors.forEach(instructor => {
        instructorsMap[instructor.id] = instructor.name;
      });

      const tbody = document.getElementById('grades-table-body');
      tbody.innerHTML = '';

      if (grades.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7">No grades available.</td></tr>`;
        return;
      }

      let totalUnits = 0;
      let totalGradePoints = 0;
      let gradedSubjectsCount = 0;
      let semesterText = '';

      grades.forEach((grade, index) => {
        const subject = subjectsMap[grade.subject_id] || {};
        const instructorName = instructorsMap[subject.instructor_id] || 'N/A';

        totalUnits += subject.units ? Number(subject.units) : 0;

        if (!semesterText && subject.semester) {
          semesterText = subject.semester === "1st" ? "First Semester" : subject.semester;
        }

        let remarkHTML = '<span class="remark-box">No Grade</span>';
        if (grade.final_grade !== null) {
          if (grade.final_grade <= 3.0) {
            remarkHTML = '<span class="remark-box remark-passed">Passed</span>';
          } else {
            remarkHTML = '<span class="remark-box remark-failed">Failed</span>';
          }
          totalGradePoints += parseFloat(grade.final_grade);
          gradedSubjectsCount++;
        }

        const midtermClass = grade.midterm_grade > 3.0 ? 'grade-fail' : 'has-grade';
        const finalClass = grade.final_grade > 3.0 ? 'grade-fail' : 'has-grade';

        tbody.innerHTML += `
          <tr>
            <td>${index + 1}</td>
            <td>${subject.code || '—'}</td>
            <td>${subject.description || '—'}</td>
            <td>${instructorName}</td>
            <td><span class="grade-box ${grade.midterm_grade !== null ? midtermClass : 'no-grade'}">
              ${grade.midterm_grade !== null ? grade.midterm_grade : 'No Grade Yet'}</span></td>
            <td><span class="grade-box ${grade.final_grade !== null ? finalClass : 'no-grade'}">
              ${grade.final_grade !== null ? grade.final_grade : 'No Grade Yet'}</span></td>
            <td>${remarkHTML}</td>
          </tr>
        `;
      });

      const gwa = gradedSubjectsCount > 0 ? (totalGradePoints / gradedSubjectsCount).toFixed(2) : 'N/A';
      document.getElementById('total-units').textContent = totalUnits;
      document.getElementById('gwa').textContent = gwa;
      document.getElementById('semester-info').textContent = semesterText;

    } catch (error) {
      console.error("Error fetching grades:", error);
      document.getElementById("grades-table-body").innerHTML =
        `<tr><td colspan="7">❌ Failed to load grades.</td></tr>`;
    }
  }

  fetchGrades();
</script>
</body>
</html>
