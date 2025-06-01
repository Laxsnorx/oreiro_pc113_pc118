<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student List</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/qrious/dist/qrious.min.js"></script>

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <style>
    body {
    background: #eaeef6;
    font-family: 'Open Sans', sans-serif;
    margin: 0;
    padding: 0;
  }

  .navbar {
    position: fixed;
    top: 1rem;
    left: 1rem;
    background: #fff;
    border-radius: 15px;
    padding: 1rem 0;
    box-shadow: 0 0 40px rgba(0, 0, 0, 0.05);
    height: calc(100vh - 2rem);
    width: 270px;
    overflow: hidden;
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

  /* Button styling */
  button {
    background-color: #4A90E2;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  button:hover {
    background-color: #357ABD;
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
  }

  .btn-delete {
    background-color: #e74c3c;
    margin-left: 5px;
  }

  .btn-delete:hover {
    background-color: #c0392b;
  }

  /* Form container styles */
  .swal2-input {
    background-color: #fff;
    color: #333;
    border: 1px solid #ddd;
    padding: 10px 15px;
    border-radius: 8px;
    font-size: 16px;
    margin: 8px 0;
    width: 100%; /* Full width for consistency */
    transition: all 0.3s ease;
  }

  /* Dropdown (Select) Styling to match form container */
  select {
    background-color: #fff;
    color: #333;
    border: 1px solid #ddd;
    padding: 10px 15px;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    width: 100%; /* Match the form container input width */
    transition: all 0.3s ease;
  }

  select:hover {
    border-color: #4A90E2;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  select:focus {
    outline: none;
    border-color: #4A90E2;
  }

  .action-button-container {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 10px;
  }

  #paginationControls button {
    background-color: #406ff3;
    color: white;
    border: none;
    padding: 8px 14px;
    margin: 0 3px;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  #paginationControls button:hover {
    background-color: #357ABD;
  }

  #paginationControls button.active {
    background-color: #2c5fe0;
    font-weight: bold;
  }
  .custom-swal-popup {
    font-size: 13px;
    padding: 15px;
  }
  </style>
</head>
<body>
  <?php include 'sidebar.php'; ?>
  <h1>Student List</h1>


<div style="margin-left: 290px; padding: 0 20px 10px 0; display: flex; justify-content: space-between; align-items: center;">
  <input type="text" id="searchInput" class="swal2-input" style="max-width: 300px;" placeholder="Search student by name or email" oninput="filterStudents()">
</div>
<div style="margin-left: 290px; padding: 10px 20px 20px 0; display: flex; gap: 10px;">
  <button onclick="exportExcel()">Export Excel</button>
  <button onclick="triggerImport()">Import Excel</button>
  <input type="file" id="excelFileInput" accept=".xlsx,.xls" style="display:none" onchange="handleFileImport(event)">
</div>
<div class="action-button-container" style="margin-left: 290px;">
  <button onclick="openAddGradeModal()">Add Grade</button>
</div>
  <table id="studentsTable">
    <thead>
      <tr>
        <th>ID</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Course</th>
        <th>Actions</th>
        <th>QR</th>
      </tr>
    </thead>
    <tbody>
      <!-- Populated dynamically -->
    </tbody>
  </table>
  <div id="paginationControls" style="margin-left: 290px; padding: 20px; text-align: right;"></div>

<script>

let allStudents = [];
let filteredStudents = [];
let currentPage = 1;
const rowsPerPage = 8;

// Load students on page load
document.addEventListener("DOMContentLoaded", function () {
  const token = localStorage.getItem("token");

  if (!token) {
    window.location.href = "login.php";
    return;
  }

  fetchStudents();

  document.getElementById("searchInput").addEventListener("input", function () {
    const searchTerm = this.value.toLowerCase();
    filteredStudents = allStudents.filter(student =>
      student.name.toLowerCase().includes(searchTerm) ||
      student.email.toLowerCase().includes(searchTerm)
    );
    currentPage = 1;
    displayStudents();
  });
});

