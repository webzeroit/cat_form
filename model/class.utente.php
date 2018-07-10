<?

class Utente
{
	
	public function getList($toJson = false){		
		$sql = "SELECT 
					id_utente,
					nome,
					cognome,
					email
				FROM sc_utente";

		$db = new MySQL();
		if (! $db->Query($sql)) echo $db->Kill();
		
		if (!$toJson)
			return ($db->RowCount() != 0) ? $db->RecordsArray(MYSQL_ASSOC) : null;
		else
			return ($db->RowCount() != 0) ? $db->RecordsArray(MYSQL_NUM) : null;			
	}	


	public function getTecniciList($toJson = false){		
		$sql = "SELECT
				sc_utente.id_utente,
				sc_utente.nome,
				sc_utente.cognome,
				sc_utente.email
				FROM
				sc_utente
				INNER JOIN sc_utente_funzione ON sc_utente_funzione.id_utente = sc_utente.id_utente
				WHERE
				sc_utente_funzione.cod_funzione = 'AB_001'";

		$db = new MySQL();
		if (! $db->Query($sql)) echo $db->Kill();
		
		if (!$toJson)
			return ($db->RowCount() != 0) ? $db->RecordsArray(MYSQL_ASSOC) : null;
		else
			return ($db->RowCount() != 0) ? $db->RecordsArray(MYSQL_NUM) : null;			
	}	

	public function getRow($id, $toJson = false){		
		$sql = "SELECT 
					id_utente,
					nome,
					cognome,
					email,
					username
				FROM sc_utente
				WHERE id_utente=".$id;

		$db = new MySQL();
		if (! $db->Query($sql)) echo $db->Kill();
		
		if (!$toJson)
			return ($db->RowCount() != 0) ? $db->RowArray(null,MYSQL_ASSOC) : null;
		else
			return $db->GetJSON();		
	}		
	
	public function delete($id){			
		$array_where["id_utente"] = $id;
			
		$db = new MySQL();
		$db->DeleteRows("sc_utente_funzione", $array_where);
		$ret = $db->DeleteRows("sc_utente", $array_where);
				
		if ( $db->Error() ){
			if ($db->ErrorNumber() == 1451)
				return "-1"; //IMPOSSIBILE CANCELLARE
			else
				return "0"; //OK
		}
		return $ret;
	}
		
	public function save($id, $nome, $cognome, $email, $username, $array_funzioni ){			
		$db = new MySQL();
		
		try {
			$db->TransactionBegin();
			$array_where["id_utente"] = $id;						
			$array_insert["nome"] = MySQL::SQLValue($nome);
			$array_insert["cognome"] = MySQL::SQLValue($cognome);
			$array_insert["email"] = MySQL::SQLValue($email);
			$array_insert["username"] = MySQL::SQLValue($username);
			if ($id == 0)
				$array_insert["password"] = MySQL::SQLValue(md5("password"));
			
			$ret = $db->AutoInsertUpdate("sc_utente",$array_insert,$array_where);
			
			/* SOLO INSERIMENTO */
			if ($id == 0) $id = $ret;
			
			$num_funzioni = count($array_funzioni);
			
			$array_where["id_utente"] = $id;
			$db->DeleteRows("sc_utente_funzione", $array_where);
			
			for ($i = 0; $i < $num_funzioni; $i++){
				$array_insert_fn["id_utente"] =  $id;
				$array_insert_fn["cod_funzione"] = MySQL::SQLValue($array_funzioni[$i]);
				$db->InsertRow("sc_utente_funzione", $array_insert_fn);
			}
			$db->TransactionEnd();		
			return $ret;
		} catch(Exception $e) {
			// If an error occurs, rollback and show the error
			$db->TransactionRollback();
			return 0;
		
		}			
	}	
	
	public function setUserLogin( $username, $password ){
		$sql = "SELECT 
					sc_utente.id_utente,
					sc_utente.nome,
					sc_utente.cognome,
					sc_utente.email,
					sc_utente.username					
			  	FROM sc_utente 
				WHERE  username = '" . $username . "' AND password = MD5('" . $password . "')";
		
		$db = new MySQL();
		$userData = $db->QuerySingleRowArray($sql);
		if ($userData) {
			$_SESSION["login_catform"] = true;
			$_SESSION["id_utente"] = $userData["id_utente"];
			$_SESSION["nome"] = $userData["nome"];
			$_SESSION["cognome"] = $userData["cognome"];
			$_SESSION["email"] = $userData["email"];
			$_SESSION["username"] = $userData["username"];
			$_SESSION["funzioni"] = $this->getUtenteFunzioni($userData["id_utente"]);						
			return true;
		}else{
			return false;
		}	
	}

	public function setPassword ($id_utente, $nuova_password ){
		$array_update["password"] = MySQL::SQLValue(md5($nuova_password) );
		$array_where["id_utente"] = $id_utente;
		$db = new MySQL();
		if (!$db->UpdateRows("sc_utente", $array_update, $array_where)){			
			return 0;			
		}
		return 1;		
	}
		
	public function isLogged(){
		if(isset($_SESSION["login_catform"]))
			return true;
		else
			return false;
	}	
		
	public function logout(){
		$_SESSION["login_catform"] = false;
		@session_destroy();
	}	
	
		
	/*******************************************************
				FUNZIONI
	*******************************************************/
	
	public function getListFunzioni($toJson = false){		
		$sql = "SELECT cod_funzione, des_funzione FROM sc_funzione";

		$db = new MySQL();
		if (! $db->Query($sql)) echo $db->Kill();
		
		if (!$toJson)
			return ($db->RowCount() != 0) ? $db->RecordsArray(MYSQL_ASSOC) : null;
		else
			return ($db->RowCount() != 0) ? $db->RecordsArray(MYSQL_NUM) : null;			
	}
	
	public function getUtenteFunzioni($id_utente){				
		$db = new MySQL();
		
		$sql = "SELECT cod_funzione
			    FROM sc_utente_funzione 
				WHERE id_utente=" . $id_utente;


		if (!$db->Query($sql)) echo $db->Kill();
		
		if ($db->RowCount() == 0) 
			return null;
		
		while ($row = $db->Row()) {
			$utente_funzioni[] = $row->cod_funzione;
		}					
		return $utente_funzioni;
	}	
	
	public function abilitato($cod_function){
		return (in_array($cod_function, $_SESSION["funzioni"]));
	}	

}


?>