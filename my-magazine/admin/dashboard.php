<?php
require_once __DIR__.'/_init.php';
$adminTitle='Dashboard';
$stats=[
 'Articles'=>(int)$pdo->query('SELECT COUNT(*) FROM articles')->fetchColumn(),
 'Published'=>(int)$pdo->query("SELECT COUNT(*) FROM articles WHERE status='published'")->fetchColumn(),
 'Categories'=>(int)$pdo->query('SELECT COUNT(*) FROM categories')->fetchColumn(),
 'Comments pending'=>(int)$pdo->query("SELECT COUNT(*) FROM comments WHERE status='pending'")->fetchColumn()
];
$recent=$pdo->query("SELECT a.id,a.title,a.status,a.created_at,c.name category_name FROM articles a LEFT JOIN categories c ON c.id=a.category_id ORDER BY a.created_at DESC LIMIT 8")->fetchAll();
require __DIR__.'/header.php';
?>
<div class="admin-welcome"><span class="eyebrow">PUBLICATION OVERVIEW</span><h1>Good to see you, <?= e(current_user()['name']) ?>.</h1><p>Here is what's happening with your magazine today.</p></div><div class="stats-grid"><?php foreach($stats as $label=>$value): ?><div class="stat-card"><span><?= e($label) ?></span><strong><?= number_format($value) ?></strong></div><?php endforeach; ?></div><div class="admin-panel"><div class="panel-heading"><h2>Recent articles</h2><a class="btn btn-small" href="<?= url('admin/articles.php?action=new') ?>">+ New article</a></div><div class="table-wrap"><table><thead><tr><th>Title</th><th>Category</th><th>Status</th><th>Created</th><th></th></tr></thead><tbody><?php foreach($recent as $r): ?><tr><td><?= e($r['title']) ?></td><td><?= e($r['category_name']??'—') ?></td><td><span class="status status-<?= e($r['status']) ?>"><?= e(ucfirst($r['status'])) ?></span></td><td><?= format_date($r['created_at']) ?></td><td><a href="<?= url('admin/articles.php?action=edit&id='.$r['id']) ?>">Edit</a></td></tr><?php endforeach; ?><?php if(!$recent): ?><tr><td colspan="5">No articles yet. Create your first story.</td></tr><?php endif; ?></tbody></table></div></div>
<?php require __DIR__.'/footer.php'; ?>
