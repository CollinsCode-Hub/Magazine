<?php
require_once __DIR__.'/config/app.php';
$count=(int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$message='';
if($count>0){exit('An administrator already exists. For security, delete install.php.');}
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf(); $name=trim($_POST['name']??'');$email=trim($_POST['email']??'');$pass=$_POST['password']??'';
 if($name && filter_var($email,FILTER_VALIDATE_EMAIL) && strlen($pass)>=12){
  $hash=password_hash($pass,PASSWORD_DEFAULT);$stmt=$pdo->prepare("INSERT INTO users(name,email,password,role,status) VALUES(?,?,?,'admin','active')");$stmt->execute([$name,$email,$hash]);
  $pdo->prepare("INSERT INTO settings(setting_key,setting_value) VALUES('site_name','My Magazine') ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)")->execute();
  $message='Administrator created. Delete install.php now, then sign in at admin/login.php.';
 }else $message='Enter a name, valid email and password of at least 12 characters.';
}
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Install My Magazine</title><link rel="stylesheet" href="<?= url('assets/css/style.css') ?>"></head><body class="admin-body"><div class="login-card"><span class="eyebrow">FIRST-TIME SETUP</span><h1>Create administrator</h1><?php if($message): ?><p class="notice"><?= e($message) ?></p><?php endif; ?><form method="post"><?= csrf_field() ?><label>Full name<input name="name" required></label><label>Email<input type="email" name="email" required></label><label>Password (minimum 12 characters)<input type="password" name="password" minlength="12" required></label><button class="btn">Create admin account</button></form></div></body></html>
