<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Document</title>
    <style>
        body {
            background: #eaeef6;
            font-family: 'Open Sans', sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Sidebar styling */
        .navbar {
            position: fixed;
            top: 1rem;
            left: 1rem;
            background: #fff;
            border-radius: 15px;
            padding: 1rem 0;
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.05);
            height: calc(100vh - 2rem);
            width: 270px; /* Width of the sidebar */
            overflow: hidden;
        }

        /* Title styling */
        h1 {
            margin-left: 290px; /* Move the heading beside the sidebar */
            padding: 20px;
            font-size: 40px;
            color: #406ff3; /* Title color */
        }

        /* Table styling */
        table {
            width: calc(100% - 290px); /* Adjust to take up space next to the sidebar */
            margin-left: 290px; /* Leave space beside the sidebar */
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
            background-color: #406ff3; /* Light blue hover color */
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

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

        .button-container {
            margin-left: 290px;
            padding: 20px;
            display: flex;
            justify-content: flex-end;
        }
        .custom-modal {
  display: none;
  position: fixed;
  z-index: 9999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.5);
}

.custom-modal-content {
  background-color: white;
  margin: 8% auto;
  padding: 20px;
  border-radius: 10px;
  width: 500px;
  font-family: Arial, sans-serif;
}

.custom-modal-content h2 {
  margin-top: 0;
  font-size: 18px;
}

.modal-form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 8px 12px;
  margin-top: 10px;
}

.modal-form-grid label {
  font-size: 13px;
  display: block;
  margin-bottom: 4px;
}

.modal-form-grid input {
  font-size: 13px;
  padding: 4px;
  height: 30px;
  width: 100%;
  box-sizing: border-box;
}

.modal-buttons {
  margin-top: 20px;
  text-align: right;
}

.modal-buttons button {
  padding: 6px 12px;
  font-size: 13px;
  margin-left: 10px;
}

    </style>
</head>

<body>
<?php include 'sidebar.php'?>

<h1>Student Grades</h1>

<!-- Create Grade Button at the top of the action column -->
<div class="button-container">
  <button onclick="createGrade()">Create Grade</button>
</div>

<table id="gradesTable">
  <thead>
    <tr>
      <th>ID</th>
      <th>Science</th>
      <th>History</th>
      <th>Math</th>
      <th>English</th>
      <th>PE</th>
      <th>Arts</th>
      <th>Music</th>
      <th>Foreign Language</th>
      <th>Social Studies</th>
      <th>Technology/ICT</th>
      <th>Total</th> <!-- New column for Total -->
      <th>Status</th> <!-- New column for Status -->
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <!-- Data will be inserted here by fetch -->
  </tbody>
</table>


<script>
document.addEventListener("DOMContentLoaded", function () {
  const token = localStorage.getItem("token");

  if (!token) {
    window.location.href = "login.php";
    return;
  }

  loadSubjects();
});
function loadSubjects() {
  const token = localStorage.getItem('token');

  fetch('http://127.0.0.1:8000/api/subjects', {
    method: 'GET',
    headers: {
      'Authorization': `Bearer ${token}`,
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    }
  })
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then(data => {
      const tableBody = document.querySelector('#gradesTable tbody');
      tableBody.innerHTML = ''; // Clear any previous table rows

      data.forEach(item => {
        const grades = [
          item.Science,
          item.History,
          item.Math,
          item.English,
          item.PE,
          item.Arts,
          item.Music,
          item['Foreign Language'],
          item['Social Studies'],
          item['Technology/ICT']
        ];

        // Convert and filter valid grades
        const validGrades = grades
          .map(grade => parseFloat(grade))
          .filter(grade => !isNaN(grade));

        const sumOfGrades = validGrades.reduce((sum, grade) => sum + grade, 0);
        const subjectCount = validGrades.length;

        // Correct formula: Total = sum / count
        const totalGrade = subjectCount > 0 ? sumOfGrades / subjectCount : 0;

        const status = totalGrade >= 75 ? 'Passed' : 'Fail';

        const row = document.createElement('tr');
        row.innerHTML = `
          <td>${item.id}</td>
          <td>${item.Science}</td>
          <td>${item.History}</td>
          <td>${item.Math}</td>
          <td>${item.English}</td>
          <td>${item.PE}</td>
          <td>${item.Arts}</td>
          <td>${item.Music}</td>
          <td>${item['Foreign Language']}</td>
          <td>${item['Social Studies']}</td>
          <td>${item['Technology/ICT']}</td>
          <td>${totalGrade.toFixed(2)}</td>
          <td style="color: ${status === 'Passed' ? 'green' : 'red'}; font-weight: bold;">${status}</td>
          <td>
    <button onclick="editSubject(${item.id})" style="background-color: #3498db; color: white; border: none; padding: 5px 10px; border-radius: 4px;">Edit</button>
    <button onclick="deleteSubject(${item.id})" style="background-color: #e74c3c; color: white; border: none; padding: 5px 10px; border-radius: 4px;">Delete</button>
</td>

        `;
        tableBody.appendChild(row);
      });
    })
    .catch(error => {
      console.error('There was a problem fetching the subjects:', error);
    });
}




