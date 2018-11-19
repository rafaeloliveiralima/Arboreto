<?php
require_once('classes/conexao.class.php');

$conexao = new Conexao;
$conn2 = $conexao->Conectar();

$conn = pg_connect("host='jb051' dbname='Jardim' user='jabot' password='#1808nccg#-@'");


$operacao = $_REQUEST['op'];
$id = $_REQUEST['id'];

$v = $_REQUEST['filtro'];
$f = 'TODOS';

//$sql = 'select * from fauna.chamado where latitude is not null and longitude is not null';

$sql = "select  
a.codarvtaxon as \"Cód. Táxon\",
t.numtombo as inventario, 
Y(replace(AsText(geom), 'MULTIPOINT', 'POINT')) as \"latitude\",
X(replace(AsText(geom), 'MULTIPOINT', 'POINT')) as \"longitude\", 
a.aux_nomecompltaxhtml as aux_nomecompltaxhtml, 
a.aux_nomecompltaxon, 
t.locfisico1 || t.locfisico2 as loc, 
t.codtestemunho,

 from jabot.testemunho t, 
 jabot.determinacao det, 
 jabot.detacesso de, 
 jabot.arvoretaxon a, 
 geo.inventario_final p 
  where 
 cast(p.inventario as int) = t.numtombo and
 t.codacesso = deta.coddetacesso and 
 det.codarvtaxon = a.codarvtaxon and 
 t.ultimadeterm = det.coddeterminacao and t.codbasedados = 8099 and
 det.codarvtaxon = a.codarvtaxon and
  (t.desaparecido = 'F' or t.desaparecido is null or t.desaparecido = '') ";
$sql="
select t.codigobarras,t.codtestemunho,
det.aux_detpor,det.diadeterm,det.mesdeterm,det.anodeterm,
aux_coletprinc,
diaacesso1,mesacesso1,anoacesso1,
aux_numcolprinc,
aux_nomecompunidgeo_invertido,
deta.descrlocal,
t.observacoes,
t.desaparecido,
t.codcolbot,
qtdestoqueduplic,
pj.siglapj,
fam.nometaxon as familia,
gen.nometaxon as genero,
a.aux_nomecompltaxon,
deta.aux_coletprinc,
deta.aux_numcolprinc,
t.numtombo,
det.codcattypus,
ugp.aux_nomecomplunidgeo,
cb.codtipocolbot,
t.locfisico1,
t.locfisico2,
t.codbasedados,
ct.nomecattypus,
tcb.nomeespecime,
ej.latitude,
ej.longitude,
t.desaparecido,
t.numtombo,
t.dataultalter,
t.alteradopor,
Y(replace(AsText(geom), 'MULTIPOINT', 'POINT')) as \"latitude\",
X(replace(AsText(geom), 'MULTIPOINT', 'POINT')) as \"longitude\"

 from jabot.testemunho t left join jabot.individuovivo iv on t.codtestemunho = iv.codindividuovivo
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
	/*if ((!empty($lat)) && (!empty($long)) && (empty($v))  )
	{
		$fator = '0.00006';
		if (!empty($ml))
		{
		   $fator = '0.00006';
		}
		$utilizaoutrascolecao = false;

		$sql_where = " and contains(Buffer(GeomFromText('SRID=4326;POINT(".$long." ".$lat.")'), 0.00006),p.geom) ";
  	}
*/

	if ($f == 'TODOS')
	{
		if (!empty($v))
		{
			$sql_where .= " and (a.aux_nomecompltaxon ilike '%".$v."%'";

			$sql_array.="  or t.numtombo = ".$v." ";


			$sql_where .= " or deta.aux_coletprinc ilike '%".$v."%'";

			list($secao,$canteiro) = split ("[/.-]", $v, 5);
			if (!empty($secao))
			{
				$sql_where.= " or upper(trim(t.locfisico1)) = upper(trim('".$secao."')) ";
			}
			if (!empty($canteiro))
			{
				$sql_where.= " or upper(trim(t.locfisico2)) = upper(trim('".$canteiro."'))";
			}
			$sql_where .= " or upper(jabot.subst_caracsport('".str_replace(' ','-',$v)."')) in ( (select upper(jabot.subst_caracsport(nome_vulgar.nomevulgar)) from jabot.taxon_nome_vulgar, jabot.nome_vulgar 
                       where taxon_nome_vulgar.codnomevulgar = nome_vulgar.codnomevulgar 
                       and taxon_nome_vulgar.codarvtaxon = a.codarvtaxon group by 1 )) ";
			
			$sql_where.=') ';
		}
	}

/*	if ($f == 'TODOS')
	{
	    if (!empty($v))
		{
				$array_v = split(",", $v, -1);
				$sql_array = ' and ('; 
				foreach ( $array_v as $valor ) {
				$sql_array.=" t.numtombo = ".$valor." OR";
				}
				$sql_array.=') ';
				$sql_array = str_replace('OR)',')',$sql_array);
				$sql_where.=$sql_array;
		}
	}

	if ($f == 'TODOS')
	{
		$sql_where .= " and deta.aux_coletprinc ilike '%".$v."%'";
	}

    if ($f == 'TODOS')
	{
	 	if (!empty($v))
		{			
			$sql_where .= ' and deta.aux_numcolprinc = \''.$v.'\' ';
		}
	}
	
	if ($f == 'LOCALIZACAO')
	{
		list($secao,$canteiro) = split ("[/.-]", $v, 5);
		if (!empty($secao))
		{
			$sql_where.= " AND upper(trim(t.locfisico1)) = upper(trim('".$secao."')) ";
		}
		if (!empty($canteiro))
		{
			$sql_where.= " AND upper(trim(t.locfisico2)) = upper(trim('".$canteiro."'))";
		}
	}
	
	if ($f == 'TODOS')
	{
//		$sql_where .= " and upper(jabot.subst_caracsport('".str_replace(' ','-',$v)."')) in ( (select upper(jabot.subst_caracsport(nome_vulgar.nomevulgar)) from jabot.taxon_nome_vulgar, jabot.nome_vulgar 
//                       where taxon_nome_vulgar.codnomevulgar = nome_vulgar.codnomevulgar 
//                       and taxon_nome_vulgar.codarvtaxon = a.codarvtaxon group by 1 )) ";
	}
	

	//if ((empty($lat)) && (empty($long)) && (empty($v)) && (empty($edtpoligono2)) )
	//{
	//	$sql.=' and t.codtestemunho = 0 '; // FORÇO PARA NÃO MOSTRAR NADA
	//}
	*/
	$sql.= $sql_where;
	$sql.=' limit 50 ';

//echo $sql;
//exit;	
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