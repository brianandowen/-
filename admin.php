<?php
require_once 'db.php';
require_once 'session.php';

// 處理刪除操作
if (isset($_GET['delete_user'])) {
    $userToDelete = $_GET['delete_user'];

    // 確認該使用者的角色為 'U'
    $checkQuery = "SELECT role FROM user WHERE account = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $userToDelete);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if ($user['role'] === 'U') {
            $deleteQuery = "DELETE FROM user WHERE account = ?";
            $deleteStmt = $conn->prepare($deleteQuery);
            $deleteStmt->bind_param("s", $userToDelete);
            $deleteStmt->execute();
            $deleteMessage = "使用者已成功刪除。";
        } else {
            $deleteMessage = "無法刪除管理者帳戶。";
        }
    } else {
        $deleteMessage = "使用者不存在。";
    }
}

// 處理更新操作
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_account'])) {
    $edit_account = $_POST['edit_account'];
    $new_account = $_POST['new_account'];
    $new_password = $_POST['new_password'];

    // 更新使用者帳號和密碼
    $update_query = "UPDATE user SET account = ?, password = ? WHERE account = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("sss", $new_account, $new_password, $edit_account);

    if ($update_stmt->execute()) {
        $updateMessage = "使用者資料已更新成功！";
    } else {
        $updateMessage = "更新失敗，請稍後再試。";
    }
}

// 查詢所有使用者資料
$query = "SELECT account, password, role, name, created_at FROM user";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("查詢失敗：" . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者頁面</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">使用者管理介面</h2>
    
    <?php if (isset($deleteMessage)): ?>
        <div class="alert <?= strpos($deleteMessage, '成功刪除') !== false ? 'alert-success' : 'alert-danger' ?>" role="alert">
            <?= htmlspecialchars($deleteMessage); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($updateMessage)): ?>
        <div class="alert <?= strpos($updateMessage, '成功') !== false ? 'alert-success' : 'alert-danger' ?>" role="alert">
            <?= htmlspecialchars($updateMessage); ?>
        </div>
    <?php endif; ?>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>帳號</th>
                <th>密碼</th>
                <th>名稱</th>
                <th>角色</th>
                <th>註冊日期</th>
                <th>操作</th>
            </tr>
        </thead>
        <a href="status.php" class="btn btn-primary w-100">回首頁</a>

        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?= htmlspecialchars($row['account']) ?></td>
                    <td><?= htmlspecialchars($row['password']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['role'] === 'M' ? '管理者' : '使用者') ?></td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                    <td>
                        <?php if ($row['role'] === 'U'): ?>
                            <!-- 編輯按鈕，打開編輯模態框 -->
                            <button class="btn btn-warning btn-sm" onclick="openEditModal('<?= $row['account'] ?>', '<?= $row['name'] ?>')">編輯</button>
                            <a href="admin.php?delete_user=<?= urlencode($row['account']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('確定刪除此使用者？');">刪除</a>
                        <?php else: ?>
                            <span>管理者帳戶</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- 編輯模態框 -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">編輯使用者資料</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="admin.php" method="POST">
          <input type="hidden" name="edit_account" id="edit_account">
          <div class="mb-3">
              <label for="new_account" class="form-label">新帳號</label>
              <input type="text" id="new_account" name="new_account" class="form-control" required>
          </div>
          <div class="mb-3">
              <label for="new_password" class="form-label">新密碼</label>
              <input type="password" id="new_password" name="new_password" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">儲存變更</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function openEditModal(account, name) {
        document.getElementById('edit_account').value = account;
        document.getElementById('new_account').value = account;  // 預填當前帳號
        document.getElementById('editModal').style.display = 'block';
        var modal = new bootstrap.Modal(document.getElementById('editModal'));
        modal.show();
    }
</script>
</body>
</html>

<?php
mysqli_close($conn);
?>
