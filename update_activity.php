<?php
session_start();
require_once 'session.php';
require_once 'db.php';

// 確保使用者已登入並且是管理員
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'M') {
    header('Location: login.php');
    exit();
}

// 檢查是否提供活動 ID
if (!isset($_GET['id'])) {
    die("未提供活動 ID。");
}

$activityId = intval($_GET['id']); // 確保 ID 為整數

// 查詢要編輯的活動資訊
$query = "SELECT * FROM activity_logs WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $activityId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("找不到指定的活動記錄。");
}

$activity = $result->fetch_assoc();

// 處理表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $activityName = trim($_POST['activity_name']);
    $role = $_POST['role'];
    $activityDate = $_POST['activity_date'];

    // 更新資料庫
    $updateQuery = "UPDATE activity_logs SET activity_name = ?, role = ?, activity_date = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sssi", $activityName, $role, $activityDate, $activityId);

    if ($stmt->execute()) {
        $message = "活動已成功更新！";
        header("Location: activities.php?message=" . urlencode($message));
        exit();
    } else {
        $message = "更新活動失敗：" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>更新活動資訊</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">更新活動資訊</h2>

    <!-- 顯示成功或錯誤訊息 -->
    <?php if (isset($message)): ?>
        <div class="alert <?= strpos($message, '成功') ? 'alert-success' : 'alert-danger' ?>" role="alert">
            <?= htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- 編輯活動表單 -->
    <form action="update_activity.php?id=<?= urlencode($activityId) ?>" method="POST">
        <div class="mb-3">
            <label for="activity_name" class="form-label">活動名稱</label>
            <input type="text" class="form-control" id="activity_name" name="activity_name" value="<?= htmlspecialchars($activity['activity_name']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">角色</label>
            <select class="form-select" id="role" name="role" required>
                <option value="會員" <?= $activity['role'] === '會員' ? 'selected' : '' ?>>會員</option>
                <option value="幹部" <?= $activity['role'] === '幹部' ? 'selected' : '' ?>>幹部</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="activity_date" class="form-label">活動日期</label>
            <input type="date" class="form-control" id="activity_date" name="activity_date" value="<?= htmlspecialchars($activity['activity_date']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">更新活動資訊</button>
    </form>
    <a href="activities.php" class="btn btn-secondary mt-3 w-100">返回活動管理</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
