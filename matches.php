<?php require 'config.php'; $title='Partidas'; $err=[]; $edit=null;
$teams=$pdo->query('SELECT id,name FROM teams ORDER BY name')->fetchAll();
if($_SERVER['REQUEST_METHOD']==='POST'){ require_login(); check_csrf(); $act=$_POST['action']??'';
 if($act==='delete'){ $pdo->prepare('DELETE FROM matches WHERE id=?')->execute([(int)$_POST['id']]); flash('Partida excluída.'); header('Location: matches.php'); exit; }
 $h=(int)($_POST['home']??0); $a=(int)($_POST['away']??0); $hg=$_POST['hg']??''; $ag=$_POST['ag']??''; $d=$_POST['played_on']??''; $id=(int)($_POST['id']??0);
 if(!$h||!$a)$err[]='Escolha os dois times.'; if($h===$a)$err[]='Os times devem ser diferentes.';
 if(!ctype_digit((string)$hg)||!ctype_digit((string)$ag)||$hg>99||$ag>99)$err[]='Placar inválido.';
 if(!preg_match('/^\d{4}-\d{2}-\d{2}$/',$d))$err[]='Data inválida.';
 if(!$err){
  if($act==='update') $pdo->prepare('UPDATE matches SET home_team_id=?,away_team_id=?,home_goals=?,away_goals=?,played_on=? WHERE id=?')->execute([$h,$a,$hg,$ag,$d,$id]);
  else $pdo->prepare('INSERT INTO matches(home_team_id,away_team_id,home_goals,away_goals,played_on) VALUES(?,?,?,?,?)')->execute([$h,$a,$hg,$ag,$d]);
  flash('Partida salva!'); header('Location: matches.php'); exit; } }
if(isset($_GET['edit'])&&logged()){ $s=$pdo->prepare('SELECT * FROM matches WHERE id=?');$s->execute([(int)$_GET['edit']]);$edit=$s->fetch(); }
$list=$pdo->query('SELECT m.*,h.name hn,a.name an FROM matches m JOIN teams h ON h.id=m.home_team_id JOIN teams a ON a.id=m.away_team_id ORDER BY played_on DESC,m.id DESC')->fetchAll();
function opts($teams,$sel){ foreach($teams as $t) echo '<option value="'.$t['id'].'"'.($sel==$t['id']?' selected':'').'>'.e($t['name']).'</option>'; }
include 'header.php'; ?>
<h1>Partidas</h1>
<?php if(logged()): ?><div class="card"><h2><?= $edit?'Editar partida':'Registrar partida' ?></h2>
<?php foreach($err as $x): ?><div class="alert err"><?= e($x) ?></div><?php endforeach; ?>
<?php if(count($teams)<2): ?><p>Cadastre ao menos 2 times.</p><?php else: ?>
<form method="post" class="row"><?= csrf_field() ?>
<input type="hidden" name="action" value="<?= $edit?'update':'create' ?>"><input type="hidden" name="id" value="<?= e($edit['id']??'') ?>">
<label>Mandante<select name="home"><?php opts($teams,$edit['home_team_id']??0); ?></select></label>
<label>Gols<input type="number" name="hg" min="0" max="99" required value="<?= e($edit['home_goals']??'') ?>"></label>
<label>Gols<input type="number" name="ag" min="0" max="99" required value="<?= e($edit['away_goals']??'') ?>"></label>
<label>Visitante<select name="away"><?php opts($teams,$edit['away_team_id']??0); ?></select></label>
<label>Data<input type="date" name="played_on" required value="<?= e($edit['played_on']??date('Y-m-d')) ?>"></label>
<button class="btn"><?= $edit?'Salvar':'Registrar' ?></button><?php if($edit): ?><a class="btn ghost" href="matches.php">Cancelar</a><?php endif; ?></form><?php endif; ?></div>
<?php else: ?><p class="note"><a href="login.php">Entre</a> para registrar partidas.</p><?php endif; ?>
<div class="card"><table><thead><tr><th>Data</th><th>Partida</th><?php if(logged()): ?><th>Ações</th><?php endif; ?></tr></thead><tbody>
<?php foreach($list as $m): ?><tr><td><?= e(date('d/m/Y',strtotime($m['played_on']))) ?></td>
<td><?= e($m['hn']) ?> <b class="score"><?= $m['home_goals'] ?> x <?= $m['away_goals'] ?></b> <?= e($m['an']) ?></td>
<?php if(logged()): ?><td class="acts"><a class="btn small" href="matches.php?edit=<?= $m['id'] ?>">Editar</a>
<form method="post" onsubmit="return confirm('Excluir partida?')"><?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $m['id'] ?>"><button class="btn small danger">Excluir</button></form></td><?php endif; ?></tr>
<?php endforeach; if(!$list): ?><tr><td colspan="3">Nenhuma partida.</td></tr><?php endif; ?></tbody></table></div>
<?php include 'footer.php'; ?>
