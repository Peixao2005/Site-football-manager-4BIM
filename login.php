<?php require 'config.php'; $err='';
if($_SERVER['REQUEST_METHOD']==='POST'){ check_csrf();
 $s=$pdo->prepare('SELECT * FROM users WHERE email=?');$s->execute([trim($_POST['email']??'')]);$u=$s->fetch();
 if($u && password_verify($_POST['password']??'',$u['password_hash'])){
  session_regenerate_id(true); $_SESSION['uid']=$u['id']; $_SESSION['uname']=$u['name'];
  if(password_needs_rehash($u['password_hash'],PASSWORD_DEFAULT))
   $pdo->prepare('UPDATE users SET password_hash=? WHERE id=?')->execute([password_hash($_POST['password'],PASSWORD_DEFAULT),$u['id']]);
  flash('Bem-vindo!'); header('Location: index.php'); exit;
 } else $err='E-mail ou senha incorretos.'; }
$title='Login'; include 'header.php'; ?>
<div class="card narrow"><h1>Entrar</h1>
<?php if($err): ?><div class="alert err"><?= e($err) ?></div><?php endif; ?>
<form method="post"><?= csrf_field() ?>
<label>E-mail<input type="email" name="email" required></label>
<label>Senha<input type="password" name="password" required></label>
<button class="btn">Entrar</button></form>
<p>Não tem conta? <a href="register.php">Cadastre-se</a></p></div>
<?php include 'footer.php'; ?>
