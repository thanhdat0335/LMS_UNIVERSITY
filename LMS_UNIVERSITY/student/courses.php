<?php
require_once '../config/database.php';
requireLogin();
if (!hasRole('student')) {
    header('Location: ../auth/login.php');
    exit();
}
$database = new Database();
$db = $database->getConnection();
$user_id = $_SESSION['user_id'];
$message = '';

// Đăng ký khóa học
if (isset($_POST['enroll_course_id'])) {
    $course_id = (int)$_POST['enroll_course_id'];
    // Kiểm tra đã đăng ký chưa
    $stmt = $db->prepare("SELECT * FROM enrollments WHERE student_id=? AND course_id=?");
    $stmt->execute([$user_id, $course_id]);
    if ($stmt->fetch()) {
        $message = '<div class="alert alert-warning">You have already enrolled in this course.</div>';
    } else {
        $stmt = $db->prepare("INSERT INTO enrollments (student_id, course_id, status) VALUES (?, ?, 'enrolled')");
        $stmt->execute([$user_id, $course_id]);
        $message = '<div class="alert alert-success">Enrollment successful!</div>';
    }
}

// Lấy danh sách khóa học chưa đăng ký
$query = "SELECT c.*, u.first_name, u.last_name FROM courses c JOIN users u ON c.instructor_id = u.id WHERE c.id NOT IN (SELECT course_id FROM enrollments WHERE student_id=?) AND c.status='active' ORDER BY c.created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute([$user_id]);
$available_courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách khóa học đã đăng ký
$query = "SELECT c.*, u.first_name, u.last_name, e.status FROM courses c JOIN users u ON c.instructor_id = u.id JOIN enrollments e ON c.id = e.course_id WHERE e.student_id=? ORDER BY e.enrollment_date DESC";
$stmt = $db->prepare($query);
$stmt->execute([$user_id]);
$enrolled_courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Registration - Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/dashboard.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/student_navbar.php'; ?>
    <div class="container mt-4">
        <h2><i class="fas fa-book"></i> Course Registration</h2>
        <?= $message ?>
        <h4>Available Courses</h4>
        <div class="table-responsive mb-4">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Code</th>
                        <th>Instructor</th>
                        <th>Credits</th>
                        <th>Semester</th>
                        <th>Year</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($available_courses as $course): ?>
                        <tr>
                            <td><?= htmlspecialchars($course['title']) ?></td>
                            <td><?= htmlspecialchars($course['course_code']) ?></td>
                            <td><?= htmlspecialchars($course['first_name'] . ' ' . $course['last_name']) ?></td>
                            <td><?= htmlspecialchars($course['credits']) ?></td>
                            <td><?= htmlspecialchars($course['semester']) ?></td>
                            <td><?= htmlspecialchars($course['year']) ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="enroll_course_id" value="<?= $course['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-success">Enroll</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <h4>My Courses</h4>
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
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($enrolled_courses as $course): ?>
                        <tr>
                            <td><?= htmlspecialchars($course['title']) ?></td>
                            <td><?= htmlspecialchars($course['course_code']) ?></td>
                            <td><?= htmlspecialchars($course['first_name'] . ' ' . $course['last_name']) ?></td>
                            <td><?= htmlspecialchars($course['credits']) ?></td>
                            <td><?= htmlspecialchars($course['semester']) ?></td>
                            <td><?= htmlspecialchars($course['year']) ?></td>
                            <td><span class="badge bg-info"><?= htmlspecialchars($course['status']) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</body>
</html>
