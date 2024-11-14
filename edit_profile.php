<?php
require_once 'db.php';
require_once 'session.php'; 

// 獲取當前使用者的舊帳號
$oldAccount = $_SESSION['username']; 

// 查詢資料庫，獲取使用者的帳號、密碼和名稱
$query = "SELECT account, password, name FROM user WHERE account = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $oldAccount);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// 處理表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 從表單中取得更新的資料
    $newAccount = mysqli_real_escape_string($conn, $_POST['account']);
    $newPassword = mysqli_real_escape_string($conn, $_POST['password']);
    $newName = mysqli_real_escape_string($conn, $_POST['name']);
    
    // 更新使用者資料
    $update_query = "UPDATE user SET account = ?, password = ?, name = ? WHERE account = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ssss", $newAccount, $newPassword, $newName, $oldAccount);

    if ($update_stmt->execute()) {
        $message = "更新成功！";
        // 更新 session 中的帳號和名稱
        $_SESSION['username'] = $newAccount; 
        $_SESSION['name'] = $newName;
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
    <title>編輯個人資料</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">編輯個人資料</h2>

    <!-- 顯示成功或錯誤訊息 -->
    <?php if (isset($message)): ?>
        <div class="alert <?= strpos($message, '成功') ? 'alert-success' : 'alert-danger' ?>" role="alert">
            <?= htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- 個人資料編輯表單 -->
    <form action="edit_profile.php" method="POST">
        <div class="mb-3">
            <label for="account" class="form-label">帳號</label>
            <input type="text" id="account" name="account" class="form-control" value="<?= htmlspecialchars($user['account']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">密碼</label>
            <div>
            <input type="password" id="password" name="password" class="form-control" value="<?= htmlspecialchars($user['password']) ?>" required>
            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">顯示密碼</button>
            </div>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">名稱</label>
            <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">更新資料</button>
    </form>
    <a href="status.php" class="btn btn-primary w-10">回首頁</a>
</div>
</body>
<script>
    function togglePassword() {
        var passwordField = document.getElementById("password");
        var toggleButton = event.target;
        if (passwordField.type === "password") {
            passwordField.type = "text";
            toggleButton.innerText = "隱藏密碼";
        } else {
            passwordField.type = "password";
            toggleButton.innerText = "顯示密碼";
        }
    }
</script>
</html>
