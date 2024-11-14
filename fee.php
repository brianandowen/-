<?php
require_once 'session.php';
require_once 'db.php';

// 查詢已繳費的學生
$paid_query = "SELECT m.name, m.student_id, f.payment_date 
               FROM members m 
               JOIN fees f ON m.id = f.member_id 
               WHERE f.fee_status = 1";
$paid_result = $conn->query($paid_query);

// 查詢尚未繳費的學生，用於新增繳費
$unpaid_query = "SELECT id, name, student_id 
                 FROM members 
                 WHERE id NOT IN (SELECT member_id FROM fees WHERE fee_status = 1)";
$unpaid_result = $conn->query($unpaid_query);

// 統計繳費和未繳費人數
$paid_count = $conn->query("SELECT COUNT(*) as count FROM fees WHERE fee_status = 1")->fetch_assoc()['count'];
$unpaid_count = $conn->query("SELECT COUNT(*) as count FROM members WHERE id NOT IN (SELECT member_id FROM fees WHERE fee_status = 1)")->fetch_assoc()['count'];

// 更新繳費狀態處理
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_member'])) {
    $selected_member = $_POST['selected_member'];
    $payment_date = date('Y-m-d');
    $insert_query = "INSERT INTO fees (member_id, fee_status, payment_date) VALUES (?, 1, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("is", $selected_member, $payment_date);

    if ($stmt->execute()) {
        $message = "繳費已成功添加！";
        header("Location: fees.php"); // 重新加載頁面以更新數據
        exit();
    } else {
        $message = "添加失敗，請稍後再試。";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>會費查詢</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <h1 class="text-center">會費查詢</h1>

    <!-- 已繳費學生列表 -->
    <h2 class="mt-4">已繳費的學生</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>姓名</th>
                <th>學號</th>
                <th>繳費時間</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $paid_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['student_id']) ?></td>
                    <td><?= htmlspecialchars($row['payment_date']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- 新增繳費記錄 -->
    <h2 class="mt-4">新增繳費</h2>
    <form action="fees.php" method="POST">
        <div class="mb-3">
            <label for="selected_member" class="form-label">選擇學生:</label>
            <select class="form-select" name="selected_member" id="selected_member" required>
                <option value="">請選擇尚未繳費的學生</option>
                <?php while ($row = $unpaid_result->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?> (<?= htmlspecialchars($row['student_id']) ?>)</option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary w-100">新增繳費</button>
    </form>

    <!-- 繳費統計表 -->
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
