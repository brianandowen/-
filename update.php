<?php
require_once 'session.php';
require_once 'db.php';

// 顯示錯誤訊息以便調試
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 檢查是否為管理者
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'M') {
    header('Location: login.php'); // 如果未登入或非管理者，跳轉回登入頁面
    exit();
}

// 獲取要修改的資料ID
if (!isset($_GET['id'])) {
    die("未提供要修改的資料ID");
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

// 查詢要修改的資料
$query = "SELECT * FROM members WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("找不到要修改的會員資料");
}

$member = $result->fetch_assoc(); // 獲取當前會員的資料內容

// 處理表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 獲取更新後的資料
    $name = $_POST['name'];
    $student_id = $_POST['student_id'];
    $contact_info = $_POST['contact_info'];
    $enrollment_year = $_POST['enrollment_year'];
    $position = $_POST['position'];

    // 更新資料庫中的會員資料
    $update_query = "UPDATE members SET name = ?, student_id = ?, contact_info = ?, enrollment_year = ?, position = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sssssi", $name, $student_id, $contact_info, $enrollment_year, $position, $id);

    if ($stmt->execute()) {
        $message = "會員資料更新成功！";
    } else {
        $message = "更新失敗，請稍後再試。";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>修改會員資料</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">修改會員資料</h2>

    <!-- 顯示成功或失敗訊息 -->
    <?php if (isset($message)): ?>
        <div class="alert <?= strpos($message, '成功') ? 'alert-success' : 'alert-danger' ?>" role="alert">
            <?= htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- 修改會員資料表單，預填現有資料 -->
    <form action="update.php?id=<?= urlencode($id) ?>" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">姓名</label>
            <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($member['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="student_id" class="form-label">學號</label>
            <input type="text" id="student_id" name="student_id" class="form-control" value="<?= htmlspecialchars($member['student_id']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="contact_info" class="form-label">聯絡方式</label>
            <input type="text" id="contact_info" name="contact_info" class="form-control" value="<?= htmlspecialchars($member['contact_info']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="enrollment_year" class="form-label">入學年份</label>
            <input type="number" id="enrollment_year" name="enrollment_year" class="form-control" value="<?= htmlspecialchars($member['enrollment_year']) ?>" required min="2000" max="<?= date('Y'); ?>">
        </div>
        <div class="mb-3">
            <label for="position" class="form-label">職位</label>
            <select id="position" name="position" class="form-select" required>
                <option value="會員" <?= $member['position'] === '會員' ? 'selected' : '' ?>>會員</option>
                <option value="幹部" <?= $member['position'] === '幹部' ? 'selected' : '' ?>>幹部</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary w-100">更新會員資料</button>
    </form>
    <a href="Query.php" class="btn btn-primary mt-3">回上一頁</a>
</div>
</body>
</html>
