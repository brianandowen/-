<?php
session_start();
require_once 'db.php';
require_once 'session.php';

// 獲取使用者的輸入
$name = isset($_POST['name']) ? mysqli_real_escape_string($conn, $_POST['name']) : "";
$student_id = isset($_POST['student_id']) ? mysqli_real_escape_string($conn, $_POST['student_id']) : "";
$enrollment_year = isset($_POST['enrollment_year']) ? mysqli_real_escape_string($conn, $_POST['enrollment_year']) : "";
$position = isset($_POST['position']) ? mysqli_real_escape_string($conn, $_POST['position']) : "";

// 構建查詢條件
$condition = "WHERE 1=1";
if (!empty($name)) {
    $condition .= " AND name LIKE '%$name%'";
}
if (!empty($student_id)) {
    $condition .= " AND student_id LIKE '%$student_id%'";
}
if (!empty($enrollment_year)) {
    $condition .= " AND enrollment_year = '$enrollment_year'";
}
if (!empty($position)) {
    $condition .= " AND position = '$position'";
}

// SQL 查詢語句
$sql = "SELECT id, name, student_id, enrollment_year, position FROM members $condition ORDER BY id";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("查詢失敗: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>成員查詢</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container">
    <h2 class="text-center my-4">成員查詢</h2>

    <!-- 搜尋和排序表單 -->
    <form action="query.php" method="POST" class="row g-3 mb-4">
        <div class="col-md-3">
            <label for="name" class="form-label">姓名</label>
            <input type="text" name="name" class="form-control" placeholder="輸入姓名" value="<?= htmlspecialchars($name) ?>">
        </div>
        <div class="col-md-3">
            <label for="student_id" class="form-label">學號</label>
            <input type="text" name="student_id" class="form-control" placeholder="輸入學號" value="<?= htmlspecialchars($student_id) ?>">
        </div>
        <div class="col-md-3">
            <label for="enrollment_year" class="form-label">入學年份</label>
            <input type="text" name="enrollment_year" class="form-control" placeholder="輸入年份" value="<?= htmlspecialchars($enrollment_year) ?>">
        </div>
        <div class="col-md-3">
            <label for="position" class="form-label">職位</label>
            <select name="position" class="form-select">
                <option value="">選擇職位</option>
                <option value="會員" <?= ($position == '會員') ? 'selected' : ''; ?>>會員</option>
                <option value="幹部" <?= ($position == '幹部') ? 'selected' : ''; ?>>幹部</option>
            </select>
        </div>
        <div class="col-md-12 text-end">
            <button type="submit" class="btn btn-primary">搜尋</button>
        </div>
    </form>

    <a href="insert.php" class="btn btn-success mb-3">新增成員</a>

    <!-- 顯示查詢結果 -->
    <?php if ($result->num_rows > 0): ?>
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>序列</th>
                <th>姓名</th>
                <th>學號</th>
                <th>入學年份</th>
                <th>職位</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
        <?php $counter = 1; // 初始化計數 
        while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?= $counter++; ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['student_id']) ?></td>
                <td><?= htmlspecialchars($row['enrollment_year']) ?></td>
                <td><?= htmlspecialchars($row['position']) ?></td>
                <td>
                    <a href="update.php?id=<?= urlencode($row['id']) ?>" class="btn btn-warning btn-sm">修改</a>
                    <a href="delete.php?id=<?= urlencode($row['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('確定刪除？');">刪除</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p class="text-center text-muted">沒有符合條件的成員。</p>
    <?php endif; ?>
</div>

</body>
<footer class="text-center mt-4">
    <small>&copy; 2024 輔大資管學系 二甲 陳庭毅 412401317</small>
</footer>
</html>
