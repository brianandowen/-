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
$query = "SELECT * FROM job WHERE postid = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("找不到要修改的資料");
}

$job = $result->fetch_assoc(); // 獲取當前資料的內容

// 處理表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 將 content 資料中的 \r\n 和 \r 都轉換為單一的 \n，保持一致性
    $company = $_POST['company'];
    $content = str_replace(["\r\n", "\r"], "\n", $_POST['content']);
    $pdate = $_POST['pdate'];

    // 更新資料庫內容
    $update_query = "UPDATE job SET company = ?, content = ?, pdate = ? WHERE postid = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sssi", $company, $content, $pdate, $id);

    if ($stmt->execute()) {
        $message = "資料更新成功！";
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
    <title>修改徵才資料</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">修改徵才資料</h2>

    <!-- 顯示成功或失敗訊息 -->
    <?php if (isset($message)): ?>
        <div class="alert <?= strpos($message, '成功') ? 'alert-success' : 'alert-danger' ?>" role="alert">
            <?= htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- 修改資料表單，預填現有資料 -->
    <form action="update.php?id=<?= urlencode($id) ?>" method="POST">
        <div class="mb-3">
            <label for="company" class="form-label">求才廠商</label>
            <input type="text" id="company" name="company" class="form-control" value="<?= htmlspecialchars($job['company']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">求才內容</label>
            <textarea id="content" name="content" class="form-control" rows="4" required><?= htmlspecialchars($job['content']) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="pdate" class="form-label">刊登日期</label>
            <input type="date" id="pdate" name="pdate" class="form-control" value="<?= htmlspecialchars($job['pdate']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">更新資料</button>
    </form>
    <a href="Query.php" class="btn btn-primary w-10">回上一頁</a>
</div>
</body>
</html>
