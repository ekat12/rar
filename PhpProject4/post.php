<?php
require 'config.php';

// Проверка наличия ID
if (!isset($_GET['id'])) {
    die('Ошибка: новость не найдена.');
}

$id = $_GET['id'];

// Получение данных новости
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die('Новость не найдена.');
}

// Проверка отправки комментария
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_comment'])) {
    $author_name = trim($_POST['author_name']);
    $comment_content = trim($_POST['comment_content']);

    if ($author_name && $comment_content) {
        $stmt = $pdo->prepare("INSERT INTO comments (post_id, author_name, content) VALUES (?, ?, ?)");
        $stmt->execute([$id, $author_name, $comment_content]);
        header("Location: post.php?id=" . $id); // Перенаправление, чтобы избежать повторной отправки формы
        exit;
    } else {
        $error = "Пожалуйста, заполните все поля.";
    }
}

// Получение комментариев
$stmt = $pdo->prepare("SELECT author_name, content, created_at FROM comments WHERE post_id = ? ORDER BY created_at ASC");
$stmt->execute([$id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($post['title']) ?></title>
</head>
<body>
    <h1><?= htmlspecialchars($post['title']) ?></h1>
    <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
    <small>Дата публикации: <?= $post['created_at'] ?></small>
    <br><br>
    <a href="index.php">Назад к списку новостей</a>

    <hr>

    <!-- Форма для добавления комментария -->
    <h2>Оставить комментарий</h2>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    <form action="" method="POST">
        <label for="author_name">Ваше имя:</label><br>
        <input type="text" name="author_name" id="author_name" required><br><br>

        <label for="comment_content">Комментарий:</label><br>
        <textarea name="comment_content" id="comment_content" rows="5" cols="50" required></textarea><br><br>

        <button type="submit" name="add_comment">Отправить</button>
    </form>

    <hr>

    <!-- Список комментариев -->
    <h2>Комментарии:</h2>
    <?php if ($comments): ?>
        <?php foreach ($comments as $comment): ?>
            <div style="border: 1px solid #ccc; margin-bottom: 10px; padding: 10px;">
                <p><strong><?= htmlspecialchars($comment['author_name']) ?></strong> <small>(<?= $comment['created_at'] ?>)</small></p>
                <p><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Пока комментариев нет. Будьте первым!</p>
    <?php endif; ?>
</body>
</html>