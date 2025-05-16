<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>User List</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="styles/user-list.css?v=1.0.7" />
</head>

<body>

  <?php include 'partials/sidebar.php'; ?>

  <div class="main-content">
    <div class="container mt-5">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">User List</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
          + Add User
        </button>
      </div>

      <table id="userTable" class="table table-hover table-bordered">
        <thead class="table-dark">
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
        <tbody id="userTableBody">
        </tbody>
      </table>
    </div>
  </div>

<!-- ADD USER MODAL -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addUserForm" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" id="username" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="text" id="phone" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <input type="text" id="role" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="image" class="form-label">Upload Image</label>
            <input type="file" id="image" class="form-control">
          </div>
          <div class="mb-3">
            <label for="file" class="form-label">Upload File</label>
            <input type="file" id="file" class="form-control">
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Add User</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- EDIT USER MODAL -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editUserForm" enctype="multipart/form-data">
          <input type="hidden" id="editUserId">
          <div class="mb-3">
            <label for="editUsername" class="form-label">Username</label>
            <input type="text" id="editUsername" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="editEmail" class="form-label">Email</label>
            <input type="email" id="editEmail" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="editPhone" class="form-label">Phone Number</label>
            <input type="text" id="editPhone" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="editRole" class="form-label">Role</label>
            <input type="text" id="editRole" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="editPassword" class="form-label">New Password (Leave empty to keep current)</label>
            <input type="password" id="editPassword" class="form-control">
          </div>
          <div class="mb-3">
            <label for="editImage" class="form-label">Upload New Image</label>
            <input type="file" id="editImage" class="form-control">
          </div>
          <div class="mb-3">
            <label for="editFile" class="form-label">Upload New File</label>
            <input type="file" id="editFile" class="form-control">
          </div>
          <div id="currentImage"></div>
          <div id="currentFileLink"></div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update User</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


  
