<?php
session_start();
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

// 查詢所有成員
$members_query = "SELECT id, name FROM members ORDER BY name";
$members_result = $conn->query($members_query);

// 儲存成員資料
$members = [];
while ($member = $members_result->fetch_assoc()) {
    $members[] = $member;
}

// 查詢所有活動總覽
$overview_query = "
    SELECT a.id AS activity_id, m.name AS member_name, m.student_id, 
           a.activity_name, a.role, a.activity_date
    FROM activity_logs a
    JOIN members m ON a.member_id = m.id
    ORDER BY m.name, a.activity_date DESC
";
$overview_result = $conn->query($overview_query);

// 儲存活動總覽
$overview_data = [];
while ($row = $overview_result->fetch_assoc()) {
    $overview_data[] = $row;
}

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

// 處理新增活動紀錄
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        $member_id = $_POST['member_id'] ?? '';
        $activity_name = trim($_POST['activity_name']);
        $role = $_POST['role'] ?? '';
        $activity_date = $_POST['activity_date'] ?? '';

        // 驗證表單數據
        if (empty($member_id) || empty($activity_name) || empty($role) || empty($activity_date)) {
            $message = "所有欄位都必須填寫！";
        } else {
            $insert_query = "INSERT INTO activity_logs (member_id, activity_name, role, activity_date) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("isss", $member_id, $activity_name, $role, $activity_date);

            if ($stmt->execute()) {
                $message = "活動「$activity_name」已成功添加！";
                header("Location: activities.php?member_id=$member_id");
                exit();
            } else {
                $message = "新增活動失敗：" . $conn->error;
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
    <title>活動參與管理</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <!-- 成功或錯誤訊息 -->
    <?php if ($message): ?>
        <div class="alert <?= strpos($message, '成功') !== false ? 'alert-success' : 'alert-danger' ?>" role="alert">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- 標籤切換 -->
    <ul class="nav nav-tabs mt-4" id="activityTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link <?= $selected_member_id ? '' : 'active' ?>" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab" aria-controls="overview" aria-selected="<?= $selected_member_id ? 'false' : 'true' ?>">活動總覽</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link <?= $selected_member_id ? 'active' : '' ?>" id="member-tab" data-bs-toggle="tab" data-bs-target="#member" type="button" role="tab" aria-controls="member" aria-selected="<?= $selected_member_id ? 'true' : 'false' ?>">成員活動紀錄</button>
        </li>
    </ul>
    <!-- 新增活動紀錄按鈕 -->
    <button class="btn btn-primary mt-4" data-bs-toggle="modal" data-bs-target="#addActivityModal">新增活動紀錄</button>

    <div class="tab-content" id="activityTabsContent">
        <!-- 活動總覽 -->
        <div class="tab-pane fade <?= $selected_member_id ? '' : 'show active' ?>" id="overview" role="tabpanel" aria-labelledby="overview-tab">
            <h2 class="mt-4">活動總覽</h2>
            <table class="table table-bordered table-striped table-hover mt-4">
    <thead class="table-dark">
        <tr>
            <th>姓名</th>
            <th>學號</th>
            <th>活動名稱</th>
            <th>角色</th>
            <th>活動日期</th>
            <th>操作</th> <!-- 新增操作欄 -->
        </tr>
    </thead>
    <tbody>
        <?php foreach ($overview_data as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['member_name']) ?></td>
                <td><?= htmlspecialchars($row['student_id']) ?></td>
                <td><?= htmlspecialchars($row['activity_name']) ?></td>
                <td><?= htmlspecialchars($row['role']) ?></td>
                <td><?= htmlspecialchars($row['activity_date']) ?></td>
                <td>
                <a href="update_activity.php?id=<?= $row['activity_id'] ?>" class="btn btn-warning btn-sm">變更</a>
                <a href="delete_activity.php?id=<?= $row['activity_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('確定刪除此活動嗎？')">刪除</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

        </div>

        <!-- 成員活動紀錄 -->
        <div class="tab-pane fade <?= $selected_member_id ? 'show active' : '' ?>" id="member" role="tabpanel" aria-labelledby="member-tab">
            <form action="activities.php" method="GET" class="mt-4">
                <label for="member_id" class="form-label">選擇成員:</label>
                <select name="member_id" id="member_id" class="form-select" onchange="this.form.submit()">
                    <option value="">請選擇成員</option>
                    <?php foreach ($members as $member): ?>
                        <option value="<?= $member['id'] ?>" <?= ($selected_member_id == $member['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($member['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>

            <?php if ($selected_member_id && $activities): ?>
                <h2 class="mt-4">活動紀錄</h2>
                <p><strong>學號:</strong> <?= htmlspecialchars($activities[0]['student_id']) ?></p>
                <p><strong>累積活動參加場次數:</strong> <?= $total_activities ?></p>
                <table class="table table-bordered mt-4">
    <thead class="table-dark">
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
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

            <?php endif; ?>
        </div>
    </div>

    <!-- 新增活動紀錄模態框 -->
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
                        <h6 class="text-primary">基本資訊</h6>
                        <div class="mb-3">
                            <label for="member_id" class="form-label">選擇成員</label>
                            <select class="form-select" name="member_id" id="member_id" required>
                                <option value="">請選擇成員</option>
                                <?php foreach ($members as $member): ?>
                                    <option value="<?= $member['id'] ?>"><?= htmlspecialchars($member['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <h6 class="text-primary">活動內容</h6>
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
<?php include 'footer.php'; ?>

</html>

<?php
// 關閉資料庫連線
$conn->close();
?>
