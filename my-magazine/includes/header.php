<?php
$pageTitle = $pageTitle ?? APP_NAME;
$siteName = setting($pdo, 'site_name', APP_NAME);
$categories = $pdo->query('SELECT id, name, slug FROM categories WHERE is_active = 1 ORDER BY name LIMIT 8')->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle) ?> | <?= e($siteName) ?></title>
<meta name="description" content="<?= e(setting($pdo, 'site_description', 'News, stories and ideas that matter.')) ?>">
<link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
</head>
<body>
<div class="topbar"><div class="container topbar-inner"><span><?= date('l, F j, Y') ?></span><span>Stories that matter. Perspectives that inspire.</span></div></div>
<header class="site-header"><div class="container header-inner">
<a class="brand" href="<?= url() ?>"><span class="brand-mark">M</span><span><?= e($siteName) ?><small>NEWS • CULTURE • IDEAS</small></span></a>
<button class="menu-toggle" aria-label="Toggle navigation" onclick="document.querySelector('.main-nav').classList.toggle('open')">☰</button>
<nav class="main-nav"><a href="<?= url() ?>">Home</a><?php foreach ($categories as $cat): ?><a href="<?= url('category.php?slug=' . urlencode($cat['slug'])) ?>"><?= e($cat['name']) ?></a><?php endforeach; ?></nav>
<form class="header-search" action="<?= url('search.php') ?>" method="get"><input name="q" placeholder="Search stories..." aria-label="Search"><button>⌕</button></form>
</div></header>
<main>
