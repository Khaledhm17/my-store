<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "sport_store";

// إنشاء اتصال
$conn = new mysqli($servername, $username, $password);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}

// إنشاء قاعدة البيانات إذا لم تكن موجودة
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
$conn->query($sql);

// تحديد قاعدة البيانات
$conn->select_db($dbname);

// إنشاء جدول المستخدمين إذا لم يكن موجودًا
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";
$conn->query($sql);

// إنشاء جدول المنتجات إذا لم يكن موجودًا
$sql = "CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) NOT NULL
)";
$conn->query($sql);
?>
