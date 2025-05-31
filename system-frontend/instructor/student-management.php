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
    <tbody></tbody>
  </table>

  <div id="paginationControls" style="margin-left: 290px; padding: 20px; text-align: right;"></div>

  <script>
    let allStudents = [];
    let filteredStudents = [];
    let currentPage = 1;
    const rowsPerPage = 8;

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

    function createStudent() {
  Swal.fire({
    title: 'Add Student',
    html: `
      <input type="text" id="name" class="swal2-input" placeholder="Full Name">
      <input type="number" id="age" class="swal2-input" placeholder="Age">
      <input type="email" id="email" class="swal2-input" placeholder="Email">
      <input type="text" id="course" class="swal2-input" placeholder="Course">
    `,
    confirmButtonText: 'Create',
    showCancelButton: true,
    preConfirm: () => {
      const name = document.getElementById('name').value.trim();
      const age = parseInt(document.getElementById('age').value.trim());
      const email = document.getElementById('email').value.trim();
      const course = document.getElementById('course').value.trim();

      if (!name || isNaN(age) || !email || !course) {
        Swal.showValidationMessage('All fields are required');
        return false;
      }

      return { name, age, email, course };
    }
  }).then(result => {
    if (result.isConfirmed) {
      const token = localStorage.getItem('token');

      fetch("http://127.0.0.1:8000/api/students", {
        method: "POST",
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify(result.value)
      })
      .then(res => res.json())
      .then(data => {
        Swal.fire('Success', 'Student created successfully', 'success');
        fetchStudents();
      })
      .catch(() => Swal.fire('Error', 'Failed to create student', 'error'));
    }
  });
}


function editStudent(id) {
  const token = localStorage.getItem("token");
  const student = allStudents.find(s => s.id === id);

  if (!student) {
    Swal.fire("Error", "Student not found.", "error");
    return;
  }

  Swal.fire({
    title: 'Edit Student',
    html: `
      <input id="swal-input1" class="swal2-input" placeholder="Full Name" value="${student.name}">
      <input id="swal-input2" class="swal2-input" placeholder="Age" type="number" value="${student.age || ''}">
      <input id="swal-input3" class="swal2-input" placeholder="Email" value="${student.email}">
      <input id="swal-input4" class="swal2-input" placeholder="Course" value="${student.course}">
    `,
    focusConfirm: false,
    showCancelButton: true,
    confirmButtonText: 'Update',
    preConfirm: () => {
      const name = document.getElementById('swal-input1').value.trim();
      const age = parseInt(document.getElementById('swal-input2').value.trim());
      const email = document.getElementById('swal-input3').value.trim();
      const course = document.getElementById('swal-input4').value.trim();

      if (!name || isNaN(age) || !email || !course) {
        Swal.showValidationMessage('All fields are required');
        return false;
      }

      return { name, age, email, course };
    }
  }).then((result) => {
    if (result.isConfirmed) {
      const updatedData = result.value;

      fetch(`http://127.0.0.1:8000/api/students/${id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify(updatedData)
      })
      .then(res => {
        if (!res.ok) throw new Error('Failed to update student');
        return res.json();
      })
      .then(() => {
        Swal.fire('Updated!', 'Student info has been updated.', 'success');
        fetchStudents();
      })
      .catch(() => {
        Swal.fire('Error', 'Update failed.', 'error');
      });
    }
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

    function viewGrades(id) {
      // Placeholder: You can implement this
      alert("View grades for student ID: " + id);
    }
  </script>
</body>
</html>
