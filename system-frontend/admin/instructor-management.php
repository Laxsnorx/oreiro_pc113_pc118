<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
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
  <h1>Instructor List</h1>

  <div class="action-button-container">
    <button onclick="createInstructor()">Add Instructor</button>
  </div>

  <div style="margin-left: 290px; padding: 0 20px 10px 0; display: flex; justify-content: space-between; align-items: center;">
    <input type="text" id="searchInput" class="swal2-input" style="max-width: 300px;" placeholder="Search by name, course or role" oninput="filterInstructors()" />
  </div>

  <table id="instructorTable">
    <thead>
      <tr>
        <th>ID</th>
        <th>Image</th>
        <th>Name</th>
        <th>Email</th>
        <th>Age</th>
        <th>Course</th>
        <th>Contact</th>
        <th>Role</th> <!-- New Role Column -->
        <th>Subject</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>

  <div id="paginationControls" style="margin-left: 290px; padding: 20px; text-align: right;"></div>

  <script>
    let allInstructors = [];
    let allSubjects = [];
    let filteredInstructors = [];
    let currentPage = 1;
    const rowsPerPage = 8;

    document.addEventListener("DOMContentLoaded", function () {
      if (!localStorage.getItem("token")) return location.href = "login.php";
      fetchInstructors();
    });

    function fetchInstructors() {
  Promise.all([
    fetch("http://127.0.0.1:8000/api/instructors", {
      headers: { Authorization: `Bearer ${localStorage.getItem("token")}` }
    }).then(res => res.json()),
    fetch("http://127.0.0.1:8000/api/subjects", {
      headers: { Authorization: `Bearer ${localStorage.getItem("token")}` }
    }).then(res => res.json())
  ])
  .then(([instructorsData, subjectsData]) => {
    // Your added console.logs here:
    console.log(instructorsData); // Array of instructors
    console.log(subjectsData);    // Array of subjects

    // Your subject-instructor matching logic here:
    subjectsData.forEach(subject => {
      const instructor = instructorsData.find(instr => instr.id === subject.instructor_id);
      const instructorName = instructor ? instructor.name : 'Unassigned';
      console.log(`${subject.name} - Instructor: ${instructorName}`);
      // You can also update UI here if needed
    });

    allInstructors = filteredInstructors = instructorsData;
    allSubjects = subjectsData;
    displayInstructors();
  });
}



