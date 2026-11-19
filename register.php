<?php require 'config.php'; $err=[];
if($_SERVER['REQUEST_METHOD']==='POST'){ check_csrf();
 $n=trim($_POST['name']??''); $m=trim($_POST['email']??''); $p=$_POST['password']??'';
 if($n==='')$err[]='Informe o nome.';
 if(!filter_var($m,FILTER_VALIDATE_EMAIL))$err[]='E-mail inválido.';
 if(strlen($p)<6)$err[]='A senha deve ter ao menos 6 caracteres.';
 if(!$err){ $s=$pdo->prepare('SELECT id FROM users WHERE email=?');$s->execute([$m]);
  if($s->fetch())$err[]='E-mail já cadastrado.'; else{
   $pdo->prepare('INSERT INTO users(name,email,password_hash) VALUES(?,?,?)')->execute([$n,$m,password_hash($p,PASSWORD_DEFAULT)]);
   flash('Conta criada! Faça login.'); header('Location: login.php'); exit; } } }
$title='Cadastro'; include 'header.php'; ?>
<div class="card narrow"><h1>Criar conta</h1>
<?php foreach($err as $x): ?><div class="alert err"><?= e($x) ?></div><?php endforeach; ?>
<form method="post"><?= csrf_field() ?>
<label>Nome<input name="name" required value="<?= e($_POST['name']??'') ?>"></label>
<label>E-mail<input type="email" name="email" required value="<?= e($_POST['email']??'') ?>"></label>
<label>Senha<input type="password" name="password" required minlength="6"></label>
<button class="btn">Cadastrar</button></form></div>
<?php include 'footer.php'; ?>