function fetchStudents() {
  const token = localStorage.getItem("token");

  fetch("http://127.0.0.1:8000/api/students", {
    headers: {
      Authorization: `Bearer ${token}`
    }
  })
    .then(res => res.json())
    .then(data => {
      allStudents = data;
      filteredStudents = data;
      displayStudents();
    })
    .catch(error => {
      console.error("Failed to load students:", error);
    });
}

function displayStudents() {
  const tbody = document.querySelector("#studentsTable tbody");
  tbody.innerHTML = "";

  const startIndex = (currentPage - 1) * rowsPerPage;
  const paginatedStudents = filteredStudents.slice(startIndex, startIndex + rowsPerPage);

  paginatedStudents.forEach(student => {
    const row = document.createElement("tr");
    row.innerHTML = `
  <td>${student.id}</td>
  <td>${student.name}</td>
  <td>${student.email}</td>
  <td>${student.course}</td>
  <td>
    <button onclick="viewGrades(${student.id})" style="background-color: yellow; border: none; color: black; padding: 10px 10px; cursor: pointer;">View</button>
    <button onclick="editStudent(${student.id})">Edit</button>
    <button class="btn-delete" onclick="deleteStudent(${student.id})">Delete</button>
  </td>
  <td>
    <button onclick="viewQR(${student.id})" style="background-color: lightgreen; border: none; color: black; padding: 10px 10px; cursor: pointer;">View QR</button>

  </td>
`;

    tbody.appendChild(row);
  });

  renderPaginationControls();
}

function renderPaginationControls() {
  const totalPages = Math.ceil(filteredStudents.length / rowsPerPage);
  const paginationDiv = document.getElementById("paginationControls");
  paginationDiv.innerHTML = "";

  for (let i = 1; i <= totalPages; i++) {
    const btn = document.createElement("button");
    btn.textContent = i;
    btn.className = i === currentPage ? "active" : "";

    btn.onclick = function () {
      currentPage = i;
      displayStudents();
    };

    paginationDiv.appendChild(btn);
  }
}

async function fetchStudentDetails(studentId, token) {
  try {
    // Fetch grades, subjects, and instructors in parallel
    const [gradesRes, subjectsRes, instructorsRes, studentRes] = await Promise.all([
      fetch(`http://127.0.0.1:8000/api/students/${studentId}/grades`, {
        headers: { Authorization: `Bearer ${token}` }
      }),
      fetch('http://127.0.0.1:8000/api/subjects', {
        headers: { Authorization: `Bearer ${token}` }
      }),
      fetch('http://127.0.0.1:8000/api/instructors', {
        headers: { Authorization: `Bearer ${token}` }
      }),
      fetch(`http://127.0.0.1:8000/api/students/${studentId}`, {
        headers: { Authorization: `Bearer ${token}` }
      }),
    ]);

    if (!gradesRes.ok || !subjectsRes.ok || !instructorsRes.ok || !studentRes.ok) {
      throw new Error('Failed to fetch all required data');
    }

    const grades = await gradesRes.json();
    const subjects = await subjectsRes.json();
    const instructors = await instructorsRes.json();
    const student = await studentRes.json();

    return { student, grades, subjects, instructors };
  } catch (error) {
    console.error('Error fetching student details:', error);
    throw error;
  }
}
function buildQRContent({ student, grades = [], subjects = [], instructors = [] }) {
  const baseInfo = `Name: ${student.name}
Email: ${student.email}
Course: ${student.course}`;

  const subjectsMap = {};
  subjects.forEach(subject => {
    subjectsMap[subject.id] = subject;
  });

  const instructorsMap = {};
  instructors.forEach(instructor => {
    instructorsMap[instructor.id] = instructor.name;
  });

  const gradesInfo = grades.map((grade) => {
    const subject = subjectsMap[grade.subject_id] || {};
    const instructorName = instructorsMap[subject.instructor_id] || 'N/A';
    const subjectName = subject.description || 'N/A';
    const finalGrade = grade.final_grade !== null ? grade.final_grade : 'No Grade';

    return `\n\nSubject: ${subjectName}
Instructor: ${instructorName}
Grade: ${finalGrade}`;
  }).join('');

  return `${baseInfo}${gradesInfo}`;
}

