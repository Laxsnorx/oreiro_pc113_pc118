<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Employee List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
    <?php include 'partials/sidebar.php'; ?>

    <div class="main-content">
        <div class="container mt-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Employee List</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                    + Create Employee
                </button>
            </div>
            <table id="employeeTable" class="table table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Employee ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Position</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="employeeTableBody"></tbody>
            </table>
        </div>
    </div>

    <!-- Add Employee Modal -->
    <div id="addEmployeeModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addEmployeeForm">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" id="name" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" id="email" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label>Position</label>
                            <input type="text" id="position" class="form-control" required />
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Employee Modal -->
<div id="editEmployeeModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editEmployeeForm">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" id="editName" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="editEmail" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label>Position</label>
                        <input type="text" id="editPosition" class="form-control" required />
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

        const apiUrl = "http://127.0.0.1:8000/api/employees";
        let dataTable;

        function fetchEmployees() {
            fetch(apiUrl, {
                method: "GET",
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            })
            .then((response) => response.json())
            .then((data) => {
                const employees = data.data || data;
                const formattedRows = employees.map((employee) => {
                    const actionButtons = `
                        <button type="button" class="btn btn-outline-primary btn-sm edit-btn" 
                                data-id="${employee.id}"
                                data-name="${employee.name}" 
                                data-position="${employee.position}" 
                                data-email="${employee.email}">
                            Edit
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm delete-btn" 
                                data-id="${employee.id}">
                            Delete
                        </button>
                    `;
                    return [
                        employee.id,
                        employee.name,
                        employee.email,
                        employee.position,
                        actionButtons,
                    ];
                });

                if (!dataTable) {
                    dataTable = $('#employeeTable').DataTable({
                        responsive: true,
                        data: formattedRows,
                        columns: [
                            { title: "Employee ID" },
                            { title: "Full Name" },
                            { title: "Email" },
                            { title: "Position" },
                            { title: "Actions", orderable: false }
                        ]
                    });
                } else {
                    dataTable.clear().rows.add(formattedRows).draw();
                }
            })
            .catch((error) => console.error("Error loading employees:", error));
        }

        document.getElementById("addEmployeeForm").addEventListener("submit", function (e) {
            e.preventDefault();
            const name = document.getElementById("name").value;
            const position = document.getElementById("position").value;
            const email = document.getElementById("email").value;

            const employeeData = { name, position, email };

            fetch(apiUrl, {
                method: "POST",
                headers: {
                    Authorization: `Bearer ${token}`,
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(employeeData),
            })
            .then(() => {
                fetchEmployees();
                $("#addEmployeeModal").modal("hide");
                this.reset();
                Swal.fire({
                    icon: 'success',
                    title: 'Created!',
                    text: 'Employee has been added successfully.',
                    timer: 2000,
                    showConfirmButton: false
                });
            })
            .catch((error) => console.error("Error adding employee:", error));
        });

        document.addEventListener("click", function (e) {
            if (e.target && e.target.classList.contains("edit-btn")) {
                const btn = e.target;
                document.getElementById("editName").value = btn.dataset.name;
                document.getElementById("editEmail").value = btn.dataset.email;
                document.getElementById("editPosition").value = btn.dataset.position;
                document.getElementById("editEmployeeForm").dataset.id = btn.dataset.id;
                $("#editEmployeeModal").modal("show");
            }
        });

        document.getElementById("editEmployeeForm").addEventListener("submit", function (e) {
            e.preventDefault();
            const employeeId = this.dataset.id;
            const name = document.getElementById("editName").value;
            const email = document.getElementById("editEmail").value;
            const position = document.getElementById("editPosition").value;

            const employeeData = { name, position, email };

            fetch(`${apiUrl}/${employeeId}`, {
                method: "PUT",
                headers: {
                    Authorization: `Bearer ${token}`,
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(employeeData),
            })
            .then(() => {
                fetchEmployees();
                $("#editEmployeeModal").modal("hide");
                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: 'Employee information updated successfully.',
                    timer: 2000,
                    showConfirmButton: false
                });
            })
            .catch((error) => console.error("Error updating employee:", error));
        });

        document.addEventListener("click", function (e) {
            if (e.target && e.target.classList.contains("delete-btn")) {
                const employeeId = e.target.dataset.id;
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`${apiUrl}/${employeeId}`, {
                            method: "DELETE",
                            headers: {
                                Authorization: `Bearer ${token}`,
                            },
                        })
                        .then(() => {
                            fetchEmployees();
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Employee has been removed.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        })
                        .catch((error) => console.error("Error deleting employee:", error));
                    }
                });
            }
        });

        fetchEmployees(); // Load initial data
    });
</script>


</body>
</html>
