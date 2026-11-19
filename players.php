<?php require 'config.php'; $title='Jogadores'; $err=[]; $edit=null;
$positions=['Goleiro','Zagueiro','Lateral','Volante','Meia','Atacante'];
$teams=$pdo->query('SELECT id,name FROM teams ORDER BY name')->fetchAll();
if($_SERVER['REQUEST_METHOD']==='POST'){ require_login(); check_csrf(); $act=$_POST['action']??'';
 if($act==='delete'){ $pdo->prepare('DELETE FROM players WHERE id=?')->execute([(int)$_POST['id']]); flash('Jogador excluído.'); header('Location: players.php'); exit; }
 $n=trim($_POST['name']??''); $tid=(int)($_POST['team_id']??0); $pos=$_POST['position']??''; $num=$_POST['shirt_number']!==''?(int)$_POST['shirt_number']:null; $id=(int)($_POST['id']??0);
 if($n==='')$err[]='Informe o nome.'; if(!$tid)$err[]='Escolha um time.'; if(!in_array($pos,$positions))$err[]='Posição inválida.';
 if($num!==null&&($num<1||$num>99))$err[]='Camisa deve ser de 1 a 99.';
 if(!$err){
  if($act==='update') $pdo->prepare('UPDATE players SET name=?,team_id=?,position=?,shirt_number=? WHERE id=?')->execute([$n,$tid,$pos,$num,$id]);
  else $pdo->prepare('INSERT INTO players(name,team_id,position,shirt_number) VALUES(?,?,?,?)')->execute([$n,$tid,$pos,$num]);
  flash('Jogador salvo!'); header('Location: players.php'); exit; } }
if(isset($_GET['edit'])&&logged()){ $s=$pdo->prepare('SELECT * FROM players WHERE id=?');$s->execute([(int)$_GET['edit']]);$edit=$s->fetch(); }
$f=(int)($_GET['team']??0);
$q='SELECT p.*,t.name AS team FROM players p JOIN teams t ON t.id=p.team_id'.($f?' WHERE p.team_id=?':'').' ORDER BY t.name,p.shirt_number,p.name';
$s=$pdo->prepare($q);$s->execute($f?[$f]:[]);$list=$s->fetchAll();
include 'header.php'; ?>
<h1>Jogadores</h1>
<?php if(logged()): ?><div class="card"><h2><?= $edit?'Editar jogador':'Novo jogador' ?></h2>
<?php foreach($err as $x): ?><div class="alert err"><?= e($x) ?></div><?php endforeach; ?>
<?php if(!$teams): ?><p>Cadastre um time antes.</p><?php else: ?>
<form method="post" class="row"><?= csrf_field() ?>
<input type="hidden" name="action" value="<?= $edit?'update':'create' ?>"><input type="hidden" name="id" value="<?= e($edit['id']??'') ?>">
<label>Nome<input name="name" required value="<?= e($edit['name']??'') ?>"></label>
<label>Time<select name="team_id"><?php foreach($teams as $t): ?><option value="<?= $t['id'] ?>" <?= ($edit['team_id']??0)==$t['id']?'selected':'' ?>><?= e($t['name']) ?></option><?php endforeach; ?></select></label>
<label>Posição<select name="position"><?php foreach($positions as $p): ?><option <?= ($edit['position']??'')===$p?'selected':'' ?>><?= $p ?></option><?php endforeach; ?></select></label>
<label>Camisa<input type="number" name="shirt_number" min="1" max="99" value="<?= e($edit['shirt_number']??'') ?>"></label>
<button class="btn"><?= $edit?'Salvar':'Cadastrar' ?></button><?php if($edit): ?><a class="btn ghost" href="players.php">Cancelar</a><?php endif; ?></form><?php endif; ?></div><?php endif; ?>
<form method="get" class="filter"><label>Filtrar por time <select name="team" onchange="this.form.submit()"><option value="0">Todos</option>
<?php foreach($teams as $t): ?><option value="<?= $t['id'] ?>" <?= $f==$t['id']?'selected':'' ?>><?= e($t['name']) ?></option><?php endforeach; ?></select></label></form>
<div class="card"><table><thead><tr><th>Nº</th><th>Nome</th><th>Posição</th><th>Time</th><?php if(logged()): ?><th>Ações</th><?php endif; ?></tr></thead><tbody>
<?php foreach($list as $p): ?><tr><td><?= e($p['shirt_number']) ?></td><td><?= e($p['name']) ?></td><td><?= e($p['position']) ?></td><td><?= e($p['team']) ?></td>
<?php if(logged()): ?><td class="acts"><a class="btn small" href="players.php?edit=<?= $p['id'] ?>">Editar</a>
<form method="post" onsubmit="return confirm('Excluir jogador?')"><?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $p['id'] ?>"><button class="btn small danger">Excluir</button></form></td><?php endif; ?></tr>
<?php endforeach; if(!$list): ?><tr><td colspan="5">Nenhum jogador.</td></tr><?php endif; ?></tbody></table></div>
<?php include 'footer.php'; ?>
