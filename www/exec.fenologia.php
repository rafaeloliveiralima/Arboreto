<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);
require_once('conexao.class.php');

$conexao = new Conexao;
$conn = $conexao->Conectar();

$chkboxfen = $_REQUEST['id'];
$codtestemunho = $_REQUEST['codtestemunho'];
$codusuario = $_REQUEST['codusuario'];

$array = explode(',', $chkboxfen);
$sql = '';
foreach($array as $valores)
{
   $sql.= 'delete from jabot.fenologia where data = now() and codtestemunho = '.$codtestemunho.' and idcadastro = '.$valores.';';
   $sql.= 'insert into jabot.fenologia (codtestemunho,idcadastro,codusuario) values ('.$codtestemunho.','.$valores.','.$codusuario.');';
}

//echo $sql;

$res = pg_exec($conn,$sql);
if ($res===false)
{
	echo 'ERRO';
}
else
{
	echo 'OK';
}

?>