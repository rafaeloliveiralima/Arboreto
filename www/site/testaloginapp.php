<?php
require_once('conexao.class.php');
$conexao = new Conexao;
$conn = $conexao->Conectar();
require_once 'usuario.class.php';
$Classe = new Usuario(); // <-- Alterar o nome da classe
$Classe->conn = $conn;
$user = $_REQUEST['edtlogin'];
$password = $_REQUEST['edtsenha'];
$uuid = $_REQUEST['edtuuid'];
if ($Classe->autenticaApp($user, $password, $uuid)) {
   echo $Classe->idusuario.'|'.$Classe->nome;
} else {
   echo "ERRO";   
}


/*
//require_once 'classes/conexao.class.php';

//$conexao = new Conexao;
//$conn = $conexao->Conectar();




*/
?>