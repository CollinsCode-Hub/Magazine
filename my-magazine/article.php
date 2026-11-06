<?php
require_once __DIR__ . '/config/app.php';
$slug = trim($_GET['slug'] ?? '');
$stmt = $pdo->prepare("SELECT a.*, c.name category_name, c.slug category_slug, u.name author_name FROM articles a LEFT JOIN categories c ON c.id=a.category_id LEFT JOIN users u ON u.id=a.author_id WHERE a.slug=? AND a.status='published' AND a.published_at<=NOW() LIMIT 1");
$stmt->execute([$slug]); $article = $stmt->fetch();
if (!$article) { http_response_code(404); $pageTitle='Article not found'; require ROOT_PATH.'/includes/header.php'; echo '<div class="container empty-state"><h1>Story not found</h1><a class="text-link" href="'.url().'">Return home →</a></div>'; require ROOT_PATH.'/includes/footer.php'; exit; }
$pdo->prepare('UPDATE articles SET views=views+1 WHERE id=?')->execute([$article['id']]);
$pageTitle = $article['title'];
$commentsStmt=$pdo->prepare("SELECT name, comment, created_at FROM comments WHERE article_id=? AND status='approved' ORDER BY created_at DESC");
$commentsStmt->execute([$article['id']]); $comments=$commentsStmt->fetchAll();
$notice='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    verify_csrf();
    $name=trim($_POST['name']??''); $email=trim($_POST['email']??''); $comment=trim($_POST['comment']??'');
    if ($name && filter_var($email,FILTER_VALIDATE_EMAIL) && $comment) {
        $ins=$pdo->prepare("INSERT INTO comments(article_id,name,email,comment,status) VALUES(?,?,?,?, 'pending')");
        $ins->execute([$article['id'],$name,$email,$comment]); $notice='Thank you. Your comment has been submitted for moderation.';
    } else $notice='Please provide your name, a valid email and a comment.';
}
require ROOT_PATH.'/includes/header.php';
?>
<div class="container article-container"><div class="article-main"><div class="breadcrumbs"><a href="<?= url() ?>">Home</a> / <a href="<?= url('category.php?slug='.urlencode($article['category_slug']??'')) ?>"><?= e($article['category_name']??'News') ?></a></div>
<span class="category-label"><?= e($article['category_name']??'News') ?></span><h1 class="article-title"><?= e($article['title']) ?></h1><p class="article-deck"><?= e($article['excerpt']) ?></p><div class="byline article-byline">By <?= e($article['author_name']??'Editorial Team') ?> <span>•</span> <?= format_date($article['published_at']) ?> <span>•</span> <?= number_format((int)$article['views']+1) ?> views</div>
<img class="article-cover" src="<?= article_image($article['featured_image']) ?>" alt="<?= e($article['title']) ?>">
<div class="article-content"><?= $article['content'] ?></div>
<section class="comments"><h2>Join the conversation</h2><?php if($notice): ?><p class="notice"><?= e($notice) ?></p><?php endif; ?><form method="post" class="comment-form"><?= csrf_field() ?><div class="form-row"><label>Your name<input name="name" required maxlength="100"></label><label>Email address<input type="email" name="email" required maxlength="190"></label></div><label>Comment<textarea name="comment" rows="4" required maxlength="3000"></textarea></label><button class="btn" type="submit">Submit comment</button></form><h3>Comments (<?= count($comments) ?>)</h3><?php foreach($comments as $comment): ?><div class="comment-item"><b><?= e($comment['name']) ?></b><small><?= format_date($comment['created_at']) ?></small><p><?= nl2br(e($comment['comment'])) ?></p></div><?php endforeach; ?></section>
</div></div>
<?php require ROOT_PATH.'/includes/footer.php'; ?>
