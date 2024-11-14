<?php
require_once 'session.php'; // 包含 session 驗證邏輯
require_once 'db.php';

// 確認是否提供了要刪除的資料 ID
if (!isset($_GET['id'])) {
    header("Location: query.php?message=未提供要刪除的資料 ID");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

// 執行刪除操作
$query = "DELETE FROM members WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: query.php?message=刪除成功"); // 成功後返回查詢頁面
    exit();
} else {
    header("Location: query.php?message=刪除失敗，請稍後再試");
    exit();
}
?>
