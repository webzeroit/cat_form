<?
class Graduatoria
{
	public function getList($toJson = false){		
		$sql = "SELECT * FROM tb_graduatoria";

		$db = new MySQL();
		if (! $db->Query($sql)) echo $db->Kill();
		
		if (!$toJson)
			return ($db->RowCount() != 0) ? $db->RecordsArray(MYSQL_ASSOC) : null;
		else
			return ($db->RowCount() != 0) ? $db->RecordsArray(MYSQL_NUM) : null;			
	}	

	public function getRow($id, $toJson = false){		
		$sql = "SELECT * FROM v_graduatoria WHERE id_graduatoria=".$id;

		$db = new MySQL();
		if (! $db->Query($sql)) echo $db->Kill();
		
		if (!$toJson)
			return ($db->RowCount() != 0) ? $db->RowArray(null,MYSQL_ASSOC) : null;
		else
			return $db->GetJSON();				
	}	
	
	public function delete($id, $id_utente){			
		$array_where["id_graduatoria"] = $id;
		
		$db = new MySQL();
		$ret = $db->DeleteRows("tb_graduatoria", $array_where);
		if ( $db->Error() ){
			if ($db->ErrorNumber() == 1451)
				$ret  = -1; //IMPOSSIBILE CANCELLARE
			else
				$ret = 0; //OK
		}
		if ($ret >= 0)
			LogToDB::insert($id_utente,"deleteGraduatoria","tb_graduatoria", $id, "Cancellazione Graduatoria");
		return $ret;
	}
		
	public function save($dati_graduatoria){			
		$db = new MySQL();
		$tableName = "tb_graduatoria";
		$array_where["id_graduatoria"] = $dati_graduatoria["id_graduatoria"];	
		
		$array_insert["des_graduatoria"] = MySQL::SQLValue($dati_graduatoria["des_graduatoria"]);		
		$array_insert["data_inizio"] = MySQL::SQLValue($dati_graduatoria["data_inizio"], MySQL::SQLVALUE_DATE_IT);
		$array_insert["data_fine"] = MySQL::SQLValue($dati_graduatoria["data_fine"], MySQL::SQLVALUE_DATE_IT);
		$array_insert["id_stato_graduatoria"] = MySQL::SQLValue($dati_graduatoria["id_stato_graduatoria"]);	
		$array_insert["id_misura"] = MySQL::SQLValue($dati_graduatoria["id_misura"]);		
		$array_insert["id_sottomisura"] = MySQL::SQLValue($dati_graduatoria["id_sottomisura"]);		
		$array_insert["id_operazione"] = MySQL::SQLValue($dati_graduatoria["id_operazione"]);		
		$array_insert["dotazione_finanziaria"] = Utility::str2num($dati_graduatoria["dotazione_finanziaria"]);
		$array_insert["punteggio_min"] = Utility::str2num($dati_graduatoria["punteggio_min"]);						
		
		$ret = $db->AutoInsertUpdate($tableName, $array_insert, $array_where);
		
		LogToDB::insert($dati_graduatoria["id_utente"],"saveGraduatoria",$tableName, $dati_graduatoria["id_graduatoria"], "Salvataggio Graduatoria");
		return $ret;
	}
	
	public function setStatoGraduatoria($dati_graduatoria){			
		$db = new MySQL();
		$array_where["id_graduatoria"] = $dati_graduatoria["id_graduatoria"];	
				
		$array_insert["id_stato_graduatoria"] = MySQL::SQLValue($dati_graduatoria["id_stato_graduatoria"]);							
		
		return $db->UpdateRows("tb_graduatoria",$array_insert,$array_where);
	}	
	
	public function setVerbaleGraduatoria($id_graduatoria , $data_verbale, $id_stato_graduatoria){			
		$db = new MySQL();
		$array_where["id_graduatoria"] = $id_graduatoria;	
		if ( $data_verbale == "NULL")
			$array_insert["data_verbale"] = $data_verbale;	
		else
			$array_insert["data_verbale"] = MySQL::SQLValue( $data_verbale, MySQL::SQLVALUE_DATE_IT);	
		$array_insert["id_stato_graduatoria"] = MySQL::SQLValue($id_stato_graduatoria);							
		
		return $db->UpdateRows("tb_graduatoria",$array_insert,$array_where);
	}	
	
	
	/* GESTIONE ESITI */ 
	public function getTotPresentate($id_graduatoria, $toJson = false){
		$sql = "SELECT COUNT(id_domanda) FROM v_domanda_lista WHERE id_graduatoria=$id_graduatoria";

		$db = new MySQL();
		if (! $db->Query($sql)) echo $db->Kill();
		
		return $db->QuerySingleValue( $sql );	
	}		

	public function getTotAmmesse($id_graduatoria, $toJson = false){		
	
		$punt_min = $this->getPunteggioMinGraduatoria($id_graduatoria);
		
		$sql = "SELECT COUNT(id_domanda) FROM v_domanda_lista 
				WHERE id_graduatoria=$id_graduatoria and 
					  id_stato_domanda>=5000 and
					  punteggio>=" . $punt_min;

		$db = new MySQL();
		if (! $db->Query($sql)) echo $db->Kill();
		
		return $db->QuerySingleValue( $sql );		
	}	

	public function getTotNonAmmesse($id_graduatoria, $toJson = false){		
	
		$punt_min = $this->getPunteggioMinGraduatoria($id_graduatoria);
		
		$sql = "SELECT COUNT(id_domanda) FROM v_domanda_lista 
				WHERE id_graduatoria=$id_graduatoria and 
					  id_stato_domanda>=5000 and
					  punteggio<" . $punt_min;

		$db = new MySQL();
		if (! $db->Query($sql)) echo $db->Kill();
		
		return $db->QuerySingleValue( $sql );		
	}		
	
	public function getTotNonRicevibili($id_graduatoria, $toJson = false){		

		$sql = "SELECT COUNT(id_domanda) FROM v_domanda_lista 
				WHERE id_graduatoria=$id_graduatoria and 
					  id_stato_domanda=2101";

		$db = new MySQL();
		if (! $db->Query($sql)) echo $db->Kill();
		
		return $db->QuerySingleValue( $sql );		
	}		

	public function getTotNonAmmissibili($id_graduatoria, $toJson = false){		

		$sql = "SELECT COUNT(id_domanda) FROM v_domanda_lista 
				WHERE id_graduatoria=$id_graduatoria and id_stato_domanda in (3101,4101)";

		$db = new MySQL();
		if (! $db->Query($sql)) echo $db->Kill();
		
		return $db->QuerySingleValue( $sql );		
	}		

	public function getTotInIstruttoria($id_graduatoria, $toJson = false){		

		$sql = "SELECT COUNT(id_domanda) FROM v_domanda_lista 
				WHERE id_graduatoria=$id_graduatoria and id_stato_domanda in(1000,2000,3000,3200,4000,4200)";

		$db = new MySQL();
		if (! $db->Query($sql)) echo $db->Kill();
		
		return $db->QuerySingleValue( $sql );		
	}

		
	public function getPunteggioMinGraduatoria($id_graduatoria){
		$sql = "SELECT punteggio_min FROM tb_graduatoria WHERE id_graduatoria=$id_graduatoria";
		$db = new MySQL();
		if (! $db->Query($sql)) echo $db->Kill();
		
		return $db->QuerySingleValue( $sql );					
	}
	
}


?>
