<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student List</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"/>
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css"/>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="styles/student-list.css?v=1.0.1" />

</head>
<body>
  <?php include 'partials/sidebar.php'; ?>

  <div class="main-content">
    <div class="container mt-5">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Student List</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
          + Add Student
        </button>
      </div>
      <table id="studentTable" class="table table-hover table-bordered">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Age</th>
            <th>Email</th>
            <th>Course</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="studentTableBody">
        </tbody>
      </table>
    </div>
  </div>

  <!-- Add Student Modal -->
  <div id="addStudentModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Student</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="addStudentForm">
            <div class="form-group">
              <label>Full Name</label>
              <input type="text" id="name" class="form-control" required />
            </div>
            <div class="form-group">
              <label>Age</label>
              <input type="number" id="age" class="form-control" required />
            </div>
            <div class="form-group">
              <label>Email</label>
              <input type="email" id="email" class="form-control" required />
            </div>
            <div class="form-group">
              <label>Course</label>
              <input type="text" id="course" class="form-control" />
            </div>
            <button type="submit" class="btn btn-primary mt-3">Add</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Student Modal -->
  <div id="editStudentModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Student</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="editStudentForm">
            <div class="form-group">
              <label>Full Name</label>
              <input type="text" id="editName" class="form-control" required />
            </div>
            <div class="form-group">
              <label>Age</label>
              <input type="number" id="editAge" class="form-control" required />
            </div>
            <div class="form-group">
              <label>Email</label>
              <input type="email" id="editEmail" class="form-control" required />
            </div>
            <div class="form-group">
              <label>Course</label>
              <input type="text" id="editCourse" class="form-control" />
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

    const apiUrl = "http://127.0.0.1:8000/api/students";
    let dataTable;

    function fetchStudents() {
      fetch(apiUrl, {
        method: "GET",
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
        .then((response) => response.json())
        .then((data) => {
          const students = data.data || data;
          const tbody = document.getElementById("studentTableBody");
          tbody.innerHTML = "";

          if (students && students.length > 0) {
            students.forEach(function (student) {
              const row = document.createElement("tr");
              row.innerHTML = `
                <td>${student.id}</td>
                <td>${student.name}</td>
                <td>${student.age}</td>
                <td>${student.email}</td>
                <td>${student.course || "N/A"}</td>
                <td>
                  <button type="button" class="btn btn-outline-primary btn-sm edit-btn" 
                          data-id="${student.id}"
                          data-name="${student.name}" 
                          data-age="${student.age}" 
                          data-email="${student.email}" 
                          data-course="${student.course}">
                    Edit
                  </button>
                  <button type="button" class="btn btn-outline-danger btn-sm delete-btn" 
                          data-id="${student.id}">
                    Delete
                  </button>
                </td>
              `;
              tbody.appendChild(row);
            });

            if (!$.fn.dataTable.isDataTable("#studentTable")) {
              dataTable = $("#studentTable").DataTable({
                responsive: true,
                autoWidth: false, // Important to prevent fixed column width issues
                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
                columnDefs: [{ orderable: false, targets: 0 }],
              });
            } else {
              dataTable.clear().rows.add(tbody.querySelectorAll("tr")).draw();
            }
          } else {
            tbody.innerHTML = "<tr><td colspan='6'>No students found.</td></tr>";
          }
        })
        .catch((error) => {
          console.error("Error loading students:", error);
        });
    }

    // Add Student
    document
      .getElementById("addStudentForm")
      .addEventListener("submit", function (e) {
        e.preventDefault();
        const studentData = {
          name: document.getElementById("name").value,
          age: document.getElementById("age").value,
          email: document.getElementById("email").value,
          course: document.getElementById("course").value,
        };

        fetch(apiUrl, {
          method: "POST",
          headers: {
            Authorization: `Bearer ${token}`,
            "Content-Type": "application/json",
          },
          body: JSON.stringify(studentData),
        })
          .then(() => {
            fetchStudents();
            $("#addStudentModal").modal("hide");
            Swal.fire({
              icon: 'success',
              title: 'Student Added!',
              text: 'The student has been successfully added.',
            });
          })
          .catch((error) => {
            console.error("Error adding student:", error);
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: 'There was an issue adding the student.',
            });
          });
      });

    // Edit Student
    document.addEventListener("click", function (e) {
      if (e.target && e.target.classList.contains("edit-btn")) {
        const btn = e.target;
        document.getElementById("editName").value = btn.dataset.name;
        document.getElementById("editAge").value = btn.dataset.age;
        document.getElementById("editEmail").value = btn.dataset.email;
        document.getElementById("editCourse").value = btn.dataset.course;
        document.getElementById("editStudentForm").dataset.id = btn.dataset.id;
        $("#editStudentModal").modal("show");
      }
    });

    // Update Student
    document
      .getElementById("editStudentForm")
      .addEventListener("submit", function (e) {
        e.preventDefault();
        const studentId = this.dataset.id;
        const studentData = {
          name: document.getElementById("editName").value,
          age: document.getElementById("editAge").value,
          email: document.getElementById("editEmail").value,
          course: document.getElementById("editCourse").value,
        };

        fetch(`${apiUrl}/${studentId}`, {
          method: "PUT",
          headers: {
            Authorization: `Bearer ${token}`,
            "Content-Type": "application/json",
          },
          body: JSON.stringify(studentData),
        })
          .then(() => {
            fetchStudents();
            $("#editStudentModal").modal("hide");
            Swal.fire({
              icon: 'success',
              title: 'Student Updated!',
              text: 'The student has been successfully updated.',
            });
          })
          .catch((error) => {
            console.error("Error updating student:", error);
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: 'There was an issue updating the student.',
            });
          });
      });

    // Delete Student
    document.addEventListener("click", function (e) {
      if (e.target && e.target.classList.contains("delete-btn")) {
        const studentId = e.target.dataset.id;
        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!',
        }).then((result) => {
          if (result.isConfirmed) {
            fetch(`${apiUrl}/${studentId}`, {
              method: "DELETE",
              headers: {
                Authorization: `Bearer ${token}`,
              },
            })
              .then(() => {
                fetchStudents();
                Swal.fire(
                  'Deleted!',
                  'The student has been deleted.',
                  'success'
                );
              })
              .catch((error) => {
                console.error("Error deleting student:", error);
                Swal.fire({
                  icon: 'error',
                  title: 'Error!',
                  text: 'There was an issue deleting the student.',
                });
              });
          }
        });
      }
    });

    fetchStudents();
  });
</script>


</body>
</html>
