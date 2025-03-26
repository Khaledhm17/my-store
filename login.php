<?php
include "db.php"; // الاتصال بقاعدة البيانات
ob_start();
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // البحث عن المستخدم في قاعدة البيانات
    $sql = "SELECT id, username, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $username, $hashed_password);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
        // تسجيل الدخول بنجاح
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;

        // إعادة التوجيه إلى index.html
        header("Location: index.html");
        exit();
    } else {
        $error = "❌ البريد الإلكتروني أو كلمة المرور غير صحيحة!";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
    <h2>تسجيل الدخول</h2>
    <?php if (!empty($error)) echo "<p style='color: red;'>$error</p>"; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="البريد الإلكتروني" required><br>
        <input type="password" name="password" placeholder="كلمة المرور" required><br>
        <button type="submit">دخول</button>
    </form>
    <p>ليس لديك حساب؟ <a href="register.php">إنشاء حساب جديد</a></p>
</div>

</body>
</html>
