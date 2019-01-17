<?php
class Conexao
{
	protected $host = 'jb051';
	protected $user = 'jabot';
	protected $dbname = 'null'; //nao apagar
	protected $password = '#1808nccg#-@';
	protected $conn = 'null';

	public function __construct(){
		//$this->schema = $_SESSION['schema'];
		
		/*$this->host = $host;
		$this->dbname = $dbname;
		$this->user = $user;
		$this->password = $password;
		*/
	}
	
	function Conectar($dbname='Jardim')
	{
	$this->dbname = $dbname; //Não apagar.
	
		// if($_SESSION['s_idusuario'] == 2039  )
		// {
			// $this->statusCon();
			// print 'Conexao -  dbname: '.$dbname.'<br>';
		// }

		$this->conn = pg_connect("host=$this->host dbname=$dbname user=$this->user password=$this->password");
		return $this->conn;
	}

	 
	#método verifica status da conexao
	function statusCon()
	{
		if(!$this->conn){
		echo  "<h3>O sistema não está conectado à  [$this->dbname] em [$this->host].</h3>";
		exit;
		}
		else{
		echo "<h3>O sistema está conectado à  [$this->dbname] em [$this->host].</h3>";
		}
	}

function close(){
	@pg_close($this->conn);
}
	



/* 
//m46jo
//   function Conectar($host = 'essinfo',$dbname = 'Jardim',$user = 'postgres',$password = 'maluerebeca')

   function Conectar($host = 'jb051',$dbname = 'Jardim',$user = 'jabot',$password = '#1808nccg#-@')
   {
   print $_SESSION['schema'];
   
   $this->schema = $_SESSION['schema'];
   
   print 'Conexao - Schema: '.$this->schema.'<br>';
   //exit;
   
      $conn = pg_connect("host=$host dbname = $dbname user = $user password = $password ");
	  
	  return $conn;
	}
	
*/
	
}
?>