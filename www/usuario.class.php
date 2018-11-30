<?php

class Usuario {

    var $conn;
    public $idusuario;
    public $nome;
    public $login;
    public $senha;
    public $telefone;
    public $email;
    public $idsituacaousuario;
	public $idtipousuario;
	public $tipousuario;

	
	function incluir() {
        $sql = "insert into fauna.usuario (nome,login,senha,telefone,email,idsituacaousuario,idtipousuario) 
		values ('" . $this->nome . "',
		'" . $this->login . "',
		'" . $this->senha . "',
		'" . $this->telefone . "',
		'" . $this->email . "',
		" . $this->idsituacaousuario . ",
		" . $this->idtipousuario . "
		)
		RETURNING idusuario";

        $resultado = pg_exec($this->conn, $sql);
        $row = pg_fetch_row($resultado);


        if ($resultado) {
            return $row[0];
        } else {
            return false;
        }
    }

    function alterar($id) {
        $sql = "update fauna.usuario set 
		nome = '" . $this->nome . "' ,
		login = '" . $this->login . "' ,
		telefone = '" . $this->telefone . "' ,
		email = '" . $this->email . "' ,
		idsituacaousuario = " . $this->idsituacaousuario . ", 
		idtipousuario = " . $this->idtipousuario . " 
		where idusuario='" . $id . "' ";
        $resultado = pg_exec($this->conn, $sql);
        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }

    function excluir($id) {
        $sql = "delete from fauna.usuario where idusuario = '" . $id . "' ";
        $resultado = @pg_exec($this->conn, $sql);
        
        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }	
	
	
	 function alterarSenha($idusuario,$senha) {
        $sql = "update fauna.usuario set senha='".$senha."' where idusuario = '".$idusuario."'";
        $resultado = @pg_exec($this->conn, $sql);
        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }	
	
    public function getDados($row) {
        $this->idusuario = $row['idusuario'];
        $this->nome = $row['nome'];
        $this->login = $row['login'];
        $this->senha= $row['senha'];
        $this->email = $row['email'];
        $this->telefone = $row['telefone'];
        $this->idsituacaousuario = $row['idsituacaousuario'];
		$this->idtipousuario = $row['idtipousuario'];
		$this->tipousuario = $row['tipousuario'];
    }

    function autenticaApp($username, $password, $uuid) {
		$sql = "select * from fauna.usuario where idusuario = idusuario ";
		if (!empty($username))
		{
			$sql.=" and login = '" . $username . "' and senha = '".$password."'";

		}
		else
		{
			if (!empty($uuid))
			{
				$sql .= " and uuid = '".$uuid."';";
			}
		}
        $res = pg_exec($this->conn, $sql);
		//echo $sql;
		if (pg_num_rows($res)<1)
		{
            return false;
        } 
		else 
		{
//            imap_close($mbox);
			if (!empty($username))
			{
				$this->getByLogin($username);
				
	    		if (!empty($uuid))
				{
					$sql2 = "update fauna.usuario set uuid = '' where uuid='".$uuid."'";
					$res2 = pg_exec($this->conn,$sql2);

					$sql2 = "update fauna.usuario set uuid = '".$uuid."' where idusuario = ".$this->idusuario;
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
	
	 public function getByLoginUUID($uuid) {
        if (empty($login)) {
            $id = '';
        }
        $sql = "
			select * from fauna.usuario u, fauna.tipousuario  where u.idtipousuario = tipousuario.idtipousuario and  u.uuid = '" . $uuid . "'";
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
			select * from fauna.usuario u, fauna.tipousuario  where u.idtipousuario = tipousuario.idtipousuario and  u.login = '" . $login . "'";
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
			select * from fauna.usuario where idusuario = ' . $id;

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

//--fim classe
?>