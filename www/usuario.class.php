<?php
class Usuario {
    var $conn;
    var $idusuario;
    var $nome;
    var $login;
    var $senha;
    var $telefone;
    var $email;
	
	function autenticaApp($username, $password, $uuid) {
		$sql = "select * from public.usuario where codusuario = codusuario ";
		if (!empty($username))
		{
			$sql.=" and nomelogin = '" . $username . "'";// and senha = '".$password."'";

		}
		else
		{
			if (!empty($uuid))
			{
				$sql .= " and uuid = '".$uuid."';";
			}
		}
        $res = pg_exec($this->conn, $sql);
		if (pg_num_rows($res)<1)
		{
            return false;
        } 
		else 
		{
			if (!empty($username))
			{
				$this->getByLogin($username);
				
	    		if (!empty($uuid))
				{
					$sql2 = "update public.usuario set uuid = '' where uuid='".$uuid."'";
					$res2 = pg_exec($this->conn,$sql2);

					$sql2 = "update public.usuario set uuid = '".$uuid."' where codusuario = ".$this->idusuario;
					$res2 = pg_exec($this->conn,$sql2);
				}
				return true;
			}
			else
			{
				$this->getByLoginUUID($uuid);
				return true;
			}
        }
		
    }
	

    public function getDados($row) {
		
        $this->idusuario = $row['codusuario'];
        $this->nome = $row['nomecompleto'];
        $this->login = $row['nomelogin'];
        $this->senha= $row['senha'];
        $this->email = $row['email'];
        $this->telefone = $row['telefone'];
		
    }

    
	
	 public function getByLoginUUID($uuid) {
        if (empty($login)) {
            $id = '';
        }
        $sql = "
			select * from public.usuario u  where u.uuid = '" . $uuid . "'";
        $result = pg_exec($this->conn, $sql);
        if (pg_num_rows($result) > 0) {
            $row = pg_fetch_array($result);
            $this->getDados($row);
            return 1;
        } else {
            return 0;
        }
		
    }

    public function getByLogin($login) {
		
        if (empty($login)) {
            $id = '';
        }
        $sql = "
			select * from public.usuario u where u.nomelogin = '" . $login . "'";
        $result = pg_exec($this->conn, $sql);
        if (pg_num_rows($result) > 0) {
            $row = pg_fetch_array($result);
            $this->getDados($row);
            return 1;
        } else {
            return 0;
        }
		
    }

    public function getById($id) {
        if (empty($id)) {
            $id = 0;
        }
        $sql = '
			select * from public.usuario where codusuario = ' . $id;

        $result = pg_exec($this->conn, $sql);
        if (pg_num_rows($result) > 0) {
            $row = pg_fetch_array($result);
            $this->getDados($row);
            return 1;
        } else {
            return 0;
        }
    }
	
	
}

?>