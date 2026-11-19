<?php require_once __DIR__.'/config.php'; $flash=flash(); ?>
<!DOCTYPE html><html lang="pt-br"><head><meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Football Manager<?= isset($title)?' - '.e($title):'' ?></title>
<link rel="stylesheet" href="style.css"></head><body>
<header class="top"><div class="wrap nav">
<a class="logo" href="index.php"> Football Manager</a>
<nav><a href="index.php">Classificação</a><a href="teams.php">Times</a><a href="players.php">Jogadores</a><a href="matches.php">Partidas</a>
<?php if(logged()): ?><span class="user">Olá, <?= e($_SESSION['uname']) ?></span><a href="logout.php">Sair</a>
<?php else: ?><a href="login.php">Entrar</a><a class="btn small" href="register.php">Cadastrar</a><?php endif; ?></nav></div></header>
<main class="wrap">
<?php if($flash): ?><div class="alert"><?= e($flash) ?></div><?php endif; ?>
