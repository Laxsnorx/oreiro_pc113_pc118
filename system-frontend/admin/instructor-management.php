<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Instructor List</title>
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
    padding: 20px;
    font-size: 40px;
    color: #406ff3;
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
  <h1>Instructor List</h1>

  <div class="action-button-container">
    <button onclick="createInstructor()">Add Instructor</button>
  </div>

  <div style="margin-left: 290px; padding: 0 20px 10px 0; display: flex; justify-content: space-between; align-items: center;">
    <input type="text" id="searchInput" class="swal2-input" style="max-width: 300px;" placeholder="Search by name or course" oninput="filterInstructors()">
  </div>

  <table id="instructorTable">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th> 
        <th>Age</th>
        <th>Course</th>
        <th>Image</th>
        <th>Contact</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>

  <div id="paginationControls" style="margin-left: 290px; padding: 20px; text-align: right;"></div>

  <script>
        let allInstructors = [];
let filteredInstructors = [];
let currentPage = 1;
const rowsPerPage = 8;

document.addEventListener("DOMContentLoaded", function () {
  if (!localStorage.getItem("token")) return location.href = "login.php";
  fetchInstructors();
});

function fetchInstructors() {
  fetch("http://127.0.0.1:8000/api/instructors", {
    headers: { Authorization: `Bearer ${localStorage.getItem("token")}` }
  })
  .then(res => res.json())
  .then(data => {
    allInstructors = filteredInstructors = data;
    displayInstructors();
  });
}

function displayInstructors() {
  const tbody = document.querySelector("#instructorTable tbody");
  tbody.innerHTML = "";
  const start = (currentPage - 1) * rowsPerPage;
  const pageData = filteredInstructors.slice(start, start + rowsPerPage);
  pageData.forEach(ins => {
    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${ins.id}</td>
      <td>${ins.name}</td>
      <td>${ins.email || 'N/A'}</td>
      <td>${ins.age}</td>
      <td>${ins.course}</td>
      <td>${ins.image ? `<img src="http://127.0.0.1:8000/storage/${ins.image}" width="50">` : 'N/A'}</td>
      <td>${ins.contact_number}</td>
        <td>
  <div style="display: flex; gap: 5px;">
    <button style="background-color: yellow; border: none; color: black; padding: 10px 10px; cursor: pointer;" onclick="viewInstructor(${ins.id})">View</button>
    <button onclick="editInstructor(${ins.id})">Edit</button>
    <button class="btn-delete" onclick="deleteInstructor(${ins.id})">Delete</button>
  </div>
</td>
    `;
    tbody.appendChild(row);
  });
  renderPaginationControls();
}

function renderPaginationControls() {
  const totalPages = Math.ceil(filteredInstructors.length / rowsPerPage);
  const div = document.getElementById("paginationControls");
  div.innerHTML = "";
  for (let i = 1; i <= totalPages; i++) {
    const btn = document.createElement("button");
    btn.textContent = i;
    btn.className = i === currentPage ? "active" : "";
    btn.onclick = () => { currentPage = i; displayInstructors(); };
    div.appendChild(btn);
  }
}

function filterInstructors() {
  const term = document.getElementById("searchInput").value.toLowerCase();
  filteredInstructors = allInstructors.filter(i =>
    i.name.toLowerCase().includes(term) ||
    i.course.toLowerCase().includes(term) ||
    (i.email && i.email.toLowerCase().includes(term))
  );
  currentPage = 1;
  displayInstructors();
}

function createInstructor() {
  Swal.fire({
    title: 'Add Instructor',
    html: `
      <input id="name" class="swal2-input" placeholder="Name">
      <input id="email" class="swal2-input" type="email" placeholder="Email">
      <input id="age" class="swal2-input" type="number" placeholder="Age">
      <input id="course" class="swal2-input" placeholder="Course">
      <input id="contact" class="swal2-input" placeholder="Contact Number">
      <input id="image" class="swal2-input" type="file" accept="image/*">
    `,
    showCancelButton: true,
    confirmButtonText: 'Create',
    preConfirm: () => {
      const name = document.getElementById('name').value.trim();
      const email = document.getElementById('email').value.trim();
      const age = document.getElementById('age').value.trim();
      const course = document.getElementById('course').value.trim();
      const contact = document.getElementById('contact').value.trim();
      const image = document.getElementById('image').files[0];

      if (!name || !email || !age || !course || !contact) {
        Swal.showValidationMessage('All fields except image are required');
        return false;
      }

      const formData = new FormData();
      formData.append("name", name);
      formData.append("email", email);
      formData.append("age", age);
      formData.append("course", course);
      formData.append("contact_number", contact);
      if (image) formData.append("image", image);

      return formData;
    }
  }).then(result => {
    if (result.isConfirmed) {
      fetch("http://127.0.0.1:8000/api/instructors", {
        method: "POST",
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`
          // No Content-Type because of FormData
        },
        body: result.value
      })
      .then(async res => {
        if (!res.ok) {
          const error = await res.text();
          throw new Error(error || 'Failed to add instructor');
        }
        return res.json();
      })
      .then(() => {
        Swal.fire('Success', 'Instructor added!', 'success');
        fetchInstructors();
      })
      .catch(err => {
        Swal.fire('Error', err.message, 'error');
      });
    }
  });
}

