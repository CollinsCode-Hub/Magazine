<?php
require_once __DIR__.'/config/app.php';
$slug=trim($_GET['slug']??''); $stmt=$pdo->prepare('SELECT * FROM categories WHERE slug=? AND is_active=1'); $stmt->execute([$slug]); $category=$stmt->fetch();
if(!$category){http_response_code(404);exit('Category not found.');}
$pageTitle=$category['name']; $q=$pdo->prepare("SELECT a.*,u.name author_name FROM articles a LEFT JOIN users u ON u.id=a.author_id WHERE a.category_id=? AND a.status='published' AND a.published_at<=NOW() ORDER BY a.published_at DESC");$q->execute([$category['id']]);$articles=$q->fetchAll();
require ROOT_PATH.'/includes/header.php';
?>
<div class="container page-container"><span class="eyebrow">BROWSE TOPICS</span><h1><?= e($category['name']) ?></h1><p class="page-intro"><?= e($category['description']) ?></p><div class="article-grid"><?php foreach($articles as $a): ?><article class="article-card"><a class="card-image" href="<?= url('article.php?slug='.urlencode($a['slug'])) ?>"><img loading="lazy" src="<?= article_image($a['featured_image']) ?>" alt="<?= e($a['title']) ?>"></a><div class="card-body"><h3><a href="<?= url('article.php?slug='.urlencode($a['slug'])) ?>"><?= e($a['title']) ?></a></h3><p><?= e(excerpt($a['excerpt']?:$a['content'])) ?></p><div class="byline"><?= e($a['author_name']??'Editorial Team') ?> • <?= format_date($a['published_at']) ?></div></div></article><?php endforeach; ?></div><?php if(!$articles): ?><div class="empty-state">No published stories in this category yet.</div><?php endif; ?></div>
<?php require ROOT_PATH.'/includes/footer.php'; ?>
