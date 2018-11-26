<?php
//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);
//require_once('../v2/classes/conexao.class.php');

//$conexao = new Conexao;
//$conn2 = $conexao->Conectar();

$conn = pg_connect("host='jb051' dbname='Jardim' user='jabot' password='#1808nccg#-@'");


$operacao = $_REQUEST['op'];
$id = $_REQUEST['id'];

$v = $_REQUEST['filtro'];
$lat = $_REQUEST['lat'];
$long = $_REQUEST['long'];

if ((empty($v)) && (empty($lat)))
{
	$v = 'xx';
}

$f = 'TODOS';
//echo "rafael";

$sql="
select t.codigobarras,t.codtestemunho,
det.aux_detpor,det.diadeterm,det.mesdeterm,det.anodeterm,
diaacesso1,mesacesso1,anoacesso1,
aux_nomecompunidgeo_invertido,
deta.descrlocal,
t.observacoes,
t.codcolbot,
pj.siglapj,
fam.nometaxon as familia,
gen.nometaxon as genero,
a.aux_nomecompltaxon,
deta.aux_coletprinc,
deta.aux_numcolprinc,
t.numtombo,
det.codcattypus,
ugp.aux_nomecomplunidgeo,
t.locfisico1,
t.locfisico2,
ct.nomecattypus,
ej.latitude,
ej.longitude,
t.desaparecido,
Y(replace(AsText(geom), 'MULTIPOINT', 'POINT')) as \"latitude\",
X(replace(AsText(geom), 'MULTIPOINT', 'POINT')) as \"longitude\",
imagem.arquivo

 from jabot.testemunho t
 left join jabot.imagem imagem on t.numtombo = cast(imagem.codigobarras as int) and imagem.siglacolbotorigem = 'rbv' and arquivo ilike '%_01.jpg'
 left join jabot.individuovivo iv on t.codtestemunho = iv.codindividuovivo

 left join publicacao.extracao_jabot ej on t.codtestemunho = ej.codtestemunho
 ,
jabot.determinacao det
 left join jabot.categoriatypus ct on det.codcattypus = ct.codcattypus,
jabot.arvoretaxon a
left join
jabot.arvoretaxon gen ON
(a.aux_genero = gen.codarvtaxon),
jabot.arvoretaxon fam,
jabot.detacesso deta,
jabot.unidgeopolitica ugp,
jabot.colecaobotanica cb,
jabot.basedados bd, 
jabot.tipocolbotanica tcb,
pessoajuridica pj,
geo.inventario_final p 
 ";

$sql.=" where ";

$sql.="
cast(p.inventario as int) = t.numtombo and
t.ultimadeterm = det.coddeterminacao and
t.codtipocolbot = tcb.codtipocolbot and
det.codarvtaxon = a.codarvtaxon and
t.codacesso = deta.coddetacesso and
a.aux_familia = fam.codarvtaxon and
deta.codunidgeo = ugp.codunidgeo and
t.codcolbot = cb.codcolecaobot and
t.codbasedados = bd.codbasedados and
cb.codcolecaobot = pj.codpj and
(t.desaparecido = 'F' or t.desaparecido is null or t.desaparecido = '')
and t.codcolbot = 4635

";
	if ((!empty($lat)) && (!empty($long)))
	{
		$fator = '0.00006';
		$sql_where = " and contains(Buffer(GeomFromText('SRID=4326;POINT(".$long." ".$lat.")'), 0.00006),p.geom) ";
  	}
	else
	{

		$sql_where .= " and upper(jabot.subst_caracsport('".str_replace(' ','-',$v)."')) in ( (select upper(jabot.subst_caracsport(nome_vulgar.nomevulgar)) from jabot.taxon_nome_vulgar, jabot.nome_vulgar 
                       where taxon_nome_vulgar.codnomevulgar = nome_vulgar.codnomevulgar 
                       and taxon_nome_vulgar.codarvtaxon = a.codarvtaxon group by 1 )) ";
	}

	$sql.= $sql_where;
	$sql.=' limit 50 ';
	
	

$res = pg_exec($conn,$sql);
$row = pg_fetch_all($res);

//$myObj->name = "John";
//$myObj->age = 30;
//$myObj->city = "New York";


/*$myObj->idchamado = utf8_encode($row['idchamado']);
$myObj->data= utf8_encode($row['data']);
$myObj->hora= utf8_encode($row['hora']);
$myObj->idusuario= utf8_encode($row['idusuario']);
$myObj->solicitante= utf8_encode($row['solicitante']);
$myObj->animal= utf8_encode($row['animal']);
$myObj->descricao= utf8_encode($row['descricao']);
$myObj->local= utf8_encode($row['local']);
$myObj->idsituacaochamado= utf8_encode($row['idsituacaochamado']);
$myObj->idsituacaoanimal= utf8_encode($row['idsituacaoanimal']);
$myObj->atendimento= utf8_encode($row['atendimento']);
$myObj->latitude= utf8_encode($row['latitude']);
$myObj->longitude= utf8_encode($row['longitude']);*/
$myJSON = json_encode($row);
//}
echo $myJSON;
?>