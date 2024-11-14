<?php
require_once 'session.php'; // 包含 session 驗證邏輯
require_once 'db.php';

// 初始化訊息
$message = '';

// 從 session 中獲取使用者的 member_id
$member_id = $_SESSION['user_id']; // 假設 session 中已儲存使用者 ID 作為 member_id

// 查詢該使用者的繳費狀況
$query = "SELECT fee_status, payment_date FROM fee WHERE member_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $member_id);
$stmt->execute();
$result = $stmt->get_result();

$fee_status = 0;
$payment_date = null;

if ($result->num_rows > 0) {
    $fee_data = $result->fetch_assoc();
    $fee_status = $fee_data['fee_status'];
    $payment_date = $fee_data['payment_date'];
}

// 處理表單提交，更新繳費狀態
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fee_status = isset($_POST['fee_status']) ? 1 : 0;
    $payment_date = $fee_status ? date('Y-m-d') : null;

    // 檢查是否已有繳費記錄
    if ($result->num_rows > 0) {
        // 更新已存在的記錄
        $update_query = "UPDATE fee SET fee_status = ?, payment_date = ? WHERE member_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("isi", $fee_status, $payment_date, $member_id);
    } else {
        // 插入新的記錄
        $insert_query = "INSERT INTO fee (member_id, fee_status, payment_date) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("iis", $member_id, $fee_status, $payment_date);
    }

    if ($stmt->execute()) {
        $message = "會費狀態已更新成功！";
    } else {
        $message = "更新失敗，請稍後再試。";
    }
}

// 查詢已繳費與未繳費人數，用於生成統計表
$paid_query = "SELECT COUNT(*) as paid_count FROM fee WHERE fee_status = 1";
$unpaid_query = "SELECT COUNT(*) as unpaid_count FROM fee WHERE fee_status = 0";
$paid_result = $conn->query($paid_query);
$unpaid_result = $conn->query($unpaid_query);
$paid_count = $paid_result->fetch_assoc()['paid_count'] ?? 0;
$unpaid_count = $unpaid_result->fetch_assoc()['unpaid_count'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>會費管理系統</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <h1 class="text-center">會費管理系統</h1>

    <!-- 顯示成功或錯誤訊息 -->
    <?php if ($message): ?>
        <div class="alert <?= strpos($message, '成功') ? 'alert-success' : 'alert-danger' ?>" role="alert">
            <?= htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- 使用者繳費狀態表單 -->
    <form action="fee.php" method="POST">
        <div class="mb-4">
            <label class="form-label">繳費狀態:</label><br>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="fee_status" id="fee_status" <?= $fee_status ? 'checked' : '' ?>>
                <label class="form-check-label" for="fee_status">是否已繳費</label>
            </div>
        </div>

        <?php if ($fee_status && $payment_date): ?>
            <div class="mb-4">
                <label class="form-label">繳費日期:</label>
                <p><?= htmlspecialchars($payment_date); ?></p>
            </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-outline-dark w-100">更新繳費狀態</button>
    </form>

    <!-- 會費繳交統計表 -->
    <div class="mt-5">
        <h2 class="text-center">會費繳交統計表</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>已繳費人數</th>
                    <th>未繳費人數</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= $paid_count ?></td>
                    <td><?= $unpaid_count ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
<footer style="position: fixed; bottom: 5%; width: 100%; text-align: center;">
    <small>
      Copyright © 2024 輔大資管學系 二甲 陳庭毅 412401317
    </small>
</footer>

</html>

<?php
// 關閉資料庫連線
$conn->close();
?>
