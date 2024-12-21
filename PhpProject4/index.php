<?php
require 'config.php';

// Получение новостей
$stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Новости</title>
</head>
<body>
    <h1>Новости</h1>
    <a href="admin.php">Добавить новость</a>
    <ul>
        <?php foreach ($posts as $post): ?>
            <li>
                <h3><a href="post.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a></h3>
                <span><?= $post['created_at'] ?></span>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>