async function viewQR(studentId) {
  const token = localStorage.getItem("token");

  try {
    const response = await fetch(`http://localhost:8000/api/grades/student/${studentId}`, {
      headers: {
        "Authorization": `Bearer ${token}`
      }
    });

    if (!response.ok) {
      throw new Error("Failed to fetch student data");
    }

    const { student, qr_code } = await response.json();

    // Just display the QR code image returned from backend
    Swal.fire({
      title: `<span style="font-size: 20px;">${student.name}</span>`,
      html: `
        <div style="text-align: center;">
          <img src="${qr_code}" 
               alt="QR Code"
               style="border: 8px solid #406ff3; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); margin-bottom: 10px; width: 300px; height: 300px;">
        </div>
      `,
      showCloseButton: true,
      showConfirmButton: false,
      width: 400,
      customClass: {
        popup: 'custom-swal-popup'
      }
    });

  } catch (error) {
    console.error('QR Fetch Error:', error);
    Swal.fire("Error", "Failed to load QR code details.", "error");
  }
}





function viewGrades(studentId) { 
  const token = localStorage.getItem("token");

  fetch(`http://127.0.0.1:8000/api/students/${studentId}`, {
    headers: {
      Authorization: `Bearer ${token}`
    }
  })
  .then(res => res.json())
  .then(student => {
    fetch(`http://127.0.0.1:8000/api/students/${studentId}/grades`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    .then(res => {
      if (!res.ok) throw new Error("Grades not found");
      return res.json();
    })
    .then(grades => {
      const limitedGrades = grades.slice(0, 9);
      let leftColumn = "", rightColumn = "";
      let totalUnits = 0;
      let overallStatus = 'Passed';
      let totalGradePoints = 0;

      limitedGrades.forEach((grade, index) => {
        const midterm = parseFloat(grade.midterm_grade) || 0;
        const final = parseFloat(grade.final_grade) || 0;
        const avg = (midterm + final) / 2;
        const status = avg >= 1.00 && avg <= 3.00 ? 'Passed' : 'Fail';
        const statusColor = status === 'Passed' ? 'green' : 'red';

        const entry = ` 
          <div style="margin-bottom: 10px; font-size: 14px;">
            <strong>Subject:</strong> ${grade.subject?.name || 'N/A'}<br>
            <strong>Midterm:</strong> ${grade.midterm_grade ?? 'N/A'}<br>
            <strong>Final:</strong> ${grade.final_grade ?? 'N/A'}<br>
            <strong>Average:</strong> ${avg.toFixed(2)} 
            <span style="font-size: 12px; padding: 2px 8px; margin-left: 10px; background-color: ${statusColor}; color: white; border-radius: 12px;">
              ${status}
            </span>
          </div>
        `;

        if (index % 2 === 0) leftColumn += entry;
        else rightColumn += entry;

        totalUnits += grade.subject?.units || 0;
        totalGradePoints += avg;
        if (status === 'Fail') overallStatus = 'Fail';
      });

      const gpa = (totalGradePoints / limitedGrades.length).toFixed(2);
      const overallStatusColor = overallStatus === 'Passed' ? 'green' : 'red';

      const gradesHtml = ` 
        <div style="display: flex; gap: 20px;">
          <div style="flex: 1;">${leftColumn}</div>
          <div style="flex: 1;">${rightColumn}</div>
        </div>
        <div style="text-align: center; margin-top: 20px;">
          <button onclick="editGrades(${studentId})" style="padding: 8px 16px; font-size: 14px;">Edit Grades</button>
        </div>
        <div style="margin-top: 20px; font-size: 16px;">
          <strong>Total Units: </strong>${totalUnits}
        </div>
        <div style="font-size: 16px; margin-top: 10px;">
          <strong>GPA: </strong>${gpa}
        </div>
        <div style="font-size: 16px; margin-top: 10px;">
          <strong>Overall Status: </strong>
          <span style="font-size: 14px; padding: 4px 12px; background-color: ${overallStatusColor}; color: white; border-radius: 12px;">
            ${overallStatus}
          </span>
        </div>
      `;

      Swal.fire({
        title: `<span style="font-size: 20px;">${student.name}'s Grades</span>`,
        html: gradesHtml,
        showCloseButton: true,
        showConfirmButton: false
        
      });
    });
  });
}
function editStudent(studentId) {
  const token = localStorage.getItem("token");

  fetch(`http://127.0.0.1:8000/api/students/${studentId}`, {
    headers: {
      Authorization: `Bearer ${token}`
    }
  })
  .then(res => {
    if (!res.ok) throw new Error('Failed to fetch student');
    return res.json();
  })
  .then(student => {
    Swal.fire({
      title: 'Edit Student',
      html: `
        <input id="name" class="swal2-input" placeholder="Full Name" value="${student.name}">
        <input id="email" class="swal2-input" placeholder="Email" value="${student.email}">
        <input id="course" class="swal2-input" placeholder="Course" value="${student.course}">
        <input id="age" class="swal2-input" placeholder="Age" type="number" value="${student.age}">
      `,
      showCancelButton: true,
      confirmButtonText: 'Save',
      preConfirm: () => {
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const course = document.getElementById('course').value.trim();
        const age = parseInt(document.getElementById('age').value.trim());

        if (!name || !email || !course || isNaN(age)) {
          Swal.showValidationMessage("All fields are required and age must be a number");
          return false;
        }

        return { name, email, course, age };
      }
    }).then((result) => {
      if (!result.isConfirmed) return;

      const { name, email, course, age } = result.value;

      fetch(`http://127.0.0.1:8000/api/students/${studentId}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify({
          name,
          email,
          course,
          age,
          role: "student" // required by backend validation
        })
      })
      .then(response => {
        if (!response.ok) return response.json().then(err => { throw err });
        return response.json();
      })
      .then(() => {
        Swal.fire('Success', 'Student updated successfully!', 'success');
        fetchStudents(); // reload list
      })
      .catch(error => {
        console.error('Update failed:', error);
        const message = error?.message || 'Failed to update student.';
        Swal.fire('Error', message, 'error');
      });
    });
  })
  .catch(error => {
    console.error('Fetch failed:', error);
    Swal.fire('Error', 'Could not load student data.', 'error');
  });
}


function editGrades(studentId) {
  const token = localStorage.getItem("token");

  fetch(`http://127.0.0.1:8000/api/students/${studentId}/grades`, {
    headers: {
      Authorization: `Bearer ${token}`
    }
  })
    .then(res => res.json())
    .then(grades => {
      const limitedGrades = grades.slice(0, 9);
      let leftColumn = "", rightColumn = "";

      limitedGrades.forEach((grade, index) => {
        const inputHtml = `
          <div style="margin-bottom: 10px; font-size: 13px;">
            <label style="display:block; margin-bottom: 4px;"><strong>${grade.subject?.name || 'Subject ' + (index + 1)}</strong></label>
            <div style="display: flex; gap: 6px;">
              <input id="midterm-${grade.id}" class="swal2-input" 
                     style="width: 120px; height: 28px; font-size: 12px; padding: 4px;" 
                     placeholder="Midterm Grade" 
                     value="${grade.midterm_grade ?? ''}">
              <input id="final-${grade.id}" class="swal2-input" 
                     style="width: 120px; height: 28px; font-size: 12px; padding: 4px;" 
                     placeholder="Final Grade" 
                     value="${grade.final_grade ?? ''}">
            </div>
          </div>
        `;
        if (index % 2 === 0) leftColumn += inputHtml;
        else rightColumn += inputHtml;
      });

      const formHtml = `
        <div style="display: flex; gap: 10px;">
          <div style="flex: 1;">${leftColumn}</div>
          <div style="flex: 1;">${rightColumn}</div>
        </div>
      `;

      Swal.fire({
        title: `<span style="font-size: 18px;">Edit Grades</span>`,
        html: formHtml,
        width: '700px',
        confirmButtonText: 'Save',
        showCancelButton: true,
        focusConfirm: false, // prevents focus override
        preConfirm: () => {
          const updates = {};
          let hasError = false;

          limitedGrades.forEach(grade => {
            const midtermInput = document.getElementById(`midterm-${grade.id}`);
            const finalInput = document.getElementById(`final-${grade.id}`);

            if (!midtermInput || !finalInput) {
              hasError = true;
              return;
            }

            updates[grade.id] = {
              midterm_grade: midtermInput.value,
              final_grade: finalInput.value
            };
          });

          if (hasError) {
            Swal.showValidationMessage('Some inputs are missing.');
            return false;
          }

          return updates;
        }
      }).then(result => {
        if (result.isConfirmed) {
          const updateRequests = Object.entries(result.value).map(([id, updated]) => {
            return fetch(`http://127.0.0.1:8000/api/grades/${id}`, {
              method: "PUT",
              headers: {
                Authorization: `Bearer ${token}`,
                'Content-Type': 'application/json'
              },
              body: JSON.stringify(updated)
            });
          });

          Promise.all(updateRequests)
            .then(() => {
              Swal.fire("Success", "Grades updated successfully!", "success");
              viewGrades(studentId); // refresh view
            })
            .catch(() => {
              Swal.fire("Error", "Failed to update grades.", "error");
            });
        }
      });
    });
}

function deleteStudent(id) {
  const token = localStorage.getItem("token");

      Swal.fire({
        title: "Delete Student?",
        text: "This action cannot be undone!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#406ff3",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then(result => {
        if (result.isConfirmed) {
          fetch('http://127.0.0.1:8000/api/grades/export', {
  method: 'GET',
  headers: {
    'Accept': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
  }
})

            .then(res => res.json())
            .then(() => {
              Swal.fire("Deleted!", "Student has been deleted.", "success");
              fetchStudents();
            })
            .catch(() => Swal.fire("Error", "Delete failed.", "error"));
        }
      });
    } 

    function exportExcel() {
  Swal.fire({
    title: 'Exporting...',
    text: 'Your file will download shortly.',
    icon: 'info',
    timer: 1500,
    showConfirmButton: false
  }).then(() => {
    window.open('student-export.php', '_blank');
  });
}

function triggerImport() {
  document.getElementById("excelFileInput").click();
}

function handleFileImport(event) {
  const file = event.target.files[0];
  if (!file) return;

  const formData = new FormData();
  formData.append("file", file);

  // Get CSRF token from meta tag
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  Swal.fire({
    title: 'Uploading...',
    text: 'Please wait while the file is being uploaded.',
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    }
  });

  fetch("http://127.0.0.1:8000/api/grades/import", {
  method: "POST",
  body: formData,
})
  .then(response => {
    if (!response.ok) throw new Error("Import failed");
    return response.json();
  })
  .then(data => {
    Swal.fire({
      title: 'Success!',
      text: data.message || 'Import successful',
      icon: 'success'
    });
    // Optionally refresh data here, e.g. fetchStudents();
  })
  .catch(error => {
    console.error("Import error:", error);
    Swal.fire({
      title: 'Error!',
      text: 'Import failed. Check console for details.',
      icon: 'error'
    });
  });
  
}
async function openAddGradeModal() {
  const token = localStorage.getItem("token");

  try {
    // Fetch all students
    const studentsRes = await fetch("http://127.0.0.1:8000/api/students", {
      headers: { Authorization: `Bearer ${token}` }
    });

    if (!studentsRes.ok) {
      throw new Error(`Failed to fetch students: ${studentsRes.status} ${studentsRes.statusText}`);
    }

    const students = await studentsRes.json();

    // For each student, fetch their grades
    const studentsWithGrades = await Promise.all(students.map(async (student) => {
      try {
        const gradesRes = await fetch(`http://127.0.0.1:8000/api/students/${student.id}/grades`, {
          headers: { Authorization: `Bearer ${token}` }
        });

        if (!gradesRes.ok) {
          if (gradesRes.status === 404) {
            const errorData = await gradesRes.json();
            if (errorData.message && errorData.message.toLowerCase() === 'grades not found') {
              // No grades yet - treat as incomplete grades so we can add grades
              return {
                ...student,
                hasIncompleteGrades: true
              };
            } else {
              console.error(`Unexpected error fetching grades for student ${student.id}:`, errorData);
              return {
                ...student,
                hasIncompleteGrades: true
              };
            }
          }
          // Other errors
          console.error(`Failed to fetch grades for student ${student.id}: ${gradesRes.status}`);
          return {
            ...student,
            hasIncompleteGrades: true
          };
        }

        const grades = await gradesRes.json();

        if (!Array.isArray(grades)) {
          console.warn(`Grades response is not an array for student ${student.id}`, grades);
          return {
            ...student,
            hasIncompleteGrades: true
          };
        }

        const incompleteGrades = grades.some(grade =>
          grade.midterm_grade === null || grade.final_grade === null
        );

        return {
          ...student,
          hasIncompleteGrades: incompleteGrades || grades.length === 0
        };
      } catch (err) {
        console.error(`Error fetching grades for student ${student.id}`, err);
        return {
          ...student,
          hasIncompleteGrades: true
        };
      }
    }));

    // Filter students needing grades
    const studentsToAddGrades = studentsWithGrades.filter(s => s.hasIncompleteGrades);

    if (studentsToAddGrades.length === 0) {
      Swal.fire('Info', 'No students need grades added — all have complete grades.', 'info');
      return;
    }

    // Build dropdown
    let studentOptionsHtml = `<select id="studentSelect" class="swal2-input"><option value="">Select a student</option>`;
    studentsToAddGrades.forEach(s => {
      studentOptionsHtml += `<option value="${s.id}">${s.name} (${s.email})</option>`;
    });
    studentOptionsHtml += `</select>`;

    Swal.fire({
      title: 'Add Grades for Student',
      html: `
        ${studentOptionsHtml}
        <input id="midtermGrade" class="swal2-input" placeholder="Midterm Grade (0-100)" type="number" min="0" max="100">
        <input id="finalGrade" class="swal2-input" placeholder="Final Grade (0-100)" type="number" min="0" max="100">
      `,
      showCancelButton: true,
      confirmButtonText: 'Save Grades',
      preConfirm: () => {
        const studentId = document.getElementById('studentSelect').value;
        const midtermGrade = document.getElementById('midtermGrade').value.trim();
        const finalGrade = document.getElementById('finalGrade').value.trim();

        if (!studentId) {
          Swal.showValidationMessage('Please select a student');
          return false;
        }
        if (midtermGrade === '' || isNaN(midtermGrade) || midtermGrade < 0 || midtermGrade > 100) {
          Swal.showValidationMessage('Please enter a valid midterm grade between 0 and 100');
          return false;
        }
        if (finalGrade === '' || isNaN(finalGrade) || finalGrade < 0 || finalGrade > 100) {
          Swal.showValidationMessage('Please enter a valid final grade between 0 and 100');
          return false;
        }

        return {
          studentId,
          midtermGrade: parseFloat(midtermGrade),
          finalGrade: parseFloat(finalGrade)
        };
      }
    }).then(async (result) => {
      if (!result.isConfirmed) return;

      const { studentId, midtermGrade, finalGrade } = result.value;

      try {
        const response = await fetch(`http://127.0.0.1:8000/api/students/${studentId}/grades`, {
          method: 'PUT', // Changed from POST to PUT here
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
          },
          body: JSON.stringify({
            midterm_grade: midtermGrade,
            final_grade: finalGrade
          })
        });

        if (!response.ok) {
          const errorData = await response.json();
          throw new Error(errorData.message || 'Failed to save grades');
        }

        Swal.fire('Success', 'Grades added successfully!', 'success');
        fetchStudents(); // Refresh list

      } catch (err) {
        Swal.fire('Error', err.message, 'error');
      }
    });

  } catch (error) {
    console.error(error);
    Swal.fire('Error', 'Failed to load students or grades.', 'error');
  }
}

</script>
</html>
