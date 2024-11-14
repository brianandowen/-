<?php
require_once 'db.php';
require_once 'session.php';

// 處理表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $contact_info = mysqli_real_escape_string($conn, $_POST['contact_info']);
    $enrollment_year = mysqli_real_escape_string($conn, $_POST['enrollment_year']);
    $position = mysqli_real_escape_string($conn, $_POST['position']); // 選擇會員或幹部

    // 插入資料到資料庫
    $query = "INSERT INTO members (name, student_id, contact_info, enrollment_year, position) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $name, $student_id, $contact_info, $enrollment_year, $position);

    if ($stmt->execute()) {
        $message = "會員資料新增成功！";
    } else {
        $message = "新增失敗，請稍後再試。";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新增會員資料</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">新增會員資料</h2>
    
    <!-- 顯示成功或失敗訊息 -->
    <?php if (isset($message)): ?>
        <div class="alert <?= strpos($message, '成功') ? 'alert-success' : 'alert-danger' ?>" role="alert">
            <?= htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- 新增會員資料表單 -->
    <form action="insert.php" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">姓名</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="student_id" class="form-label">學號</label>
            <input type="text" id="student_id" name="student_id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="contact_info" class="form-label">聯絡方式</label>
            <input type="text" id="contact_info" name="contact_info" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="enrollment_year" class="form-label">入學年份</label>
            <input type="number" id="enrollment_year" name="enrollment_year" class="form-control" required min="2000" max="<?= date('Y'); ?>">
        </div>
        <div class="mb-3">
            <label for="position" class="form-label">職位</label>
            <select id="position" name="position" class="form-select" required>
                <option value="會員">會員</option>
                <option value="幹部">幹部</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary w-100">新增會員</button>
    </form>
    <a href="Query.php" class="btn btn-primary w-10 mt-3">回上一頁</a>

</div>
</body>
</html>
