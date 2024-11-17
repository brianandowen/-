<?php require_once 'db.php';


?>
<?php
require_once 'session.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
        <?php if ($role === 'M'): ?>
                    <h1 class="display-4">管理者您好</h1>
                <?php else: ?>
                    <h1 class="display-4">您好, <?php echo htmlspecialchars($nickname); ?>!</h1>
                <?php endif; ?>
                <p class="lead">歡迎來到WEB後端設計的地獄</p>
            <p id="greeting"></p> <!-- 動態問候語 -->
            <p id="current-time"></p> <!-- 當前時間 -->
            <p id="quote" class="lead text-muted text-center"></p> <!-- 隨機名言 -->
            <p id="weather" class="text-center"></p> <!-- 天氣資訊 -->
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

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

</body>
<footer class="bg-light text-center py-3" style="position: fixed; bottom: 5%; width: 100%; text-align: center;">
    <small>
      Copyright © 2024 輔大資管學系 二甲 陳庭毅 412401317</br>
      Copyright © 2024 輔大資管學系 二甲 吳宇燊 412261121
    </small>
</footer>

</html>
