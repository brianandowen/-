<?php
session_start();
require_once 'db.php';
require_once 'session.php';

// 獲取使用者的輸入
$order = isset($_POST['order']) ? mysqli_real_escape_string($conn, $_POST['order']) : "";
$searchtxt = isset($_POST['searchtxt']) ? mysqli_real_escape_string($conn, $_POST['searchtxt']) : "";
$start_date = isset($_POST['start_date']) ? mysqli_real_escape_string($conn, $_POST['start_date']) : "";
$end_date = isset($_POST['end_date']) ? mysqli_real_escape_string($conn, $_POST['end_date']) : "";

// 構建查詢條件
$condition = "";

// 搜尋條件
if (!empty($searchtxt)) {
    $condition .= "WHERE (company LIKE '%$searchtxt%' OR content LIKE '%$searchtxt%')";
}

// 日期篩選條件
if (!empty($start_date) || !empty($end_date)) {
    $condition .= !empty($condition) ? " AND" : " WHERE";
    
    if (!empty($start_date) && !empty($end_date)) {
        if ($start_date > $end_date) {
            $temp = $start_date;
            $start_date = $end_date;
            $end_date = $temp;
        }
        $condition .= " pdate BETWEEN '$start_date' AND '$end_date'";
    } elseif (!empty($start_date)) {
        $condition .= " pdate >= '$start_date'";
    } elseif (!empty($end_date)) {
        $condition .= " pdate <= '$end_date'";
    }
}

// 排序條件
if (!empty($order)) {
    $condition .= " ORDER BY $order";
}

// SQL 查詢語句
$sql = "SELECT postid, company, content, pdate FROM job $condition";
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
    <title>徵才資料</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container">
    <h2 class="text-center my-4">徵才資料</h2>

    <!-- 搜尋和排序表單 -->
    <form action="query.php" method="POST" class="mb-4">
        <select name="order" class="form-select mb-2" aria-label="選擇排序欄位">
            <option value="" <?=($order=='')?'selected':''?>>選擇排序欄位</option>
            <option value="company" <?=($order=="company")?"selected":""?>>求才廠商</option>
            <option value="content" <?=($order=="content")?"selected":""?>>求才內容</option>
            <option value="pdate" <?=($order=="pdate")?"selected":""?>>刊登日期</option>
        </select>
        <input placeholder="搜尋廠商及內容" class="form-control mb-2" type="text" name="searchtxt" value="<?= htmlspecialchars($searchtxt) ?>">

        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="start_date" class="col-form-label">開始日期</label>
            </div>
            <div class="col-auto">
                <input id="start_date" class="form-control" type="date" name="start_date" value="<?= htmlspecialchars($start_date) ?>">
            </div>
            <div class="col-auto">
                <label for="end_date" class="col-form-label">結束日期</label>
            </div>
            <div class="col-auto">
                <input id="end_date" class="form-control" type="date" name="end_date" value="<?= htmlspecialchars($end_date) ?>">
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">搜尋</button>
    </form>

    <!-- 新增按鈕，僅限管理者顯示 -->
    <?php if ($isAdmin): ?>
        <a href="insert.php" class="btn btn-success mb-3">新增徵才資料</a>
    <?php endif; ?>

    <!-- 顯示查詢結果 -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>序列</th>
                <th>求才廠商</th>
                <th>求才內容</th>
                <th>日期</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
        <?php $counter = 1; // 初始化計數 
        while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?= $counter++; ?></td>
                    <td><?= nl2br(htmlspecialchars($row['company'])) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['content'])) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['pdate'])) ?></td>
                    <td>
                        <?php if ($isAdmin): ?>
                            <a href="update.php?id=<?= urlencode($row['postid']) ?>" class="btn btn-warning btn-sm">修改</a>
                            <a href="delete.php?id=<?= urlencode($row['postid']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('確定刪除？');">刪除</a>
                        <?php else: ?>
                            <span>無權限</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
<footer class="text-center mt-4">
    <small>&copy; 2024 輔大資管學系 二甲 陳庭毅 412401317</small>
</footer>
</html>

<?php
// 關閉資料庫連線
mysqli_close($conn);
?>
