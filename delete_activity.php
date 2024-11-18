<?php
require_once 'session.php';
require_once 'db.php';

// 檢查是否提供活動 ID
if (!isset($_GET['id'])) {
    die("未提供活動 ID。");
}

$activityId = intval($_GET['id']); // 將活動 ID 轉換為整數，增加安全性

// 確認該活動是否存在
$checkQuery = "SELECT id FROM activity_logs WHERE id = ?";
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param("i", $activityId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // 刪除活動
    $deleteQuery = "DELETE FROM activity_logs WHERE id = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("i", $activityId);

    if ($deleteStmt->execute()) {
        $message = "活動已成功刪除。";
    } else {
        $message = "刪除活動失敗：" . $conn->error;
    }
} else {
    $message = "活動不存在。";
}

// 返回活動管理頁面
header("Location: activities.php?message=" . urlencode($message));
exit();
?>