function displayInstructors() {
  const tbody = document.querySelector("#instructorTable tbody");
  tbody.innerHTML = "";
  const start = (currentPage - 1) * rowsPerPage;
  const pageData = filteredInstructors.slice(start, start + rowsPerPage);
  
  pageData.forEach(ins => {
    // Find the subject assigned to this instructor
    const subject = allSubjects.find(s => s.instructor_id === ins.id);
    const subjectName = subject ? subject.name : 'Unassigned';

    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${ins.id}</td>
      <td>${ins.image ? `<img src="http://127.0.0.1:8000/storage/${ins.image}" width="50" style="border-radius: 8px;">` : 'N/A'}</td>
      <td>${ins.name}</td>
      <td>${ins.email || 'N/A'}</td>
      <td>${ins.age}</td>
      <td>${ins.course}</td>
      <td>${ins.contact_number || 'N/A'}</td>
      <td>Instructor</td>
      <td>${subjectName}</td>
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

    function fetchInstructors() {
  Promise.all([
    fetch("http://127.0.0.1:8000/api/instructors", {
      headers: { Authorization: `Bearer ${localStorage.getItem("token")}` }
    }).then(res => res.json()),
    fetch("http://127.0.0.1:8000/api/subjects", {
      headers: { Authorization: `Bearer ${localStorage.getItem("token")}` }
    }).then(res => res.json())
  ])
  .then(([instructorsData, subjectsData]) => {
    // Attach subject to instructors for easy access
    instructorsData.forEach(instr => {
      instr.subject = subjectsData.find(s => s.instructor_id === instr.id) || null;
    });
    allInstructors = filteredInstructors = instructorsData;
    allSubjects = subjectsData;
    displayInstructors();
  });
}


    function getAvailableSubjects(currentSubjectId = null) {
  // Subjects not assigned or assigned to current instructor
  return allSubjects.filter(s => !s.instructor_id || s.instructor_id === currentSubjectId);
}

function createInstructor() { 
  const availableSubjects = getAvailableSubjects();
  const subjectOptions = availableSubjects.map(s => `<option value="${s.id}">${s.name}</option>`).join('');

  Swal.fire({
    title: 'Add Instructor',
    html: `
      <input id="name" class="swal2-input" placeholder="Name" autocomplete="off">
      <input id="email" class="swal2-input" type="email" placeholder="Email" autocomplete="off">
      <input id="password" class="swal2-input" type="password" placeholder="Password" autocomplete="new-password">
      <input id="age" class="swal2-input" type="number" placeholder="Age" min="18" max="100" autocomplete="off">
      <input id="course" class="swal2-input" placeholder="Course" autocomplete="off">
      <input id="contact" class="swal2-input" placeholder="Contact Number" autocomplete="off">
      <input id="image" type="file" accept="image/*" style="width:100%; margin: 0 auto 8px; display: block;">
      <input id="role" class="swal2-input" value="Instructor" readonly style="background:#f0f0f0; cursor:not-allowed;">
      <select id="subject" class="swal2-input">
        <option value="">-- Select Subject --</option>
        ${subjectOptions}
      </select>
    `,
    showCancelButton: true,
    confirmButtonText: 'Create',
    focusConfirm: false,  // So the confirm button is not auto-focused
    preConfirm: () => {
      const popup = Swal.getPopup();
      const name = popup.querySelector('#name').value.trim();
      const email = popup.querySelector('#email').value.trim();
      const password = popup.querySelector('#password').value.trim();
      const age = popup.querySelector('#age').value.trim();
      const course = popup.querySelector('#course').value.trim();
      const contact = popup.querySelector('#contact').value.trim();
      const imageInput = popup.querySelector('#image');
      const role = popup.querySelector('#role').value;
      const subjectId = popup.querySelector('#subject').value;

      // Validation
      if (!name || !email || !password || !age || !course || !contact) {
        Swal.showValidationMessage('All fields except image and subject are required');
        return false; // stop submission
      }

      if (subjectId) {
        if (!availableSubjects.find(s => s.id == subjectId)) {
          Swal.showValidationMessage('Selected subject is already assigned to another instructor.');
          return false;
        }
      }

      // Build FormData for POST
      const formData = new FormData();
      formData.append("name", name);
      formData.append("email", email);
      formData.append("password", password);
      formData.append("age", age);
      formData.append("course", course);
      formData.append("contact_number", contact);
      formData.append("role", role);
      if (subjectId) formData.append("subject_id", subjectId);

      if (imageInput.files.length > 0) {
        formData.append("image", imageInput.files[0]);
      }

      return formData; // returned to then() below
    }
  }).then(result => {
    if (result.isConfirmed) {
      fetch("http://127.0.0.1:8000/api/instructors", {
        method: "POST",
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`
          // DO NOT set Content-Type here when sending FormData!
        },
        body: result.value
      })
      .then(async res => {
        if (!res.ok) {
          // Try to get detailed error message from response JSON
          let errMsg = "Failed to create instructor";
          try {
            const errorData = await res.json();
            if (errorData.message) errMsg = errorData.message;
          } catch {}
          throw new Error(errMsg);
        }
        return res.json();
      })
      .then(data => {
        Swal.fire('Success', 'Instructor created successfully', 'success');
        fetchInstructors(); // Refresh the list
      })
      .catch(err => {
        Swal.fire('Error', err.message, 'error');
      });
    }
  });
}




function editInstructor(id) {
  const instructor = allInstructors.find(ins => ins.id === id);
  if (!instructor) {
    alert("Instructor not found!");
    return;
  }

  const availableSubjects = getAvailableSubjects(instructor.subject ? instructor.subject.id : null);

  if (instructor.subject && !availableSubjects.some(s => s.id === instructor.subject.id)) {
    availableSubjects.push(instructor.subject);
  }

  availableSubjects.sort((a, b) => a.name.localeCompare(b.name));

  const subjectOptions = availableSubjects.map(s =>
    `<option value="${s.id}" ${instructor.subject && instructor.subject.id === s.id ? 'selected' : ''}>${s.name}</option>`
  ).join('');

  Swal.fire({
    title: 'Edit Instructor',
    html: `
      <input id="name" class="swal2-input" value="${instructor.name}" autocomplete="off" placeholder="Name">
      <input id="email" class="swal2-input" type="email" value="${instructor.email}" autocomplete="off" placeholder="Email">
      <input id="password" class="swal2-input" placeholder="Enter new password if changing" autocomplete="new-password" type="password">
      <input id="age" class="swal2-input" type="number" value="${instructor.age}" min="18" max="100" autocomplete="off" placeholder="Age">
      <input id="course" class="swal2-input" value="${instructor.course}" autocomplete="off" placeholder="Course">
      <input id="contact" class="swal2-input" value="${instructor.contact_number}" autocomplete="off" placeholder="Contact Number">
      <input id="image" type="file" accept="image/*" style="width:100%; margin: 0 auto 8px; display: block;">
      <select id="subject" class="swal2-input">
        <option value="">-- Unassigned --</option>
        ${subjectOptions}
      </select>
    `,
    showCancelButton: true,
    confirmButtonText: 'Update',
    preConfirm: () => {
      const name = Swal.getPopup().querySelector('#name').value.trim();
      const email = Swal.getPopup().querySelector('#email').value.trim();
      const password = Swal.getPopup().querySelector('#password').value.trim();
      const age = Swal.getPopup().querySelector('#age').value.trim();
      const course = Swal.getPopup().querySelector('#course').value.trim();
      const contact = Swal.getPopup().querySelector('#contact').value.trim();
      const imageInput = Swal.getPopup().querySelector('#image');
      const subjectId = Swal.getPopup().querySelector('#subject').value;

      if (!name || !email || !age || !course || !contact) {
        Swal.showValidationMessage('Please fill all required fields');
        return false;
      }

      const formData = new FormData();
      formData.append("name", name);
      formData.append("email", email);
      if (password) formData.append("password", password);
      formData.append("age", age);
      formData.append("course", course);
      formData.append("contact_number", contact);
      formData.append("subject_id", subjectId || '');

      if (imageInput.files.length > 0) {
        formData.append("image", imageInput.files[0]);
      }

      return formData;
    }
  }).then(result => {
    if (result.isConfirmed) {
      fetch(`http://127.0.0.1:8000/api/instructors/${id}`, {
        method: "POST", // or PUT, depending on your API
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`
          // Don't set Content-Type here!
        },
        body: result.value
      })
      .then(res => {
        if (!res.ok) throw new Error("Failed to update instructor");
        return res.json();
      })
      .then(data => {
        Swal.fire('Success', 'Instructor updated successfully', 'success');
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
        title: 'Are you sure?',
        text: "This will delete the instructor permanently.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete!',
        cancelButtonText: 'Cancel'
      }).then(result => {
        if (result.isConfirmed) {
          fetch(`http://127.0.0.1:8000/api/instructors/${id}`, {
            method: "DELETE",
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`
            }
          })
          .then(res => {
            if (!res.ok) throw new Error('Failed to delete instructor');
            Swal.fire('Deleted!', 'Instructor has been deleted.', 'success');
            fetchInstructors();
          })
          .catch(err => Swal.fire('Error', err.message, 'error'));
        }
      });
    }

    function viewInstructor(id) {
  const ins = allInstructors.find(i => i.id === id);
  if (!ins) return;

  Swal.fire({
    title: `<span style="font-size: 1.3rem; font-weight: 600; color: #333;">${ins.name}</span>`,
    html: `
      ${ins.image ? `<img src="http://127.0.0.1:8000/storage/${ins.image}" 
        style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; margin-bottom: 15px; border: 2px solid #406ff3;">` : ''}
      <div style="text-align: left; font-size: 1rem; color: #555; line-height: 1.5;">
        <p><strong style="color: #406ff3;">Email:</strong> ${ins.email || 'N/A'}</p>
        <p><strong style="color: #406ff3;">Age:</strong> ${ins.age}</p>
        <p><strong style="color: #406ff3;">Course:</strong> ${ins.course}</p>
        <p><strong style="color: #406ff3;">Contact Number:</strong> ${ins.contact_number}</p>
        <p><strong style="color: #406ff3;">Role:</strong> Instructor</p>
      </div>
    `,
    confirmButtonText: 'Close',
    width: '600px',  // <-- added width here
    padding: '1.5rem 2rem',
    background: '#fff',
    backdrop: 'rgba(0,0,0,0.4) left top no-repeat',
  });
}
function getAvailableSubjects(excludeSubjectId = null) {
  // Gather all subject IDs currently assigned
  const assignedSubjectIds = allInstructors
    .map(ins => ins.subject ? ins.subject.id : null)
    .filter(id => id !== null && id !== excludeSubjectId);

  // Return subjects NOT assigned or matching the excludeSubjectId
  return allSubjects.filter(s => !assignedSubjectIds.includes(s.id));
}


  </script>
</body>
</html>
