<?php require 'config.php'; $title='Classificação';
$teams=$pdo->query('SELECT id,name FROM teams')->fetchAll();
$t=[]; foreach($teams as $x) $t[$x['id']]=['name'=>$x['name'],'j'=>0,'v'=>0,'e'=>0,'d'=>0,'gp'=>0,'gc'=>0,'pts'=>0];
foreach($pdo->query('SELECT * FROM matches') as $m){
 $h=$m['home_team_id'];$a=$m['away_team_id']; if(!isset($t[$h])||!isset($t[$a]))continue;
 $hg=$m['home_goals'];$ag=$m['away_goals'];
 foreach([[$h,$hg,$ag],[$a,$ag,$hg]] as [$id,$gp,$gc]){
  $t[$id]['j']++;$t[$id]['gp']+=$gp;$t[$id]['gc']+=$gc;
  if($gp>$gc){$t[$id]['v']++;$t[$id]['pts']+=3;} elseif($gp==$gc){$t[$id]['e']++;$t[$id]['pts']+=1;} else $t[$id]['d']++; } }
usort($t,function($a,$b){
 return [$b['pts'],$b['v'],$b['gp']-$b['gc'],$b['gp'],$a['name']] <=> [$a['pts'],$a['v'],$a['gp']-$a['gc'],$a['gp'],$b['name']]; });
include 'header.php'; ?>
<h1> Tabela de classificação</h1>
<div class="card"><table><thead><tr><th>#</th><th>Time</th><th>Pts</th><th>J</th><th>V</th><th>E</th><th>D</th><th>GP</th><th>GC</th><th>SG</th></tr></thead><tbody>
<?php if(!$t): ?><tr><td colspan="10">Nenhum time cadastrado ainda.</td></tr><?php endif;
$i=0; foreach($t as $r): $i++; ?>
<tr class="<?= $i==1?'lead':'' ?>"><td><?= $i ?></td><td><?= e($r['name']) ?></td><td><b><?= $r['pts'] ?></b></td><td><?= $r['j'] ?></td><td><?= $r['v'] ?></td><td><?= $r['e'] ?></td><td><?= $r['d'] ?></td><td><?= $r['gp'] ?></td><td><?= $r['gc'] ?></td><td><?= $r['gp']-$r['gc'] ?></td></tr>
<?php endforeach; ?></tbody></table></div>
<?php include 'footer.php'; ?>
