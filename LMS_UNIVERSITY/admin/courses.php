<?php
require_once '../config/database.php';
requireLogin();
if (!hasRole('admin')) {
    header('Location: ../auth/login.php');
    exit();
}
$database = new Database();
$db = $database->getConnection();

// Xử lý thêm/sửa/xóa
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Thêm khóa học
    if ($action === 'add') {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $instructor_id = $_POST['instructor_id'];
        $course_code = $_POST['course_code'];
        $credits = $_POST['credits'];
        $semester = $_POST['semester'];
        $year = $_POST['year'];
        $max_students = $_POST['max_students'];
        $sql = "INSERT INTO courses (title, description, instructor_id, course_code, credits, semester, year, max_students) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$title, $description, $instructor_id, $course_code, $credits, $semester, $year, $max_students]);
        $message = 'Course added successfully!';
        $action = '';
    }
    // Sửa khóa học
    if ($action === 'edit' && $id) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $instructor_id = $_POST['instructor_id'];
        $course_code = $_POST['course_code'];
        $credits = $_POST['credits'];
        $semester = $_POST['semester'];
        $year = $_POST['year'];
        $max_students = $_POST['max_students'];
        $sql = "UPDATE courses SET title=?, description=?, instructor_id=?, course_code=?, credits=?, semester=?, year=?, max_students=? WHERE id=?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$title, $description, $instructor_id, $course_code, $credits, $semester, $year, $max_students, $id]);
        $message = 'Course updated successfully!';
        $action = '';
    }
}
// Xóa khóa học
if ($action === 'delete' && $id) {
    $sql = "DELETE FROM courses WHERE id=?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$id]);
    $message = 'Course deleted successfully!';
    $action = '';
}

// Lấy danh sách giảng viên
$instructors = $db->query("SELECT id, first_name, last_name FROM users WHERE role='instructor'")->fetchAll(PDO::FETCH_ASSOC);

// Lấy thông tin khóa học nếu sửa
$edit_course = null;
if ($action === 'edit' && $id) {
    $stmt = $db->prepare("SELECT * FROM courses WHERE id=?");
    $stmt->execute([$id]);
    $edit_course = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Lấy danh sách khóa học
$courses = $db->query("SELECT c.*, u.first_name, u.last_name FROM courses c JOIN users u ON c.instructor_id = u.id ORDER BY c.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Management - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/dashboard.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/admin_navbar.php'; ?>
    <div class="container mt-4">
        <h2><i class="fas fa-book"></i> Course Management</h2>
        <?php if ($message): ?>
            <div class="alert alert-success"> <?= $message ?> </div>
        <?php endif; ?>
        <?php if ($action === 'add' || ($action === 'edit' && $edit_course)): ?>
            <form method="post" class="card p-3 mb-4">
                <div class="mb-2">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" required value="<?= $edit_course ? htmlspecialchars($edit_course['title']) : '' ?>">
                </div>
                <div class="mb-2">
                    <label>Course Code</label>
                    <input type="text" name="course_code" class="form-control" required value="<?= $edit_course ? htmlspecialchars($edit_course['course_code']) : '' ?>">
                </div>
                <div class="mb-2">
                    <label>Instructor</label>
                    <select name="instructor_id" class="form-control" required>
                        <option value="">-- Select Instructor --</option>
                        <?php foreach ($instructors as $ins): ?>
                            <option value="<?= $ins['id'] ?>" <?= $edit_course && $edit_course['instructor_id'] == $ins['id'] ? 'selected' : '' ?>><?= htmlspecialchars($ins['first_name'] . ' ' . $ins['last_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-2">
                    <label>Credits</label>
                    <input type="number" name="credits" class="form-control" value="<?= $edit_course ? htmlspecialchars($edit_course['credits']) : '3' ?>">
                </div>
                <div class="mb-2">
                    <label>Semester</label>
                    <input type="text" name="semester" class="form-control" value="<?= $edit_course ? htmlspecialchars($edit_course['semester']) : '' ?>">
                </div>
                <div class="mb-2">
                    <label>Year</label>
                    <input type="number" name="year" class="form-control" value="<?= $edit_course ? htmlspecialchars($edit_course['year']) : date('Y') ?>">
                </div>
                <div class="mb-2">
                    <label>Max Students</label>
                    <input type="number" name="max_students" class="form-control" value="<?= $edit_course ? htmlspecialchars($edit_course['max_students']) : '50' ?>">
                </div>
                <div class="mb-2">
                    <label>Description</label>
                    <textarea name="description" class="form-control"><?= $edit_course ? htmlspecialchars($edit_course['description']) : '' ?></textarea>
                </div>
                <button type="submit" class="btn btn-success">Save</button>
                <a href="courses.php" class="btn btn-secondary">Cancel</a>
            </form>
        <?php else: ?>
            <a href="courses.php?action=add" class="btn btn-success mb-3"><i class="fas fa-plus"></i> Add New Course</a>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Code</th>
                            <th>Instructor</th>
                            <th>Credits</th>
                            <th>Semester</th>
                            <th>Year</th>
                            <th>Max Students</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td><?= htmlspecialchars($course['title']) ?></td>
                                <td><?= htmlspecialchars($course['course_code']) ?></td>
                                <td><?= htmlspecialchars($course['first_name'] . ' ' . $course['last_name']) ?></td>
                                <td><?= htmlspecialchars($course['credits']) ?></td>
                                <td><?= htmlspecialchars($course['semester']) ?></td>
                                <td><?= htmlspecialchars($course['year']) ?></td>
                                <td><?= htmlspecialchars($course['max_students']) ?></td>
                                <td>
                                    <a href="courses.php?action=edit&id=<?= $course['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="courses.php?action=delete&id=<?= $course['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this course?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</body>
</html>