function editInstructor(id) {
  const instructor = allInstructors.find(i => i.id === id);
  if (!instructor) return;

  Swal.fire({
    title: `Edit Instructor: ${instructor.name}`,
    html: `
      ${instructor.image ? `<img src="http://127.0.0.1:8000/storage/${instructor.image}" style="width: 100%; max-width: 200px; border-radius: 8px; margin-bottom: 15px;">` : ''}
      <input id="name" class="swal2-input" placeholder="Name" value="${instructor.name}">
      <input id="email" class="swal2-input" type="email" placeholder="Email" value="${instructor.email}">
      <input id="age" class="swal2-input" type="number" placeholder="Age" value="${instructor.age}">
      <input id="course" class="swal2-input" placeholder="Course" value="${instructor.course}">
      <input id="contact" class="swal2-input" placeholder="Contact Number" value="${instructor.contact_number}">
      <input id="image" class="swal2-input" type="file" accept="image/*">
    `,
    showCancelButton: true,
    confirmButtonText: 'Update',
    preConfirm: () => {
      const name = document.getElementById('name').value.trim();
      const email = document.getElementById('email').value.trim();
      const age = document.getElementById('age').value.trim();
      const course = document.getElementById('course').value.trim();
      const contact = document.getElementById('contact').value.trim();
      const image = document.getElementById('image').files[0];

      if (!name || !email || !age || !course || !contact) {
        Swal.showValidationMessage('All fields except image are required');
        return false;
      }

      const formData = new FormData();
      formData.append("name", name);
      formData.append("email", email);
      formData.append("age", age);
      formData.append("course", course);
      formData.append("contact_number", contact);
      if (image) formData.append("image", image);

      return formData;
    }
  }).then(result => {
    if (result.isConfirmed) {
      fetch(`http://127.0.0.1:8000/api/instructors/${id}`, {
        method: "POST", // Laravel override PUT with _method param
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`
          // No Content-Type header here
        },
        body: (() => {
          const formData = result.value;
          formData.append('_method', 'PUT');
          return formData;
        })()
      })
      .then(async res => {
        if (!res.ok) {
          const error = await res.text();
          throw new Error(error || 'Failed to update instructor');
        }
        return res.json();
      })
      .then(() => {
        Swal.fire('Updated!', 'Instructor updated.', 'success');
        fetchInstructors();
      })
      .catch(err => {
        Swal.fire('Error', err.message, 'error');
      });
    }
  });
}


function deleteInstructor(id) {
  Swal.fire({
    title: 'Delete this instructor?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, delete',
    confirmButtonColor: '#406ff3',
    cancelButtonColor: '#d33'
  }).then(result => {
    if (result.isConfirmed) {
      fetch(`http://127.0.0.1:8000/api/instructors/${id}`, {
        method: "DELETE",
        headers: { Authorization: `Bearer ${localStorage.getItem("token")}` }
      })
      .then(() => {
        Swal.fire('Deleted!', 'Instructor has been removed.', 'success');
        fetchInstructors();
      });
    }
  });
}

  </script>
</body>
</html>
