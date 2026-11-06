<?php
require_once __DIR__.'/_init.php';
$action=$_GET['action']??'list';$id=(int)($_GET['id']??0);$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();$op=$_POST['op']??'save';
 if($op==='delete'){require_role(['admin','editor']);$id=(int)($_POST['id']??0);$pdo->prepare('DELETE FROM articles WHERE id=?')->execute([$id]);redirect('admin/articles.php');}
 $title=trim($_POST['title']??'');$slug=slugify(trim($_POST['slug']??'')?:$title);$excerptText=trim($_POST['excerpt']??'');$content=$_POST['content']??'';$category=(int)($_POST['category_id']??0)?:null;$status=$_POST['status']??'draft';$featured=isset($_POST['is_featured'])?1:0;
 if(!in_array($status,['draft','pending','published'],true))$status='draft';
 if(!$title||!trim(strip_tags($content))){$error='Title and article content are required.';$action=$id?'edit':'new';}
 else{
  try{$image=upload_article_image($_FILES['featured_image']??[]);}catch(Throwable $ex){$error=$ex->getMessage();$action=$id?'edit':'new';$image=null;}
  if(!$error){
   if($id){$sql="UPDATE articles SET title=?,slug=?,excerpt=?,content=?,category_id=?,status=?,is_featured=?,updated_at=NOW()";$params=[$title,$slug,$excerptText,$content,$category,$status,$featured];if($image){$sql.=',featured_image=?';$params[]=$image;}$sql.=' WHERE id=?';$params[]=$id;$pdo->prepare($sql)->execute($params);}
   else{$pdo->prepare("INSERT INTO articles(title,slug,excerpt,content,category_id,author_id,status,is_featured,featured_image,published_at) VALUES(?,?,?,?,?,?,?,?,?,IF(?='published',NOW(),NULL))")->execute([$title,$slug,$excerptText,$content,$category,current_user()['id'],$status,$featured,$image,$status]);}
   redirect('admin/articles.php');
  }
 }
}
if($action==='edit'&&$id){$st=$pdo->prepare('SELECT * FROM articles WHERE id=?');$st->execute([$id]);$article=$st->fetch();if(!$article){$action='list';$error='Article not found.';}}
else $article=['title'=>'','slug'=>'','excerpt'=>'','content'=>'','category_id'=>'','status'=>'draft','is_featured'=>0,'featured_image'=>null];
$cats=$pdo->query('SELECT id,name FROM categories ORDER BY name')->fetchAll();
$adminTitle=$action==='new'?'New article':($action==='edit'?'Edit article':'Articles');
require __DIR__.'/header.php';
?>
<?php if($error): ?><p class="error"><?= e($error) ?></p><?php endif; ?>
<?php if(in_array($action,['new','edit'],true)): ?>
<div class="admin-panel"><form method="post" enctype="multipart/form-data" class="admin-form"><?= csrf_field() ?><input type="hidden" name="op" value="save"><div class="form-grid"><label>Article title<input name="title" value="<?= e($article['title']) ?>" required></label><label>URL slug<input name="slug" value="<?= e($article['slug']) ?>" placeholder="generated-from-title"></label><label>Category<select name="category_id"><option value="">Uncategorised</option><?php foreach($cats as $c): ?><option value="<?= $c['id'] ?>" <?= (string)$article['category_id']===(string)$c['id']?'selected':'' ?>><?= e($c['name']) ?></option><?php endforeach; ?></select></label><label>Status<select name="status"><?php foreach(['draft','pending','published'] as $s): ?><option value="<?= $s ?>" <?= $article['status']===$s?'selected':'' ?>><?= ucfirst($s) ?></option><?php endforeach; ?></select></label></div><label>Short excerpt<textarea name="excerpt" rows="2"><?= e($article['excerpt']) ?></textarea></label><label>Article content <small>HTML is accepted. Only trusted editors should be allowed to author raw HTML.</small><textarea name="content" rows="16" required><?= e($article['content']) ?></textarea></label><label>Featured image<input type="file" name="featured_image" accept="image/jpeg,image/png,image/webp,image/gif"></label><label class="check-label"><input type="checkbox" name="is_featured" value="1" <?= $article['is_featured']?'checked':'' ?>> Feature on homepage</label><button class="btn">Save article</button> <a class="back-link" href="<?= url('admin/articles.php') ?>">Cancel</a></form></div>
<?php else: ?><div class="admin-panel"><div class="panel-heading"><h2>All articles</h2><a class="btn btn-small" href="<?= url('admin/articles.php?action=new') ?>">+ New article</a></div><div class="table-wrap"><table><thead><tr><th>Title</th><th>Status</th><th>Featured</th><th>Created</th><th>Actions</th></tr></thead><tbody><?php foreach($pdo->query('SELECT * FROM articles ORDER BY created_at DESC') as $a): ?><tr><td><?= e($a['title']) ?></td><td><span class="status status-<?= e($a['status']) ?>"><?= e($a['status']) ?></span></td><td><?= $a['is_featured']?'Yes':'No' ?></td><td><?= format_date($a['created_at']) ?></td><td class="actions"><a href="<?= url('admin/articles.php?action=edit&id='.$a['id']) ?>">Edit</a><?php if(current_user()['role']==='admin'||current_user()['role']==='editor'): ?><form method="post" onsubmit="return confirm('Delete this article?')"><?= csrf_field() ?><input type="hidden" name="op" value="delete"><input type="hidden" name="id" value="<?= $a['id'] ?>"><button class="link-danger">Delete</button></form><?php endif; ?></td></tr><?php endforeach; ?></tbody></table></div></div><?php endif; ?>
<?php require __DIR__.'/footer.php'; ?>
