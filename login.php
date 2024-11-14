<?php
session_start();
$errorMessage = $_SESSION['errorMessage'] ?? '';
unset($_SESSION['errorMessage']); // 顯示後清除錯誤訊息
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login</title>
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="container" style="max-width: 400px;">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="text-center mb-4">管理員登入</h2>

                <!-- 顯示錯誤訊息 -->
                <?php if (!empty($errorMessage)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($errorMessage); ?></div>
                <?php endif; ?>
                <form action="login_process.php" method="post">
                    <div class="mb-3">
                        <label for="account" class="form-label">帳號:</label>
                        <input type="text" id="account" name="account" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">密碼:</label>
                        <div class="input-group">
                            <input type="password" id="password" name="password" class="form-control" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">顯示密碼</button>
                        </div>
                    </div>
                    <div class="d-grid">
                        <input type="submit" value="Login" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>

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
</body>
</html>
