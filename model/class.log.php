<?


class LogToDB {


		
	public static function insert ($id_utente, $funzione, $nome_tabella, $chiave_tabella, $operazione){
		$db = new MySQL();
		$insert["id_utente"] = 			$id_utente;
		$insert["data_evento"] = 		MySQL::SQLValue(date('Y-m-d H:i:s'), MySQL::SQLVALUE_DATETIME);
		$insert["funzione"] = 			MySQL::SQLValue($funzione);
		$insert["nome_tabella"] = 		MySQL::SQLValue($nome_tabella);
		$insert["chiave_tabella"] = 	MySQL::SQLValue($chiave_tabella);
		$insert["operazione"] = 		MySQL::SQLValue($operazione);

		$db->InsertRow("sc_utente_log", $insert);
		
	}
	
	
}
?>