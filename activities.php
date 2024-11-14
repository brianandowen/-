<?php
require_once 'session.php';
require_once 'db.php';

// 確保使用者已登入並且是管理員
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'M') {
    header('Location: login.php');
    exit();
}

// 查詢所有成員的活動參與紀錄，按成員和活動分組
$activity_query = "
    SELECT m.name AS member_name, m.student_id, a.activity_name, a.role, a.activity_date, COUNT(a.id) as participation_count
    FROM members m
    JOIN activity_logs a ON m.id = a.member_id
    GROUP BY m.name, m.student_id, a.activity_name, a.role, a.activity_date
    ORDER BY m.name, a.activity_date DESC
";
$activity_result = $conn->query($activity_query);

// 處理新增活動紀錄的表單提交
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['member_id'])) {
    $member_id = $_POST['member_id'];
    $activity_name = $_POST['activity_name'];
    $role = $_POST['role'];
    $activity_date = $_POST['activity_date'];

    // 插入新的活動紀錄
    $insert_query = "INSERT INTO activity_logs (member_id, activity_name, role, activity_date) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("isss", $member_id, $activity_name, $role, $activity_date);

    if ($stmt->execute()) {
        $message = "活動紀錄已成功添加！";
        header("Location: activities.php"); // 重新加載頁面以更新數據
        exit();
    } else {
        $message = "添加失敗，請稍後再試。";
    }
}

// 查詢所有成員，用於新增活動的下拉選單
$members_query = "SELECT id, name, student_id FROM members";
$members_result = $conn->query($members_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>活動參與統整表</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <h1 class="text-center">活動參與統整表</h1>

    <!-- 成功或錯誤訊息 -->
    <?php if ($message): ?>
        <div class="alert <?= strpos($message, '成功') !== false ? 'alert-success' : 'alert-danger' ?>" role="alert">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- 活動參與紀錄表格 -->
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>姓名</th>
                <th>學號</th>
                <th>活動名稱</th>
                <th>角色</th>
                <th>參加次數</th>
                <th>活動日期</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $activity_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['member_name']) ?></td>
                    <td><?= htmlspecialchars($row['student_id']) ?></td>
                    <td><?= htmlspecialchars($row['activity_name']) ?></td>
                    <td><?= htmlspecialchars($row['role']) ?></td>
                    <td><?= htmlspecialchars($row['participation_count']) ?></td>
                    <td><?= htmlspecialchars($row['activity_date']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- 新增活動紀錄表單按鈕 -->
    <button class="btn btn-primary mt-4" data-bs-toggle="modal" data-bs-target="#addActivityModal">新增活動紀錄</button>

    <!-- 新增活動紀錄的模態框 -->
    <div class="modal fade" id="addActivityModal" tabindex="-1" aria-labelledby="addActivityModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="activities.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addActivityModalLabel">新增活動紀錄</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="member_id" class="form-label">選擇成員</label>
                            <select class="form-select" name="member_id" id="member_id" required>
                                <option value="">請選擇成員</option>
                                <?php while ($row = $members_result->fetch_assoc()): ?>
                                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?> (<?= htmlspecialchars($row['student_id']) ?>)</option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="activity_name" class="form-label">活動名稱</label>
                            <input type="text" class="form-control" name="activity_name" id="activity_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">角色</label>
                            <select class="form-select" name="role" id="role" required>
                                <option value="會員">會員</option>
                                <option value="幹部">幹部</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="activity_date" class="form-label">活動日期</label>
                            <input type="date" class="form-control" name="activity_date" id="activity_date" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-primary">新增活動紀錄</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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
