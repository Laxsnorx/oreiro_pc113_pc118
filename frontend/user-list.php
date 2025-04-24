<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>User List</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"/>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="styles/user-list.css?v=1.0.1" />
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
            <th>Role</th>
            <th>File</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="userTableBody">
        </tbody>
      </table>
    </div>
  </div>

  <!-- Add User Modal -->
  <div id="addUserModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="addUserForm" enctype="multipart/form-data">
            <div class="form-group">
              <label>Username</label>
              <input type="text" id="username" class="form-control" required />
            </div>
            <div class="form-group">
              <label>Email</label>
              <input type="email" id="email" class="form-control" required />
            </div>
            <div class="form-group">
              <label>Role</label>
              <input type="text" id="role" class="form-control" required />
            </div>
            <div class="form-group">
              <label>File</label>
              <input type="file" id="file" class="form-control" />
            </div>
            <button type="submit" class="btn btn-primary mt-3">Add</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit User Modal -->
  <div id="editUserModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="editUserForm" enctype="multipart/form-data">
            <div class="form-group">
              <label>Username</label>
              <input type="text" id="editUsername" class="form-control" required />
            </div>
            <div class="form-group">
              <label>Email</label>
              <input type="email" id="editEmail" class="form-control" required />
            </div>
            <div class="form-group">
              <label>Role</label>
              <input type="text" id="editRole" class="form-control" required />
            </div>
            <div class="form-group">
              <label>File</label>
              <input type="file" id="editFile" class="form-control" />
              <div id="currentFileLink"></div> <!-- Display current file link -->
            </div>
            <button type="submit" class="btn btn-primary mt-3">Update</button>
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
                <td>${user.id}</td>
                <td>${user.username}</td>
                <td>${user.email}</td>
                <td>${user.role || "N/A"}</td>
                <td><a href="${user.file_url}" target="_blank">View File</a></td>
                <td>
                  <button type="button" class="btn btn-outline-primary btn-sm edit-btn" 
                          data-id="${user.id}" 
                          data-username="${user.username}" 
                          data-email="${user.email}" 
                          data-role="${user.role}"
                          data-file="${user.file_url}">
                    Edit
                  </button>
                  <button type="button" class="btn btn-outline-danger btn-sm delete-btn" 
                          data-id="${user.id}">
                    Delete
                  </button>
                </td>
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
            tbody.innerHTML = "<tr><td colspan='6'>No users found.</td></tr>";
          }
        })
        .catch((error) => {
          console.error("Error loading users:", error);
        });
    }

    //!add
    document.getElementById("addUserForm").addEventListener("submit", function (e) {
      e.preventDefault();

      const formData = new FormData();
      formData.append("name", document.getElementById("username").value);
      formData.append("email", document.getElementById("email").value);
      formData.append("role", document.getElementById("role").value); 
      formData.append("password", "defaultPassword123"); 
      formData.append("file", document.getElementById("file").files[0]);


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
        const btn = e.target;
        document.getElementById("editUsername").value = btn.dataset.username;
        document.getElementById("editEmail").value = btn.dataset.email;
        document.getElementById("editRole").value = btn.dataset.role;
        document.getElementById("editUserForm").dataset.id = btn.dataset.id;
        const currentFileLink = btn.dataset.file ? `<a href="${btn.dataset.file}" target="_blank">View Current File</a>` : "No file uploaded";
        document.getElementById("currentFileLink").innerHTML = currentFileLink;
        $("#editUserModal").modal("show");
      }
    });

    //! Update User 
    document.getElementById("editUserForm").addEventListener("submit", function (e) {
      e.preventDefault();
      const userId = this.dataset.id;
      const formData = new FormData();
      formData.append("username", document.getElementById("editUsername").value);
      formData.append("email", document.getElementById("editEmail").value);
      formData.append("role", document.getElementById("editRole").value);
      if (document.getElementById("editFile").files[0]) {
        formData.append("file", document.getElementById("editFile").files[0]);
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

    //! Delete User functionality
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
