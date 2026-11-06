<?php
require_once __DIR__.'/config/app.php';
$q=trim($_GET['q']??''); $pageTitle=$q?'Search: '.$q:'Search stories'; $articles=[];
if($q!==''){$like='%'.$q.'%';$stmt=$pdo->prepare("SELECT a.*,c.name category_name FROM articles a LEFT JOIN categories c ON c.id=a.category_id WHERE a.status='published' AND a.published_at<=NOW() AND (a.title LIKE ? OR a.excerpt LIKE ? OR a.content LIKE ?) ORDER BY a.published_at DESC LIMIT 50");$stmt->execute([$like,$like,$like]);$articles=$stmt->fetchAll();}
require ROOT_PATH.'/includes/header.php';
?>
<div class="container page-container"><span class="eyebrow">FIND A STORY</span><h1>Search the magazine</h1><form class="search-large" method="get"><input name="q" value="<?= e($q) ?>" placeholder="What are you looking for?"><button class="btn">Search</button></form><?php if($q!==''): ?><p class="page-intro"><?= count($articles) ?> result(s) for “<?= e($q) ?>”</p><div class="article-grid"><?php foreach($articles as $a): ?><article class="article-card"><a class="card-image" href="<?= url('article.php?slug='.urlencode($a['slug'])) ?>"><img loading="lazy" src="<?= article_image($a['featured_image']) ?>" alt="<?= e($a['title']) ?>"></a><div class="card-body"><span class="category-label"><?= e($a['category_name']??'News') ?></span><h3><a href="<?= url('article.php?slug='.urlencode($a['slug'])) ?>"><?= e($a['title']) ?></a></h3><p><?= e(excerpt($a['excerpt']?:$a['content'])) ?></p></div></article><?php endforeach; ?></div><?php endif; ?></div>
<?php require ROOT_PATH.'/includes/footer.php'; ?>
