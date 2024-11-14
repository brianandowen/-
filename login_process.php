<?php
require_once 'db.php'; // 確保 db.php 能正確連接到資料庫
require_once 'session.php';
// 顯示所有錯誤，方便除錯
error_reporting(E_ALL);
ini_set('display_errors', 1);

$errorMessage = ''; // 儲存錯誤訊息

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 從表單接收使用者輸入的帳號和密碼
    $account = $_POST['account'];
    $password = $_POST['password'];

    // 使用準備語句查詢帳號
    $stmt = $conn->prepare("SELECT * FROM user WHERE account = ?");
    if (!$stmt) {
        die("準備語句失敗：" . $conn->error); // 顯示 SQL 錯誤
    }
    $stmt->bind_param("s", $account);
    $stmt->execute();
    $result = $stmt->get_result();

    // 檢查查詢結果
    if ($result && $result->num_rows > 0) {
        // 找到匹配的帳號
        $user = $result->fetch_assoc();

        // 驗證密碼是否正確
        if ($password === $user['password']) {  // 如果尚未加密密碼，這樣驗證
            $_SESSION['username'] = $account;
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];
            header('Location: status.php'); // 導向狀態頁面
            exit();
        } else {
            $errorMessage = '密碼錯誤，請重新登入。';
        }
    } else {
        $errorMessage = '帳號不存在，請重新登入。';
    }
    $stmt->close();
}

$conn->close();

// 若有錯誤訊息，將其存入 session，並重導向到登入頁面顯示
if ($errorMessage) {
    $_SESSION['errorMessage'] = $errorMessage;
    header('Location: login.php'); // 返回登入頁面
    exit();
}
?>