function editSubject(id) {
  const token = localStorage.getItem('token');

  fetch(`http://127.0.0.1:8000/api/subjects/${id}`, {
    method: 'GET',
    headers: {
      'Authorization': `Bearer ${token}`,
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    }
  })
    .then(response => response.json())
    .then(response => {
      const subject = response.data;

      Swal.fire({
        title: `Edit Grades for ${subject.name}`,
        html: `
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px 12px;">
            <div>
              <label for="science" class="swal2-label" style="font-size: 13px;">Science</label>
              <input id="science" class="swal2-input" style="font-size: 13px; padding: 4px; height: 30px;" value="${subject.Science}">
            </div>
            <div>
              <label for="arts" class="swal2-label" style="font-size: 13px;">Arts</label>
              <input id="arts" class="swal2-input" style="font-size: 13px; padding: 4px; height: 30px;" value="${subject.Arts}">
            </div>
            <div>
              <label for="history" class="swal2-label" style="font-size: 13px;">History</label>
              <input id="history" class="swal2-input" style="font-size: 13px; padding: 4px; height: 30px;" value="${subject.History}">
            </div>
            <div>
              <label for="music" class="swal2-label" style="font-size: 13px;">Music</label>
              <input id="music" class="swal2-input" style="font-size: 13px; padding: 4px; height: 30px;" value="${subject.Music}">
            </div>
            <div>
              <label for="math" class="swal2-label" style="font-size: 13px;">Math</label>
              <input id="math" class="swal2-input" style="font-size: 13px; padding: 4px; height: 30px;" value="${subject.Math}">
            </div>
            <div>
              <label for="foreign_language" class="swal2-label" style="font-size: 13px;">Foreign Language</label>
              <input id="foreign_language" class="swal2-input" style="font-size: 13px; padding: 4px; height: 30px;" value="${subject['Foreign Language']}">
            </div>
            <div>
              <label for="english" class="swal2-label" style="font-size: 13px;">English</label>
              <input id="english" class="swal2-input" style="font-size: 13px; padding: 4px; height: 30px;" value="${subject.English}">
            </div>
            <div>
              <label for="social_studies" class="swal2-label" style="font-size: 13px;">Social Studies</label>
              <input id="social_studies" class="swal2-input" style="font-size: 13px; padding: 4px; height: 30px;" value="${subject['Social Studies']}">
            </div>
            <div>
              <label for="pe" class="swal2-label" style="font-size: 13px;">PE</label>
              <input id="pe" class="swal2-input" style="font-size: 13px; padding: 4px; height: 30px;" value="${subject.PE}">
            </div>
            <div>
              <label for="technology_ict" class="swal2-label" style="font-size: 13px;">Technology/ICT</label>
              <input id="technology_ict" class="swal2-input" style="font-size: 13px; padding: 4px; height: 30px;" value="${subject['Technology/ICT']}">
            </div>
          </div>
        `,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: 'Update',
        width: '500px',
        preConfirm: () => {
          // Collect input values and parse as numbers (ensure values are correctly parsed)
          const grades = {
            Science: parseFloat(document.getElementById('science').value) || 0,
            Arts: parseFloat(document.getElementById('arts').value) || 0,
            History: parseFloat(document.getElementById('history').value) || 0,
            Music: parseFloat(document.getElementById('music').value) || 0,
            Math: parseFloat(document.getElementById('math').value) || 0,
            'Foreign Language': parseFloat(document.getElementById('foreign_language').value) || 0,
            English: parseFloat(document.getElementById('english').value) || 0,
            'Social Studies': parseFloat(document.getElementById('social_studies').value) || 0,
            PE: parseFloat(document.getElementById('pe').value) || 0,
            'Technology/ICT': parseFloat(document.getElementById('technology_ict').value) || 0
          };

          // Sum the grades and calculate the total score
          const total = Object.values(grades).reduce((sum, grade) => sum + grade, 0);

          // Determine pass/fail based on total (example threshold: 75)
          const status = total >= 75 ? 'Passed' : 'Fail';

          // If any grade is not a valid number, show an error
          if (Object.values(grades).some(grade => isNaN(grade))) {
            Swal.showValidationMessage('All grades must be valid numbers.');
            return false;
          }

          return {
            ...grades,
            Total: total,
            Status: status
          };
        }
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(`http://127.0.0.1:8000/api/subjects/${id}`, {
            method: 'PUT',
            headers: {
              'Authorization': `Bearer ${token}`,
              'Accept': 'application/json',
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(result.value)
          })
            .then(response => response.json())
            .then(response => {
              if (response.status === 'success') {
                Swal.fire('Updated!', '', 'success');
                loadSubjects(); // Reload the list of subjects
              }
            })
            .catch(error => {
              console.error('Error updating subject:', error);
            });
        }
      });
    });
}



