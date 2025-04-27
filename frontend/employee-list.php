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
    <link rel="stylesheet" href="styles/employee-list.css?v=1.0.2" />


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
            <div class="table-responsive">
            <table id="employeeTable" class="table table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
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
                        <label for="editName">Name</label>
                        <input type="text" id="editName" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label for="editEmail">Email</label>
                        <input type="email" id="editEmail" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label for="editPosition">Position</label>
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
            const tbody = document.getElementById("employeeTableBody");
            tbody.innerHTML = "";

            if (employees && employees.length > 0) {
            employees.forEach(function (employee) {
                const row = document.createElement("tr");
                row.innerHTML = `
                <td>${employee.id}</td>
                <td>${employee.name}</td>
                <td>${employee.email}</td>
                <td>${employee.position}</td>
                <td>
                    <button type="button" class="btn btn-outline-primary btn-sm edit-btn" 
                            data-id="${employee.id}"
                            data-name="${employee.name}" 
                            data-email="${employee.email}" 
                            data-position="${employee.position}">
                    Edit
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm delete-btn" 
                            data-id="${employee.id}">
                    Delete
                    </button>
                </td>
                `;
                tbody.appendChild(row);
            });

            if (!$.fn.dataTable.isDataTable("#employeeTable")) {
                dataTable = $("#employeeTable").DataTable({
                responsive: true,
                autoWidth: false, 
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
            tbody.innerHTML = "<tr><td colspan='6'>No employees found.</td></tr>";
            }
        })
        .catch((error) => {
            console.error("Error loading employees:", error);
        });
    }

    // Add Employee
    document.getElementById("addEmployeeForm").addEventListener("submit", function (e) {
    e.preventDefault();
    const employeeData = {
        name: document.getElementById("name").value,
        email: document.getElementById("email").value,
        position: document.getElementById("position").value,
    };

    fetch(apiUrl, {
        method: "POST",
        headers: {
            Authorization: `Bearer ${token}`,
            "Content-Type": "application/json",
        },
        body: JSON.stringify(employeeData),
    })
    .then(response => response.json())
    .then(() => {
        fetchEmployees();
        $("#addEmployeeModal").modal("hide");
        Swal.fire({
            icon: 'success',
            title: 'Employee Added!',
            text: 'The employee has been successfully added.',
        });
    })
    .catch((error) => {
        console.error("Error adding employee:", error);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'There was an issue adding the employee.',
        });
    });
});


    // Edit Employee

document.addEventListener("click", function (e) {
    if (e.target.classList.contains("edit-btn")) {
        const employeeId = e.target.getAttribute("data-id");
        const employeeName = e.target.getAttribute("data-name");
        const employeeEmail = e.target.getAttribute("data-email");
        const employeePosition = e.target.getAttribute("data-position");


        document.getElementById("editName").value = employeeName;
        document.getElementById("editEmail").value = employeeEmail;
        document.getElementById("editPosition").value = employeePosition;
        document.getElementById("editEmployeeForm").setAttribute("data-id", employeeId);

        $("#editEmployeeModal").modal("show");
    }
});

// Handle form submission
document.getElementById("editEmployeeForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const employeeId = this.getAttribute("data-id");
    const employeeData = {
        name: document.getElementById("editName").value,
        email: document.getElementById("editEmail").value,
        position: document.getElementById("editPosition").value,
    };

    fetch(`${apiUrl}/${employeeId}`, {
        method: "PUT",
        headers: {
            Authorization: `Bearer ${token}`,
            "Content-Type": "application/json",
        },
        body: JSON.stringify(employeeData),
    })
        .then((response) => response.json())
        .then(() => {
            fetchEmployees();
            $("#editEmployeeModal").modal("hide");
            Swal.fire({
                icon: "success",
                title: "Employee Updated!",
                text: "The employee has been successfully updated.",
            });
        })
        .catch((error) => {
            console.error("Error updating employee:", error);
            Swal.fire({
                icon: "error",
                title: "Error!",
                text: "There was an issue updating the employee.",
            });
        });
});


    // Update Employee
    document
    .getElementById("editEmployeeForm")
    .addEventListener("submit", async function (e) {
        e.preventDefault();

        const employeeId = this.dataset.id;

        const employeeData = {
            name: document.getElementById("editName").value.trim(),
            email: document.getElementById("editEmail").value.trim(),
            position: document.getElementById("editPosition").value.trim(),
        };

        // Optional: Validate fields
        if (!employeeData.name || !employeeData.email || !employeeData.position) {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Fields',
                text: 'Please fill in all fields before submitting.',
            });
            return;
        }

        try {
            const response = await fetch(`${apiUrl}/${employeeId}`, {
                method: "PUT",
                headers: {
                    Authorization: `Bearer ${token}`,
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(employeeData),
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            fetchEmployees(); 
            $("#editEmployeeModal").modal("hide"); 

            Swal.fire({
                icon: 'success',
                title: 'Employee Updated!',
                text: 'The employee has been successfully updated.',
            });

        } catch (error) {
            console.error("Error updating employee:", error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'There was an issue updating the employee.',
            });
        }
    });


    // Delete Employee
    document.addEventListener("click", function (e) {
        if (e.target && e.target.classList.contains("delete-btn")) {
        const employeeId = e.target.dataset.id;
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
            fetch(`${apiUrl}/${employeeId}`, {
                method: "DELETE",
                headers: {
                Authorization: `Bearer ${token}`,
                },
            })
                .then(() => {
                fetchEmployees();
                Swal.fire(
                    'Deleted!',
                    'The employee has been deleted.',
                    'success'
                );
                })
                .catch((error) => {
                console.error("Error deleting employee:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'There was an issue deleting the employee.',
                });
                });
            }
        });
        }
    });

    fetchEmployees();
    });
</script>


</body>
</html>
