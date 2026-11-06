</main>
<footer class="site-footer"><div class="container footer-grid">
<div><a class="brand footer-brand" href="<?= url() ?>"><span class="brand-mark">M</span><span><?= e(setting($pdo, 'site_name', APP_NAME)) ?><small>NEWS • CULTURE • IDEAS</small></span></a><p><?= e(setting($pdo, 'site_description', 'Independent stories, thoughtful perspectives and the latest headlines.')) ?></p></div>
<div><h3>Explore</h3><a href="<?= url() ?>">Home</a><a href="<?= url('search.php') ?>">Search</a><a href="<?= url('admin/login.php') ?>">Admin</a></div>
<div><h3>Stay informed</h3><p>Fresh perspectives, delivered through stories worth reading.</p></div>
</div><div class="container copyright">© <?= date('Y') ?> <?= e(setting($pdo, 'site_name', APP_NAME)) ?>. All rights reserved.</div></footer>
<script src="<?= url('assets/js/app.js') ?>"></script>
</body></html>
