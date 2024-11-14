<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'db.php'; 

$message = ''; // 儲存訊息

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $account = $_POST['account'];
    $password = $_POST['password'];
    $name = $_POST['name'];

    // 檢查帳號是否已存在
    $query = "SELECT * FROM user WHERE account = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        $message = "準備語句失敗: " . $conn->error;
    } else {
        $stmt->bind_param("s", $account);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = "帳號已存在，請選擇其他帳號。";
        } else {
            // 插入新使用者
            $query = "INSERT INTO user (account, password, name) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);

            if (!$stmt) {
                $message = "準備語句失敗: " . $conn->error;
            } else {
                $stmt->bind_param("sss", $account, $password, $name);
                if ($stmt->execute()) {
                    $message = "註冊成功！";
                } else {
                    $message = "註冊失敗，請稍後再試。";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Register</title>
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="container" style="max-width: 400px;">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="text-center mb-4">Register</h2>

                <!-- 顯示註冊訊息 -->
                <?php if (!empty($message)): ?>
                    <div class="alert <?= strpos($message, '成功') !== false ? 'alert-success' : 'alert-danger' ?>" role="alert">
                        <?= htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <form action="register.php" method="POST">
                    <div class="mb-3">
                        <label for="account" class="form-label">帳號:</label>
                        <input type="text" id="account" name="account" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">密碼:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">名稱:</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">註冊</button>
                    <a href="login.php">回上一步</a>
                </form>
            </div>
        </div>
    </div>
</body>
<footer style="position: fixed; bottom: 5%; width: 100%; text-align: center;">
    <small>
      Copyright © 2024 輔大資管學系 二甲 陳庭毅 412401317
    </small>
</footer>
</html>
