<?php
require_once 'session.php';
require_once 'db.php';

// 確保使用者已登入並且是管理員
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'M') {
    header('Location: login.php');
    exit();
}

// 初始化訊息
$message = '';
$selected_member_id = $_GET['member_id'] ?? null;

// 查詢成員列表，用於選擇特定成員
$members_query = "SELECT id, name FROM members ORDER BY name";
$members_result = $conn->query($members_query);

// 查詢所有成員的活動紀錄，用於「總覽」
$overview_query = "
    SELECT m.name AS member_name, m.student_id, a.activity_name, a.role, a.activity_date
    FROM activity_logs a
    JOIN members m ON a.member_id = m.id
    ORDER BY m.name, a.activity_date DESC
";
$overview_result = $conn->query($overview_query);

// 查詢選定成員的活動紀錄
$activities = [];
$total_activities = 0;
if ($selected_member_id) {
    $activity_query = "
        SELECT a.id AS activity_id, m.name AS member_name, m.student_id, 
               a.activity_name, a.role, a.activity_date
        FROM activity_logs a
        JOIN members m ON a.member_id = m.id
        WHERE m.id = ?
        ORDER BY a.activity_date DESC
    ";
    $stmt = $conn->prepare($activity_query);
    $stmt->bind_param("i", $selected_member_id);
    $stmt->execute();
    $activity_result = $stmt->get_result();
    $activities = $activity_result->fetch_all(MYSQLI_ASSOC);

    // 計算活動參加次數
    $total_activities_query = "SELECT COUNT(*) as total FROM activity_logs WHERE member_id = ?";
    $stmt = $conn->prepare($total_activities_query);
    $stmt->bind_param("i", $selected_member_id);
    $stmt->execute();
    $total_activities = $stmt->get_result()->fetch_assoc()['total'];
}

// 處理新增活動紀錄的表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $member_id = $_POST['member_id'];
    $activity_name = $_POST['activity_name'];
    $role = $_POST['role'];
    $activity_date = $_POST['activity_date'];

    $insert_query = "INSERT INTO activity_logs (member_id, activity_name, role, activity_date) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("isss", $member_id, $activity_name, $role, $activity_date);

    if ($stmt->execute()) {
        $message = "活動紀錄已成功添加！";
        header("Location: activities.php?member_id=" . $member_id); // 重新加載頁面以更新數據
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
    <title>活動參與管理</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <h1 class="text-center">活動參與管理</h1>

    <!-- 成功或錯誤訊息 -->
    <?php if ($message): ?>
        <div class="alert <?= strpos($message, '成功') !== false ? 'alert-success' : 'alert-danger' ?>" role="alert">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- 總覽表格 -->
    <h2 class="mt-4">總覽</h2>
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>姓名</th>
                <th>學號</th>
                <th>活動名稱</th>
                <th>角色</th>
                <th>活動日期</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $overview_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['member_name']) ?></td>
                    <td><?= htmlspecialchars($row['student_id']) ?></td>
                    <td><?= htmlspecialchars($row['activity_name']) ?></td>
                    <td><?= htmlspecialchars($row['role']) ?></td>
                    <td><?= htmlspecialchars($row['activity_date']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- 選擇成員 -->
    <form action="activities.php" method="GET" class="mb-4">
        <label for="member_id" class="form-label">選擇成員:</label>
        <select name="member_id" id="member_id" class="form-select" onchange="this.form.submit()">
            <option value="">請選擇成員</option>
            <?php while ($member = $members_result->fetch_assoc()): ?>
                <option value="<?= $member['id'] ?>" <?= ($selected_member_id == $member['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($member['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>

    <!-- 顯示選定成員的活動紀錄 -->
    <?php if ($selected_member_id && $activities): ?>
        <h2 class="mt-4">活動紀錄</h2>
        <p><strong>學號:</strong> <?= htmlspecialchars($activities[0]['student_id']) ?></p>
        <p><strong>累積活動參加場次數:</strong> <?= $total_activities ?></p>

        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>活動名稱</th>
                    <th>角色</th>
                    <th>活動日期</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($activities as $activity): ?>
                    <tr>
                        <td><?= htmlspecialchars($activity['activity_name']) ?></td>
                        <td><?= htmlspecialchars($activity['role']) ?></td>
                        <td><?= htmlspecialchars($activity['activity_date']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- 新增活動紀錄按鈕 -->
    <button class="btn btn-primary mt-4" data-bs-toggle="modal" data-bs-target="#addActivityModal">新增活動紀錄</button>

    <!-- 新增活動紀錄的模態框 -->
    <div class="modal fade" id="addActivityModal" tabindex="-1" aria-labelledby="addActivityModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="activities.php" method="POST">
                    <input type="hidden" name="action" value="add">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addActivityModalLabel">新增活動紀錄</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="member_id" class="form-label">選擇成員</label>
                            <select class="form-select" name="member_id" id="member_id" required>
                                <option value="">請選擇成員</option>
                                <?php $members_result->data_seek(0); // 重置結果集游標 ?>
                                <?php while ($member = $members_result->fetch_assoc()): ?>
                                    <option value="<?= $member['id'] ?>"><?= htmlspecialchars($member['name']) ?></option>
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
<footer class="text-center mt-4">
    <small>&copy; 2024 輔大資管學系 二甲 陳庭毅 412401317</small>
</footer>

</html>

<?php
// 關閉資料庫連線
$conn->close();
?>
