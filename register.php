<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'db.php'; 

$message = ''; // 儲存訊息

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $student_id = $_POST['student_id'];
    $contact_info = $_POST['contact_info'];
    $enrollment_year = $_POST['enrollment_year'];
    $position = $_POST['position'];  // 取得選擇的職位

    // 檢查學號是否已存在
    $query = "SELECT * FROM members WHERE student_id = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        $message = "準備語句失敗: " . $conn->error;
    } else {
        $stmt->bind_param("s", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = "學號已存在，請確認或聯繫管理員。";
        } else {
            // 插入新成員資料
            $query = "INSERT INTO members (name, student_id, contact_info, enrollment_year, position) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);

            if (!$stmt) {
                $message = "準備語句失敗: " . $conn->error;
            } else {
                $stmt->bind_param("sssss", $name, $student_id, $contact_info, $enrollment_year, $position);
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
    <title>註冊成員</title>
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="container" style="max-width: 400px;">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="text-center mb-4">註冊成員</h2>

                <!-- 顯示註冊訊息 -->
                <?php if (!empty($message)): ?>
                    <div class="alert <?= strpos($message, '成功') !== false ? 'alert-success' : 'alert-danger' ?>" role="alert">
                        <?= htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <form action="register.php" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">姓名:</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="student_id" class="form-label">學號:</label>
                        <input type="text" id="student_id" name="student_id" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="contact_info" class="form-label">聯絡方式:</label>
                        <input type="text" id="contact_info" name="contact_info" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="enrollment_year" class="form-label">入學年份:</label>
                        <input type="number" id="enrollment_year" name="enrollment_year" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="position" class="form-label">職位:</label>
                        <select id="position" name="position" class="form-control">
                            <option>請選擇</option>
                            <option value="會員">會員</option>
                            <option value="幹部">幹部</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">註冊成員</button>
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
