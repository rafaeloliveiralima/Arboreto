<?php

// variável que define o diretório das imagens
$dir = "./upload"; 

// esse seria o "handler" do diretório
$dh = opendir($dir); 

$id = $_REQUEST['id'];
//$id = '47';
$id=str_pad($id, 8, "0", STR_PAD_LEFT);
// loop que busca todos os arquivos até que não encontre mais nada
$c = 0;
$ol = '';
$div = '';
while (false !== ($filename = readdir($dh))) { 
// verificando se o arquivo é .jpg
	if (substr($filename,-4) == ".jpg") { 
	// mostra o nome do arquivo e um link para ele - pode ser mudado para mostrar diretamente a imagem :)
		if (substr($filename,0,8) == $id) { 
			//echo '<img src="https://projetofauna.jbrj.gov.br/upload/'.$filename.'" class="img-thumbnail" width="50%">'; 
			$classe = '';
			$classe2 = 'class="item"';
			if ($c==0)
			{
				$classe = 'class="active"';
				$classe2 = 'class="item active"';
			}
			$ol .= '<li data-target="#myCarousel" data-slide-to="'.$c.'" '.$classe.'></li>';
			$div .= ' <div '.$classe2.'">
					<img src="'.'https://aplicacoes.jbrj.gov.br/jabot/arboreto/upload/'.$filename.'" alt="" style="width: 100%;">
					</div>';
			$c++;
		}
	}
}

?>
 
  <div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <?php echo $ol;?>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner">
	   <?php echo $div;?>
    </div>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
<?php 
?>