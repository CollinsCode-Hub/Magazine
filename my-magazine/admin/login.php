<?php
require_once dirname(__DIR__).'/config/app.php';
if(is_logged_in()) redirect('admin/dashboard.php');
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();$email=trim($_POST['email']??'');$password=$_POST['password']??'';
 $stmt=$pdo->prepare("SELECT id,name,email,password,role FROM users WHERE email=? AND status='active' LIMIT 1");$stmt->execute([$email]);$user=$stmt->fetch();
 if($user && password_verify($password,$user['password'])){
  session_regenerate_id(true);unset($user['password']);$_SESSION['user']=$user;redirect('admin/dashboard.php');
 }else $error='Invalid email or password.';
}
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin Login | My Magazine</title><link rel="stylesheet" href="<?= url('assets/css/style.css') ?>"></head><body class="admin-body"><div class="login-card"><a class="brand" href="<?= url() ?>"><span class="brand-mark">M</span><span>My Magazine<small>ADMINISTRATION</small></span></a><h1>Welcome back</h1><p>Sign in to manage your publication.</p><?php if($error): ?><p class="error"><?= e($error) ?></p><?php endif; ?><form method="post"><?= csrf_field() ?><label>Email address<input type="email" name="email" required autofocus></label><label>Password<input type="password" name="password" required></label><button class="btn">Sign in</button></form><a class="back-link" href="<?= url() ?>">← Back to website</a></div></body></html>
