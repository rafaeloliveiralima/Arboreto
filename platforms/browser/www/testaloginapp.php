<?php
$conn = pg_connect("host='jb051' dbname='Jardim' user='jabot' password='#1808nccg#-@'");
require_once 'usuario.class.php';
$Classe = new Usuario(); // <-- Alterar o nome da classe
$Classe->conn = $conn;

echo "rafael";
/*
//require_once 'classes/conexao.class.php';

//$conexao = new Conexao;
//$conn = $conexao->Conectar();




//$user = $_REQUEST['edtlogin'];
//$password = $_REQUEST['edtsenha'];
//$uuid = $_REQUEST['edtuuid'];


if ($Classe->autenticaApp($user, $password, $uuid)) {
   echo $Classe->idusuario.'|'.$Classe->nome;
} else {
   echo "ERRO";   
}
*/
?>