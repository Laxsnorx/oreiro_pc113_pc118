<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <title>User Management</title>
  <style>
  /* General styling for the body and layout */
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
</style>

</head>
<body>

<?php include 'sidebar.php'; ?>

<h1>User Management</h1>

<div class="action-button-container">
  <button onclick="createUser()">Create New User</button>
</div>

<table id="usersTable">
  <thead>
    <tr>
      <th>User ID</th>
      <th>Username</th>
      <th>Email</th>
      <th>Phone Number</th>
      <th>Role</th>
      <th>Password</th>
      <th>Image</th>
      <th>File</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <!-- Filled dynamically -->
  </tbody>
</table>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const token = localStorage.getItem("token");

  if (!token) {
    window.location.href = "login.php";
    return;
  }

  loadUsers();
});

function loadUsers() {
  const token = localStorage.getItem("token");

  fetch("http://127.0.0.1:8000/api/user", {
    headers: {
      Authorization: `Bearer ${token}`
    }
  })
  .then(res => res.json())
  .then(data => {
    const tbody = document.querySelector("#usersTable tbody");
    tbody.innerHTML = "";

    data.forEach(user => {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td>${user.id}</td>
        <td>${user.name}</td>
        <td>${user.email}</td>
        <td>${user.phone || 'N/A'}</td>
        <td>${user.role || 'N/A'}</td>
        <td>${user.password || '********'}</td>
        <td>
          ${user.image ? `<img src="http://127.0.0.1:8000/storage/${user.image}" width="50" height="50">` : 'No Image'}
        </td>
        <td>
          ${user.file ? `<a href="http://127.0.0.1:8000/storage/${user.file}" target="_blank">Download</a>` : 'No File'}
        </td>
        <td>
          <button onclick="editUser(${user.id})">Edit</button>
          <button class="btn-delete" onclick="deleteUser(${user.id})">Delete</button>
        </td>
      `;
      tbody.appendChild(row);
    });
  })
  .catch(error => {
    console.error("Failed to load users:", error);
  });
}


function createUser() {
  Swal.fire({
    title: 'Create New User',
    html: `
      <input id="swal-name" class="swal2-input" placeholder="Name">
      <input id="swal-email" class="swal2-input" placeholder="Email">
      <input id="swal-phone" class="swal2-input" placeholder="Phone Number">
      <select id="swal-role" class="swal2-input">
        <option value="admin">Admin</option>
        <option value="teacher">Teacher</option>
        <option value="student">Student</option>
      </select>
      <input id="swal-password" class="swal2-input" placeholder="Password" type="password">
    `,
    confirmButtonText: 'Create',
    showCancelButton: true,
    preConfirm: () => {
      return {
        name: document.getElementById('swal-name').value,
        email: document.getElementById('swal-email').value,
        phone: document.getElementById('swal-phone').value,
        role: document.getElementById('swal-role').value,
        password: document.getElementById('swal-password').value
      };
    }
  }).then(result => {
    if (result.isConfirmed) {
      const token = localStorage.getItem("token");

      fetch("http://127.0.0.1:8000/api/user", {
        method: "POST",
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(result.value)
      })
      .then(res => {
        if (!res.ok) throw new Error('Failed to create user');
        return res.json();
      })
      .then(() => {
        Swal.fire("Created!", "New user has been created.", "success");
        loadUsers(); // Reload the user list to include the new user
      })
      .catch(() => Swal.fire("Error", "User creation failed.", "error"));
    }
  });
}

function editUser(id) {
  const token = localStorage.getItem("token");

  fetch(`http://127.0.0.1:8000/api/user/${id}`, {
    headers: {
      Authorization: `Bearer ${token}`
    }
  })
  .then(res => res.json())
  .then(user => {
    Swal.fire({
      title: `Edit User ${user.name}`,
      html: `
        <input id="swal-name" class="swal2-input" placeholder="Name" value="${user.name}">
        <input id="swal-email" class="swal2-input" placeholder="Email" value="${user.email}">
        <select id="swal-role" class="swal2-input">
          <option value="admin" ${user.role === 'admin' ? 'selected' : ''}>Admin</option>
          <option value="teacher" ${user.role === 'teacher' ? 'selected' : ''}>Teacher</option>
          <option value="student" ${user.role === 'student' ? 'selected' : ''}>Student</option>
        </select>
        <input id="swal-phone" class="swal2-input" placeholder="Phone Number" value="${user.phone || ''}">
      `,
      confirmButtonText: 'Update',
      showCancelButton: true,
      preConfirm: () => {
        return {
          name: document.getElementById('swal-name').value,
          email: document.getElementById('swal-email').value,
          role: document.getElementById('swal-role').value,
          phone: document.getElementById('swal-phone').value
        };
      }
    }).then(result => {
      if (result.isConfirmed) {
        const updatedData = result.value;

        fetch(`http://127.0.0.1:8000/api/user/${id}`, {
          method: 'PUT',
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(updatedData)
        })
        .then(res => {
          if (!res.ok) throw new Error('Failed to update');
          return res.json();
        })
        .then(() => {
          Swal.fire("Updated!", "User updated successfully.", "success");
          loadUsers();
        })
        .catch(() => Swal.fire("Error", "Update failed.", "error"));
      }
    });
  })
  .catch(() => Swal.fire("Error", "Failed to fetch user details.", "error"));
}





function deleteUser(id) {
  const token = localStorage.getItem("token");

  Swal.fire({
    title: "Delete User?",
    text: "This action cannot be undone!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#406ff3",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!"
  }).then(result => {
    if (result.isConfirmed) {
      fetch(`http://127.0.0.1:8000/api/user/${id}`, {
        method: "DELETE",
        headers: {
          Authorization: `Bearer ${token}`
        }
      })
      .then(res => {
        if (!res.ok) throw new Error('Failed to delete');
        Swal.fire("Deleted!", "User has been deleted.", "success");
        loadUsers(); // Reload the user list after deletion
      })
      .catch(() => Swal.fire("Error", "Delete failed.", "error"));
    }
  });
}
</script>

</body>
</html>
