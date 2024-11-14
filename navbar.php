<!-- navbar.php -->
<?php
require_once 'session.php';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
            <a class="navbar-brand" href="status.php">我的首頁</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="query.php">管理查詢</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php">管理員頁面</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="conference.php">會議頁面</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="fee.php">費用頁面</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">登出</a>
                    </li>
            </ul>
        </div>
    </div>
</nav>
