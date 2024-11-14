<?php
require_once 'db.php';
require_once 'session.php';

// 處理表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company = mysqli_real_escape_string($conn, $_POST['company']);
    // 將換行符標準化為 \n 以保持一致性
    $content = str_replace(["\r\n", "\r"], "\n", $_POST['content']);
    $pdate = mysqli_real_escape_string($conn, $_POST['pdate']);

    // 插入資料到資料庫
    $query = "INSERT INTO job (company, content, pdate) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $company, $content, $pdate);

    if ($stmt->execute()) {
        $message = "徵才資料新增成功！";
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
    <title>新增徵才資料</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">新增徵才資料</h2>
    
    <!-- 顯示成功或失敗訊息 -->
    <?php if (isset($message)): ?>
        <div class="alert <?= strpos($message, '成功') ? 'alert-success' : 'alert-danger' ?>" role="alert">
            <?= htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- 新增資料表單 -->
    <form action="insert.php" method="POST">
        <div class="mb-3">
            <label for="company" class="form-label">求才廠商</label>
            <input type="text" id="company" name="company" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">求才內容</label>
            <textarea id="content" name="content" class="form-control" rows="4" required></textarea>
        </div>
        <div class="mb-3">
            <label for="pdate" class="form-label">刊登日期</label>
            <input type="date" id="pdate" name="pdate" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">新增</button>
    </form>
    <a href="Query.php" class="btn btn-primary w-10">回上一頁</a>

</div>
</body>
</html>
