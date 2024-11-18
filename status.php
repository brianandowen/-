<?php
require_once 'session.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Page</title>
    <!-- 引入 MDB 的 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/mdbootstrap@5.3.0/dist/css/mdb.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">網站名稱</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto"> <!-- ms-auto 使導航項目靠右 -->
                <li class="nav-item active">
                    <a class="nav-link" href="#">首頁</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">功能</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">登出</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <?php if ($role === 'M'): ?>
                <h1 class="display-4">管理者您好</h1>
            <?php else: ?>
                <h1 class="display-4">您好, <?php echo htmlspecialchars($nickname); ?>!</h1>
            <?php endif; ?>
            <p class="lead text-muted">歡迎來到WEB後端設計的地獄</p>
            <p id="greeting" class="lead"></p> <!-- 動態問候語 -->
            <p id="current-time" class="lead"></p> <!-- 當前時間 -->
            <p id="quote" class="lead text-muted"></p> <!-- 隨機名言 -->
        </div>
    </div>
</div>

<!-- 引入 MDB 的 JS -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/mdbootstrap@5.3.0/dist/js/mdb.min.js"></script>

<script>
    // 動態時間與問候語
    function updateTime() {
        const now = new Date();
        const hours = now.getHours();
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const timeString = `${hours}:${minutes}:${seconds}`;
        
        const greeting = hours < 12 ? "早安！" : hours < 18 ? "午安！" : "晚安！";
        
        document.getElementById("greeting").innerText = greeting;
        document.getElementById("current-time").innerText = `現在時間：${timeString}`;
    }
    setInterval(updateTime, 1000);

    // 隨機名言
    const quotes = [
        "妳的浴巾沒有鬱金香",
        "被生活操得腿開開",
        "原來曖昧可以牽手",
        "好想打麻將",
        "e04"
    ];
    
    function displayRandomQuote() {
        const randomIndex = Math.floor(Math.random() * quotes.length);
        document.getElementById("quote").innerText = quotes[randomIndex];
    }
    displayRandomQuote();
</script>

<!-- Footer -->
<footer class="bg-light text-center py-3" style="position: fixed; bottom: 0; width: 100%; text-align: center;">
    <small>
        Copyright © 2024 輔大資管學系 二甲 陳庭毅 412401317<br>
        Copyright © 2024 輔大資管學系 二甲 吳宇燊 412261121
    </small>
</footer>

</body>
</html>
