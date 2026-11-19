<?php
// Ajuste conforme seu MySQL (XAMPP: root sem senha)
const DB_HOST='localhost'; const DB_NAME='football_manager'; const DB_USER='root'; const DB_PASS='';
session_start();
try{
 $pdo=new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",DB_USER,DB_PASS,[
  PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
}catch(PDOException $e){ die("Erro de conexão. Importe o database.sql e confira o config.php."); }
function e($s){return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8');}
function logged(){return isset($_SESSION['uid']);}
function require_login(){ if(!logged()){header('Location: login.php');exit;} }
function csrf(){ if(empty($_SESSION['csrf']))$_SESSION['csrf']=bin2hex(random_bytes(16)); return $_SESSION['csrf']; }
function csrf_field(){return '<input type="hidden" name="csrf" value="'.csrf().'">';}
function check_csrf(){ if(!hash_equals($_SESSION['csrf']??'',$_POST['csrf']??'')) die('Token inválido.'); }
function flash($m=null){ if($m!==null){$_SESSION['flash']=$m;return;} $f=$_SESSION['flash']??null; unset($_SESSION['flash']); return $f; }
