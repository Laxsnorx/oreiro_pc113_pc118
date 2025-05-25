<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student List</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

  </style>
</head>
<body>
  <?php include 'sidebar.php'; ?>
  <h1>Student List</h1>

  <div class="action-button-container">
    <button onclick="createStudent()">Add Student</button>
  </div>
<div style="margin-left: 290px; padding: 0 20px 10px 0; display: flex; justify-content: space-between; align-items: center;">
  <input type="text" id="searchInput" class="swal2-input" style="max-width: 300px;" placeholder="Search student by name or email" oninput="filterStudents()">
</div>

  <table id="studentsTable">
    <thead>
      <tr>
        <th>ID</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Course</th>
        <th>Actions</th>
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

function viewGrades(studentId) {
  const token = localStorage.getItem("token");

  // Fetch student details
  fetch(`http://127.0.0.1:8000/api/students/${studentId}`, {
    headers: {
      Authorization: `Bearer ${token}`
    }
  })
    .then(res => res.json())
    .then(student => {
      // Fetch student's grades
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
          // Limit the grades to the first 9 subjects
          const limitedGrades = grades.slice(0, 9);

          let leftColumn = "", rightColumn = "";
          let totalUnits = 0; // To keep track of the total units
          let overallStatus = 'Passed'; // Default overall status
          let totalGradePoints = 0; // To calculate GPA

          limitedGrades.forEach((grade, index) => {
            const gradeValue = parseFloat(grade.grade);
            const status = gradeValue >= 1.00 && gradeValue <= 3.00 ? 'Passed' : 'Fail';
            const statusColor = status === 'Passed' ? 'green' : 'red';

            const entry = ` 
              <div style="margin-bottom: 10px; font-size: 14px;">
                <strong>Subject:</strong> ${grade.subject?.name || 'N/A'}<br>
                <strong>Grade:</strong> ${grade.grade} 
                <span style="font-size: 12px; padding: 2px 8px; margin-left: 10px; background-color: ${statusColor}; color: white; border-radius: 12px;">
                  ${status}
                </span>
              </div>
            `;
            if (index % 2 === 0) {
              leftColumn += entry;
            } else {
              rightColumn += entry;
            }

            // Calculate total units
            totalUnits += grade.subject?.units || 0;

            // Calculate total grade points for GPA
            totalGradePoints += gradeValue;

            // If any grade is "Fail", update the overall status
            if (status === 'Fail') {
              overallStatus = 'Fail';
            }
          });

          // Calculate GPA (average of grade points)
          const gpa = (totalGradePoints / limitedGrades.length).toFixed(2);

          // Overall status color
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
            showConfirmButton: false
          });
        });
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
      const limitedGrades = grades.slice(0, 9); // Limit to 9 subjects as requested

      let leftColumn = "", rightColumn = "";

      limitedGrades.forEach((grade, index) => {
        const inputHtml = `
          <div style="margin-bottom: 10px; font-size: 14px; display: flex; align-items: center; gap: 10px;">
            <label style="flex: 1;"><strong>${grade.subject?.name || 'Subject ' + (index + 1)}</strong></label>
            <input id="grade-${grade.id}" class="swal2-input" style="font-size: 14px; height: 30px; flex: 1;" type="text" value="${grade.grade}">
          </div>
        `;
        if (index % 2 === 0) {
          leftColumn += inputHtml;
        } else {
          rightColumn += inputHtml;
        }
      });

      const formHtml = `
        <div style="display: flex; gap: 20px;">
          <div style="flex: 1; display: flex; flex-direction: column;">${leftColumn}</div>
          <div style="flex: 1; display: flex; flex-direction: column;">${rightColumn}</div>
        </div>
      `;

      Swal.fire({
        title: `<span style="font-size: 20px;">Edit Grades</span>`,
        html: formHtml,
        confirmButtonText: 'Save',
        showCancelButton: true,
        preConfirm: () => {
          const updates = {};
          limitedGrades.forEach(grade => {
            updates[grade.id] = document.getElementById(`grade-${grade.id}`).value;
          });
          return updates;
        }
      }).then(result => {
        if (result.isConfirmed) {
          const updateRequests = Object.entries(result.value).map(([id, newGrade]) => {
            return fetch(`http://127.0.0.1:8000/api/grades/${id}`, {
              method: "PUT",
              headers: {
                Authorization: `Bearer ${token}`,
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({ grade: newGrade })
            });
          });

          Promise.all(updateRequests)
            .then(() => {
              Swal.fire("Success", "Grades updated successfully!", "success");
              viewGrades(studentId); // Refresh grades view
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
          fetch(`http://127.0.0.1:8000/api/students/${id}`, {
            method: "DELETE",
            headers: {
              Authorization: `Bearer ${token}`
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


</script>
</body>
</html>
