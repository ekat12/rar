<?php
require 'config.php';

// Проверка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    // Проверка заполненности полей
    if ($title && $content) {
        $stmt = $pdo->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
        $stmt->execute([$title, $content]);
        echo "Новость успешно добавлена!";
    } else {
        echo "Пожалуйста, заполните все поля.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить новость</title>
</head>
<body>
    <h1>Добавить новость</h1>
    <form action="" method="POST">
        <label for="title">Заголовок:</label><br>
        <input type="text" name="title" id="title" required><br><br>

        <label for="content">Текст:</label><br>
        <textarea name="content" id="content" rows="10" cols="50" required></textarea><br><br>

        <button type="submit">Добавить</button>
    </form>
    <a href="index.php">Вернуться к новостям</a>
</body>
</html>