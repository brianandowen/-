<?php
require_once 'session.php';

// 獲取當前頁面檔案名稱
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg">
<div class="container">
    <!-- 使用品牌名稱與校徽 -->
    <a class="navbar-brand  align-items-center" href="status.php">
      <img src="校徽.png" alt="校徽" height="50" class="me-2"> <!-- 替換為你的校徽圖片 -->
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto"> <!-- 將導航選項對齊右側 -->
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page === 'query.php') ? 'active' : ''; ?>" href="query.php">會員管理</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page === 'activities.php') ? 'active' : ''; ?>" href="activities.php">活動管理</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page === 'fee.php') ? 'active' : ''; ?>" href="fee.php">財務管理</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-danger" href="logout.php">登出</a> <!-- 使用紅色突出登出按鈕 -->
        </li>
      </ul>
    </div>
  </div>
</nav>



