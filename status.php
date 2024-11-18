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
    <title>管理系統首頁</title>
    <!-- 引入 Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa; /* 背景為淺灰 */
            color: #333; /* 主文字顏色 */
        }

        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* 卡片陰影 */
        }
        footer {
            background-color: #003366; /* 頁腳深藍色 */
            color: white;
        }
    </style>
</head>
<body>

<!-- 導航欄 -->
<?php include "navbar.php" ;?>

<!-- 主內容區域 -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-center">
                <div class="card-body">
                    <?php if ($role === 'M'): ?>
                        <h1 class="display-4 text-primary">管理者您好</h1>
                    <?php else: ?>
                        <h1 class="display-4 text-primary">您好, <?php echo htmlspecialchars($nickname); ?>!</h1>
                    <?php endif; ?>
                    <p class="lead text-muted">歡迎來到 WEB 後端設計的地獄</p>
                    <p id="greeting" class="lead"></p>
                    <p id="current-time" class="lead"></p>
                    <p id="quote" class="lead text-muted"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 引入 Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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

<!-- 頁腳 -->
<footer class="text-center py-3 mt-5">
    <div class="container">
        <small>
            Copyright © 2024 輔大資管學系 二甲 陳庭毅 412401317<br>
            Copyright © 2024 輔大資管學系 二甲 吳宇燊 412261121
        </small>
    </div>
</footer>

</body>
</html>
