<?php
include "db.php"; // استيراد الاتصال بقاعدة البيانات

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $price = trim($_POST["price"]);
    
    // التحقق من أن الصورة تم تحميلها
    if (!empty($name) && !empty($price) && isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $image_name = time() . "_" . basename($_FILES["image"]["name"]); // تغيير اسم الصورة لتجنب التكرار
        $target_dir = "uploads/"; // مجلد حفظ الصور
        $target_file = $target_dir . $image_name;

        // التأكد من أن الملف صورة فقط
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ["jpg", "jpeg", "png", "gif"];

        if (in_array($imageFileType, $allowed_types)) {
            // رفع الصورة
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // حفظ المسار في قاعدة البيانات
                $stmt = $conn->prepare("INSERT INTO products (name, price, image) VALUES (?, ?, ?)");
                $stmt->bind_param("sds", $name, $price, $target_file);

                if ($stmt->execute()) {
                    $message = "<p class='success'>✅ تمت إضافة المنتج بنجاح!</p>";
                } else {
                    $message = "<p class='error'>❌ خطأ في الإضافة: " . $conn->error . "</p>";
                }
                $stmt->close();
            } else {
                $message = "<p class='error'>❌ فشل رفع الصورة.</p>";
            }
        } else {
            $message = "<p class='error'>❌ فقط ملفات JPG, JPEG, PNG, GIF مسموح بها.</p>";
        }
    } else {
        $message = "<p class='error'>❌ جميع الحقول مطلوبة!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة منتج جديد</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin: 50px;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: auto;
        }
        h2 {
            color: #333;
        }
        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .success {
            color: green;
            font-weight: bold;
        }
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>إضافة منتج جديد</h2>
        <?= $message; ?>

        <form method="POST" enctype="multipart/form-data">
            <label>اسم المنتج:</label>
            <input type="text" name="name" required>

            <label>السعر:</label>
            <input type="number" name="price" step="0.01" required>

            <label>صورة المنتج:</label>
            <input type="file" name="image" accept="image/*" required>

            <button type="submit">إضافة المنتج</button>
        </form>
    </div>

</body>
</html>
