<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Enrollments</title>
	<style>
		body {
			font-family: 'Segoe UI', Arial, sans-serif;
			background: #f7f8fa;
			margin: 0;
			padding: 32px;
		}
		.container {
			max-width: 1100px;
			margin: 0 auto;
		}
		h1 {
			display: flex;
			align-items: center;
			font-size: 2rem;
			margin-bottom: 16px;
		}
		h1 .icon {
			margin-right: 8px;
		}
		.card {
			background: #fff;
			border-radius: 12px;
			box-shadow: 0 2px 8px rgba(0,0,0,0.07);
			padding: 24px;
			margin-top: 24px;
		}
		table {
			width: 100%;
			border-collapse: collapse;
			margin-bottom: 24px;
		}
		thead th {
			color: #888;
			font-weight: 500;
			border-bottom: 2px solid #eee;
			text-align: left;
			padding: 8px;
		}
		tbody td {
			padding: 8px;
			border-bottom: 1px solid #eee;
		}
		.actions {
			text-align: center;
		}
		.delete-btn {
			background: none;
			border: none;
			cursor: pointer;
			padding: 0;
		}
		.delete-btn svg {
			vertical-align: middle;
		}
		.delete-btn:hover svg rect,
		.delete-btn:hover svg path,
		.delete-btn:hover svg {
			stroke: #b71c1c;
			fill: #ffcdd2;
		}
		.section-title {
			font-weight: 600;
			margin-bottom: 16px;
		}
		.add-title {
			font-weight: 500;
			margin-top: 24px;
		}
		.add-desc {
			color: #888;
			margin-bottom: 8px;
		}
		select {
			padding: 8px;
			font-size: 1rem;
			border-radius: 6px;
			border: 1px solid #ccc;
			margin-bottom: 24px;
			width: 350px;
		}
	</style>
</head>
<body>
<div class="container">
	<h1><span class="icon">👤+</span>Enrollments</h1>
	<select id="course-select">
		<option value="1">AI Genesis</option>
		<!-- Thêm các khóa học khác nếu cần -->
	</select>
	<div class="card">
		<div class="section-title">Enrolled Students</div>
		<table id="enrollment-table">
			<thead>
				<tr>
					<th>ID</th>
					<th>NAME</th>
					<th>EMAIL</th>
					<th>ENROLLED</th>
					<th class="actions">ACTIONS</th>
				</tr>
			</thead>
			<tbody>
				<!-- Dữ liệu sẽ được render bằng JS -->
			</tbody>
		</table>
		<div class="add-title">Add Student</div>
		<div class="add-desc">All students already enrolled.</div>
	</div>
</div>
<script>
// Dữ liệu mẫu, bạn có thể thay bằng fetch từ API
const enrollmentsData = {
  1: [
	{ id: 3, name: 'DJ VE', email: 'student5@dehe.edu', registered_at: 'Aug 20, 2025 12:54 PM' },
	{ id: 5, name: 'TRRFD DGS', email: 'student7@dehe.edu', registered_at: 'Aug 22, 2025 4:51 PM' }
  ]
  // Thêm dữ liệu cho các khóa học khác nếu cần
};

function renderTable(courseId) {
  const tbody = document.querySelector('#enrollment-table tbody');
  tbody.innerHTML = '';
  const students = enrollmentsData[courseId] || [];
  students.forEach(student => {
	const tr = document.createElement('tr');
	tr.innerHTML = `
	  <td>${student.id}</td>
	  <td>${student.name}</td>
	  <td>${student.email}</td>
	  <td>${student.registered_at}</td>
	  <td class="actions">
		<button class="delete-btn" title="Delete" onclick="deleteStudent(${courseId}, ${student.id})">
		  <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
			<rect x="6" y="7" width="12" height="12" rx="2" fill="#fff" stroke="#d32f2f" stroke-width="2"/>
			<path d="M9 10v6M12 10v6M15 10v6" stroke="#d32f2f" stroke-width="2" stroke-linecap="round"/>
			<rect x="9" y="4" width="6" height="2" rx="1" fill="#d32f2f"/>
		  </svg>
		</button>
	  </td>
	`;
	tbody.appendChild(tr);
  });
}

function deleteStudent(courseId, studentId) {
  if (confirm('Bạn có chắc muốn xóa?')) {
	enrollmentsData[courseId] = enrollmentsData[courseId].filter(s => s.id !== studentId);
	renderTable(courseId);
  }
}

document.getElementById('course-select').addEventListener('change', function() {
  renderTable(this.value);
});

// Khởi tạo bảng với khóa học đầu tiên
renderTable(document.getElementById('course-select').value);
</script>
</body>
</html>
