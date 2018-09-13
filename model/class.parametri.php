<?


class Parametri
{
	public function getList($toJson = false){		
		$sql = "SELECT * FROM ar_parametri";

		$db = new MySQL();
		if (! $db->Query($sql)) echo $db->Kill();
		
		if (!$toJson)
			return ($db->RowCount() != 0) ? $db->RecordsArray(MYSQL_ASSOC) : null;
		else
			return ($db->RowCount() != 0) ? $db->RecordsArray(MYSQL_NUM) : null;			
	}	
	
	
	public function save($chiave , $valore){			
		$db = new MySQL();
		$array_where["chiave"] = MySQL::SQLValue($chiave);	
		$array_insert["valore"] = MySQL::SQLValue($valore);	
								
		return $db->UpdateRows("ar_parametri",$array_insert,$array_where);
	}
	
	
	public function getParametroValore($chiave){
		$sql = "SELECT valore FROM ar_parametri WHERE chiave='" . $chiave . "'";
		
		$db = new MySQL();
		if (! $db->Query($sql)) echo $db->Kill();
		
		return $db->QuerySingleValue( $sql );	
	}		
				
	
}


?>