<script>

  document.addEventListener("DOMContentLoaded", function () {
  const token = localStorage.getItem("token");
  if (!token) {
    window.location.href = "login.php";
    return;
  }

  const apiUrl = "http://127.0.0.1:8000/api/user";
  let dataTable;

  function fetchUsers() {
  fetch(apiUrl, {
    method: "GET",
    mode: "no-cors", // This will disable CORS checks
    headers: {
      Authorization: `Bearer ${token}`,
    },
  })
    .then((response) => response.json())
    .then((data) => {
      const users = data.data || data;
      const tbody = document.getElementById("userTableBody");
      tbody.innerHTML = "";

      if (users && users.length > 0) {
        users.forEach(function (user) {
          const row = document.createElement("tr");
          row.innerHTML = `
            <td>${user.id}</td> <!-- User ID -->
            <td>${user.name || "No Username"}</td> <!-- Username -->
            <td>${user.email}</td> <!-- Email -->
            <td>${user.phone || "N/A"}</td> <!-- Phone Number -->
            <td>${user.role || "N/A"}</td> <!-- Role -->
            <td>${user.password || "N/A"}</td> <!-- Password -->
            <td>
              ${user.image_url ? `<img src="${user.image_url}" alt="Image" width="50" height="50">` : 'No Image'}
            </td> <!-- Image -->
            <td>
              ${user.file_url ? `<a href="${user.file_url}" target="_blank">View File</a>` : 'No File'}
            </td> <!-- File -->
            <td>
              <button type="button" class="btn btn-outline-primary btn-sm edit-btn" 
                      data-id="${user.id}" 
                      data-username="${user.name || 'No Username'}" 
                      data-email="${user.email}" 
                      data-phone="${user.phone}" 
                      data-role="${user.role}" 
                      data-image-url="${user.image_url || ''}" 
                      data-file-url="${user.file_url || ''}">
                Edit
              </button>
              <button type="button" class="btn btn-outline-danger btn-sm delete-btn" 
                      data-id="${user.id}">
                Delete
              </button>
            </td> <!-- Actions -->
          `;
          tbody.appendChild(row);
        });

        if (!$.fn.dataTable.isDataTable("#userTable")) {
          dataTable = $("#userTable").DataTable({
            responsive: true,
            paging: true,
            searching: true,
            ordering: true,
          });
        } else {
          dataTable.clear().rows.add(tbody.querySelectorAll("tr")).draw();
        }
      } else {
        tbody.innerHTML = "<tr><td colspan='9'>No users found.</td></tr>";
      }
    })
    .catch((error) => {
      console.error("Error loading users:", error);
    });
}


  //! Add User
  document.getElementById("addUserForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData();
    formData.append("name", document.getElementById("username").value);
    formData.append("email", document.getElementById("email").value);
    formData.append("role", document.getElementById("role").value);
    formData.append("phone", document.getElementById("phone").value);
    formData.append("password", document.getElementById("password").value); 

  
    if (document.getElementById("file").files[0]) {
      formData.append("file", document.getElementById("file").files[0]);
    }
    if (document.getElementById("image").files[0]) {
      formData.append("image", document.getElementById("image").files[0]);
    }

    fetch(apiUrl, {
      method: "POST",
      headers: {
        Authorization: `Bearer ${token}`,
      },
      body: formData,
    })
      .then(() => {
        fetchUsers();
        $("#addUserModal").modal("hide");
        Swal.fire({
          icon: 'success',
          title: 'User Added!',
          text: 'The user has been successfully added.',
        });
      })
      .catch((error) => {
        console.error("Error adding user:", error);
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          text: 'There was an issue adding the user.',
        });
      });
  });

  //! Edit User
  document.addEventListener("click", function (e) {
    if (e.target && e.target.classList.contains("edit-btn")) {
      const userId = e.target.dataset.id;
      document.getElementById("editUsername").value = e.target.dataset.username;
      document.getElementById("editEmail").value = e.target.dataset.email;
      document.getElementById("editPhone").value = e.target.dataset.phone;
      document.getElementById("editRole").value = e.target.dataset.role;

      const form = document.getElementById("editUserForm");
      form.dataset.id = userId;

      
      const currentImage = e.target.dataset.image_url ? 
        `<img src="${e.target.dataset.image_url}" alt="Image" width="50" height="50">` : 
        'No Image';
      document.getElementById("currentImage").innerHTML = currentImage;

      const currentFileLink = e.target.dataset.file_url ? 
        `<a href="${e.target.dataset.file_url}" target="_blank">View File</a>` : 
        'No File';
      document.getElementById("currentFileLink").innerHTML = currentFileLink;

      // Show the modal
      $("#editUserModal").modal("show");
    }
  });
    //! Edit User 
  document.getElementById("editUserForm").addEventListener("submit", function (e) {
    e.preventDefault();
    const userId = this.dataset.id;
    const formData = new FormData();

    formData.append("username", document.getElementById("editUsername").value);
    formData.append("email", document.getElementById("editEmail").value);
    formData.append("role", document.getElementById("editRole").value);
    formData.append("phone", document.getElementById("editPhone").value);
    if (document.getElementById("editPassword").value) {
      formData.append("password", document.getElementById("editPassword").value); // Password field added for edit
    }

    if (document.getElementById("editFile").files[0]) {
      formData.append("file", document.getElementById("editFile").files[0]);
    }
    if (document.getElementById("editImage").files[0]) {
      formData.append("image", document.getElementById("editImage").files[0]);
    }

    fetch(`${apiUrl}/${userId}`, {
      method: "PUT", 
      headers: {
        Authorization: `Bearer ${token}`,
      },
      body: formData,
    })
      .then(() => {
        fetchUsers();
        $("#editUserModal").modal("hide");
        Swal.fire({
          icon: 'success',
          title: 'User Updated!',
          text: 'The user has been successfully updated.',
        });
      })
      .catch((error) => {
        console.error("Error updating user:", error);
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          text: 'There was an issue updating the user.',
        });
      });
  });

  //! Delete User
  document.addEventListener("click", function (e) {
    if (e.target && e.target.classList.contains("delete-btn")) {
      const userId = e.target.dataset.id;
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(`${apiUrl}/${userId}`, {
            method: "DELETE",
            headers: {
              Authorization: `Bearer ${token}`,
            },
          })
            .then(() => {
              fetchUsers();
              Swal.fire('Deleted!', 'The user has been deleted.', 'success');
            })
            .catch((error) => {
              console.error("Error deleting user:", error);
              Swal.fire('Error!', 'There was an issue deleting the user.', 'error');
            });
        }
      });
    }
  });

  fetchUsers();
});

</script>
</body>
</html>
