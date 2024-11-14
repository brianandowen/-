<?php
require_once 'session.php';

// 費用計算邏輯
$program_price = array(
    array(2000, 0, 0, 3000),  
    array(0, 300, 150, 5500)   
);

$price = 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $membershipStatus = $_POST["membershipFee"] == 2000 ? 0 : 1;
    $price += $program_price[$membershipStatus][0];
    $programs = $_POST["program"] ?? [];
    foreach ($programs as $program) {
        $price += $program_price[$membershipStatus][$program + 1];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container mt-5">
    <h1 class="text-center">活動費用</h1>
    <h5 class="text-center">系上活動費用估算</h5>

    <form action="fee.php" method="post">
        <div class="mb-4">
            <label class="form-label">會費:</label><br>
            <div class="form-check">
                <input type="radio" class="form-check-input" name="membershipFee" value="2000" required>
                <label class="form-check-label">繳交 ($2000)</label>
            </div>
            <div class="form-check">
                <input type="radio" class="form-check-input" name="membershipFee" value="0" required>
                <label class="form-check-label">不繳交 ($0)</label>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label">活動:</label><br>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="program[]" value="0">
                <label class="form-check-label">一日資管營 (會員免費 / 非會員 $300)</label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="program[]" value="1">
                <label class="form-check-label">迎新茶會 (會員免費 / 非會員 $150)</label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="program[]" value="2">
                <label class="form-check-label">迎新宿營 (會員 $3000 / 非會員 $5500)</label>
            </div>
        </div>

        <button type="submit" class="btn btn-outline-dark w-100">確定</button>
    </form>

    <!-- 顯示計算結果 -->
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div class="alert alert-success mt-4" role="alert">
            總費用：$<?php echo $price; ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
<footer style="position: fixed; bottom: 5%; width: 100%; text-align: center;">
    <small>
      Copyright © 2024 輔大資管學系 二甲 陳庭毅 412401317
    </small>
</footer>

</html>
