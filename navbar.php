<?php
require_once 'session.php';

// 獲取當前頁面檔案名稱
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <!-- 使用小邊距調整 -->
  <a class="navbar-brand" href="status.php" style="margin-left: 10px;">
    <?php echo ($current_page === 'status.php') ? '管理系統' : '回到首頁'; ?>
  </a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
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
        <a class="nav-link" href="logout.php" >登出</a>
      </li>
    </ul>
  </div>
</nav>
