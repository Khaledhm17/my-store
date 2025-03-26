<?php
include "db.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // تشفير كلمة المرور

    // التحقق مما إذا كان البريد الإلكتروني مسجلاً مسبقًا
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "❌ البريد الإلكتروني مسجل بالفعل!";
    } else {
        // إدخال المستخدم الجديد
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $password);

        if ($stmt->execute()) {
            // توجيه المستخدم إلى صفحة تسجيل الدخول بعد التسجيل
            header("Location: login.php");
            exit();
        } else {
            $error = "❌ حدث خطأ أثناء التسجيل!";
        }
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تسجيل حساب جديد</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
    <h2>تسجيل حساب جديد</h2>
    <?php if (!empty($error)) echo "<p style='color: red;'>$error</p>"; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="اسم المستخدم" required><br>
        <input type="email" name="email" placeholder="البريد الإلكتروني" required><br>
        <input type="password" name="password" placeholder="كلمة المرور" required><br>
        <button type="submit">تسجيل</button>
    </form>
    <p>لديك حساب؟ <a href="login.php">تسجيل الدخول</a></p>
</div>

</body>
</html>