// 🗑️ DELETE SUBJECT (Using SweetAlert)
function deleteSubject(id) {
  const token = localStorage.getItem('token');

  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'Cancel'
  }).then((result) => {
    if (result.isConfirmed) {
      fetch(`http://127.0.0.1:8000/api/subjects/${id}`, {
        method: 'DELETE',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        }
      })
        .then(response => {
          if (!response.ok) {
            throw new Error('Failed to delete subject');
          }
          Swal.fire('Deleted!', 'The subject has been deleted.', 'success');
          loadSubjects();
        })
        .catch(error => {
          console.error('Error deleting subject:', error);
          Swal.fire('Error', 'Failed to delete subject.', 'error');
        });
    }
  });
}

function createGrade() {
  Swal.fire({
    title: 'Add New Grade',
    html: `
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px 12px;">
        <div>
          <label for="science" class="swal2-label" style="font-size: 13px;">Science</label>
          <input id="science" class="swal2-input" style="font-size: 13px; padding: 4px; height: 30px;">
        </div>
        <div>
          <label for="history" class="swal2-label" style="font-size: 13px;">History</label>
          <input id="history" class="swal2-input" style="font-size: 13px; padding: 4px; height: 30px;">
        </div>
        <div>
          <label for="math" class="swal2-label" style="font-size: 13px;">Math</label>
          <input id="math" class="swal2-input" style="font-size: 13px; padding: 4px; height: 30px;">
        </div>
        <div>
          <label for="english" class="swal2-label" style="font-size: 13px;">English</label>
          <input id="english" class="swal2-input" style="font-size: 13px; padding: 4px; height: 30px;">
        </div>
        <div>
          <label for="pe" class="swal2-label" style="font-size: 13px;">PE</label>
          <input id="pe" class="swal2-input" style="font-size: 13px; padding: 4px; height: 30px;">
        </div>
        <div>
          <label for="arts" class="swal2-label" style="font-size: 13px;">Arts</label>
          <input id="arts" class="swal2-input" style="font-size: 13px; padding: 4px; height: 30px;">
        </div>
        <div>
          <label for="music" class="swal2-label" style="font-size: 13px;">Music</label>
          <input id="music" class="swal2-input" style="font-size: 13px; padding: 4px; height: 30px;">
        </div>
        <div>
          <label for="foreign_language" class="swal2-label" style="font-size: 13px;">Foreign Language</label>
          <input id="foreign_language" class="swal2-input" style="font-size: 13px; padding: 4px; height: 30px;">
        </div>
        <div>
          <label for="social_studies" class="swal2-label" style="font-size: 13px;">Social Studies</label>
          <input id="social_studies" class="swal2-input" style="font-size: 13px; padding: 4px; height: 30px;">
        </div>
        <div>
          <label for="technology_ict" class="swal2-label" style="font-size: 13px;">Technology/ICT</label>
          <input id="technology_ict" class="swal2-input" style="font-size: 13px; padding: 4px; height: 30px;">
        </div>
      </div>
    `,
    showCancelButton: true,
    confirmButtonText: 'Create',
    width: '500px', // smaller modal
    preConfirm: () => {
          // Collect input values
          let science = parseFloat(document.getElementById('science').value);
          let arts = parseFloat(document.getElementById('arts').value);
          let history = parseFloat(document.getElementById('history').value);
          let music = parseFloat(document.getElementById('music').value);
          let math = parseFloat(document.getElementById('math').value);
          let foreign_language = parseFloat(document.getElementById('foreign_language').value);
          let english = parseFloat(document.getElementById('english').value);
          let social_studies = parseFloat(document.getElementById('social_studies').value);
          let pe = parseFloat(document.getElementById('pe').value);
          let technology_ict = parseFloat(document.getElementById('technology_ict').value);

          // Validate grades to be between 60 and 100
          const validGrade = (grade) => grade >= 60 && grade <= 100;

          if (
            !validGrade(science) || !validGrade(arts) || !validGrade(history) || !validGrade(music) ||
            !validGrade(math) || !validGrade(foreign_language) || !validGrade(english) ||
            !validGrade(social_studies) || !validGrade(pe) || !validGrade(technology_ict)
          ) {
            Swal.showValidationMessage('Grades must be between 60 and 100.');
            return false;
          }

          return {
            Science: science,
            Arts: arts,
            History: history,
            Music: music,
            Math: math,
            'Foreign Language': foreign_language,
            English: english,
            'Social Studies': social_studies,
            PE: pe,
            'Technology/ICT': technology_ict,
          };
        }
  }).then((result) => {
    if (result.isConfirmed) {
      const token = localStorage.getItem('token');
      fetch('http://127.0.0.1:8000/api/subjects', {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(result.value)
      })
      .then(response => {
        if (!response.ok) throw new Error('Failed to create grade');
        return response.json();
      })
      .then(() => {
        Swal.fire('Success', 'Grade created successfully', 'success');
        loadSubjects();
      })
      .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Failed to create grade', 'error');
      });
    }
  });
}

</script>
</body>
</html>