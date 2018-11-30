<?php

//require_once 'classes/conexao.class.php';
require_once 'classes/usuario.class.php';

//$conexao = new Conexao;
//$conn = $conexao->Conectar();

$conn = pg_connect("host='jb051' dbname='Jardim' user='jabot' password='#1808nccg#-@'");


$Classe = new UsuarioFauna(); // <-- Alterar o nome da classe
$Classe->conn = $conn;

$user = $_REQUEST['edtlogin'];
$password = $_REQUEST['edtsenha'];
$uuid = $_REQUEST['edtuuid'];

if ($Classe->autenticaApp($user, $password, $uuid)) {
   echo $Classe->idusuario.'|'.$Classe->nome.'|'.$Classe->idtipousuario.'|'.$Classe->tipousuario.'|'.$Classe->idsituacaousuario.'|';
} else {
   echo "ERRO";   
}
?>