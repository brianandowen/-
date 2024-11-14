<?php
require_once 'session.php';
$requireUser = true;

if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // 如果沒有登入，導回登入頁面
    exit();
}
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conference Page</title>
    <!-- 引入 Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container mt-5">
    <h1 class="text-center">活動報名表單</h1>

    <!-- Conference 表單 -->
    <form action="conference.php" method="post">
    <div class="row justify-content-center">
        <div class="col-6">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="_name" name="name" placeholder="您的姓名" value="<?php echo htmlspecialchars($username); ?>" readonly>
                <label for="_name">Name</label>
            </div>
        </div>
    </div>
        <div class="row">
            <div class="col">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" name="program[]" id="program_1" >
                    <label class="form-check-label" for="program_1">上午場 ($150)</label>
                </div>
            </div>
            <div class="col">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="2" name="program[]" id="program_2">
                    <label class="form-check-label" for="program_2">下午場 ($100)</label>
                </div>
            </div>
            <div class="col">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="3" name="program[]" id="program_3">
                    <label class="form-check-label" for="program_3">午餐 ($60)</label>
                </div>
            </div>
        </div>
        <input class="btn btn-primary mt-3" type="submit" value="Submit" />
    </form>
</div>

<!-- 引入 Bootstrap 的 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
<footer style="position: fixed; bottom: 5%; width: 100%; text-align: center;">
    <small>
      Copyright © 2024 輔大資管學系 二甲 陳庭毅 412401317
    </small>
</footer>

</html>
