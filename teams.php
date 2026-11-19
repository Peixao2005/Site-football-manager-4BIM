<?php require 'config.php'; $title='Times'; $err=[]; $edit=null;
if($_SERVER['REQUEST_METHOD']==='POST'){ require_login(); check_csrf();
 $act=$_POST['action']??'';
 if($act==='delete'){ $pdo->prepare('DELETE FROM teams WHERE id=?')->execute([(int)$_POST['id']]); flash('Time excluído.'); header('Location: teams.php'); exit; }
 $n=trim($_POST['name']??''); $c=trim($_POST['city']??''); $y=$_POST['founded_year']!==''?(int)$_POST['founded_year']:null; $id=(int)($_POST['id']??0);
 if($n==='')$err[]='Informe o nome do time.';
 if($y!==null&&($y<1850||$y>(int)date('Y')))$err[]='Ano de fundação inválido.';
 if(!$err){ try{
  if($act==='update') $pdo->prepare('UPDATE teams SET name=?,city=?,founded_year=? WHERE id=?')->execute([$n,$c,$y,$id]);
  else $pdo->prepare('INSERT INTO teams(name,city,founded_year,created_by) VALUES(?,?,?,?)')->execute([$n,$c,$y,$_SESSION['uid']]);
  flash('Time salvo!'); header('Location: teams.php'); exit;
 }catch(PDOException $x){ $err[]='Já existe um time com esse nome.'; } } }
if(isset($_GET['edit'])&&logged()){ $s=$pdo->prepare('SELECT * FROM teams WHERE id=?');$s->execute([(int)$_GET['edit']]);$edit=$s->fetch(); }
$list=$pdo->query('SELECT t.*,(SELECT COUNT(*) FROM players p WHERE p.team_id=t.id) AS np FROM teams t ORDER BY name')->fetchAll();
include 'header.php'; ?>
<h1>Times</h1>
<?php if(logged()): ?><div class="card"><h2><?= $edit?'Editar time':'Novo time' ?></h2>
<?php foreach($err as $x): ?><div class="alert err"><?= e($x) ?></div><?php endforeach; ?>
<form method="post" class="row"><?= csrf_field() ?>
<input type="hidden" name="action" value="<?= $edit?'update':'create' ?>"><input type="hidden" name="id" value="<?= e($edit['id']??'') ?>">
<label>Nome<input name="name" required value="<?= e($edit['name']??'') ?>"></label>
<label>Cidade<input name="city" value="<?= e($edit['city']??'') ?>"></label>
<label>Fundação<input type="number" name="founded_year" value="<?= e($edit['founded_year']??'') ?>"></label>
<button class="btn"><?= $edit?'Salvar':'Cadastrar' ?></button><?php if($edit): ?><a class="btn ghost" href="teams.php">Cancelar</a><?php endif; ?></form></div>
<?php else: ?><p class="note"><a href="login.php">Entre</a> para cadastrar e editar times.</p><?php endif; ?>
<div class="card"><table><thead><tr><th>Time</th><th>Cidade</th><th>Fundação</th><th>Jogadores</th><?php if(logged()): ?><th>Ações</th><?php endif; ?></tr></thead><tbody>
<?php foreach($list as $t): ?><tr><td><?= e($t['name']) ?></td><td><?= e($t['city']) ?></td><td><?= e($t['founded_year']) ?></td><td><?= $t['np'] ?></td>
<?php if(logged()): ?><td class="acts"><a class="btn small" href="teams.php?edit=<?= $t['id'] ?>">Editar</a>
<form method="post" onsubmit="return confirm('Excluir time e seus jogadores/partidas?')"><?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $t['id'] ?>"><button class="btn small danger">Excluir</button></form></td><?php endif; ?></tr>
<?php endforeach; if(!$list): ?><tr><td colspan="5">Nenhum time.</td></tr><?php endif; ?></tbody></table></div>
<?php include 'footer.php'; ?>
