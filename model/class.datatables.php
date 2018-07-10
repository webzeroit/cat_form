<?

/**
 * Classe per la gestione parametrizzata del dataTables jquery Versione 1.9.4
 * 
 *
 * @version 1.0.0
 * @authors Raffaele Lanzetta
 * 			Davide Manzo
 *
 */
class DataTables
{	

	public $Table 		= "";  		// Tabella o Vista da cui selezionare
	public $Columns    	= array();  // Elenco colonne da selezionare
	public $IndexColumn = "";       // Primary Key della tabella per velocizzare la ricerca
	public $debugSQL 	= ""; 	
	public $col_to_sum = array(); // Aggiunto il 29 Aprile 2013
	public $extraSQL = array(); // Aggiunto il 2 Maggio 2013
	public $extraCol = array(); // Aggiunto il 2 Maggio 2013
	public $initWhere = ""; //WHERE
	
	public function getData($Parameters) 
	{	
		$db = new MySQL();
				 		
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
     	 *  you want to insert a non-database field (for example a counter or static image)
     	*/													
		$aColumns = $this->Columns;
			
		/* Indexed column (used for fast and accurate table cardinality) */
		$sIndexColumn = $this->IndexColumn;
		
		/* DB table to use */
		$sTable = $this->Table;


			
		if ($db->Error()) $db->Kill();
		
		
		/*
		 * Paging
		 */
		$sLimit = "";
		if ( isset( $Parameters['iDisplayStart'] ) && $Parameters['iDisplayLength'] != '-1' )
		{
			$sLimit = "LIMIT ".intval( $Parameters['iDisplayStart'] ).", ".
				intval( $Parameters['iDisplayLength'] );
		}		
		
		
		/*
		 * Ordering
		 */
		$sOrder = "";
		if ( isset( $Parameters['iSortCol_0'] ) )
		{
			$sOrder = "ORDER BY  ";
			for ( $i=0 ; $i<intval( $Parameters['iSortingCols'] ) ; $i++ )
			{
				if ( $Parameters[ 'bSortable_'.intval($Parameters['iSortCol_'.$i]) ] == "true" )
				{
					switch ($aColumns[ intval( $Parameters['iSortCol_'.$i] ) ]) {
						case "data_protocollo_domanda":	
						case "data_inizio":	
						case "data_rilascio":
						case "data_scadenza":	
						case "data_esame":
						case "data_nascita":
							$sOrder .= " STR_TO_DATE(" . $aColumns[ intval( $Parameters['iSortCol_'.$i] ) ].", '%d/%m/%Y') 
							".($Parameters['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";					
							break;
						default:
							$sOrder .= $aColumns[ intval( $Parameters['iSortCol_'.$i] ) ]."
							".($Parameters['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
							break;
					}
				}
			}
			 
			$sOrder = substr_replace( $sOrder, "", -2 );
			if ( $sOrder == "ORDER BY" )
			{
				$sOrder = "";
			}
		}
		
		

		/*
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables, and MySQL's regex functionality is very limited
		 */
		

		$sWhere = "";
		if ($this->initWhere == "") 
			$sWhere = "";
		else
			$sWhere = "WHERE $this->initWhere";
						
		if ( isset($Parameters['sSearch']) && $Parameters['sSearch'] != "" )
		{
			if ($this->initWhere == "") 
				$sWhere = "WHERE (";
			else
				$sWhere .= " AND (";
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				if ( isset($Parameters['bSearchable_'.$i]) && $Parameters['bSearchable_'.$i] == "true" )
				{
					$sWhere .= $aColumns[$i]." LIKE '%". $db->SQLFix( $Parameters['sSearch'] )."%' OR ";
				}
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}	
		
		/* Individual column filtering */
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ( isset($Parameters['bSearchable_'.$i]) && $Parameters['bSearchable_'.$i] == "true" && $Parameters['sSearch_'.$i] != '' )
			{
				if ($sWhere == "") 
				{
					$sWhere = "WHERE ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				$sWhere .= $aColumns[$i]." LIKE '%". $db->SQLFix($Parameters['sSearch_'.$i])."%' ";
			}
		}
		
		
		/*
		 * SQL queries
		 * Get data to display
		 */
		$sQuery = "
			SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
			FROM   $sTable
			$sWhere
			$sOrder
			$sLimit
		";				
		
			
		$this->debugSQL = $sQuery;
		
		if (! $db->Query($sQuery)) $db->Kill();
		
		/* Data set length after filtering */
		if ($db->RowCount() != 0)
		{
			//SE CI SONO RIGHE CONTO LE FILTRATE
			$rResult = $db->RecordsArray(MYSQL_ASSOC);
			$sQuery = "SELECT FOUND_ROWS()";
			$iFilteredTotal = $db->QuerySingleValue( $sQuery ) or $db->Kill();					
		}else{
			$iFilteredTotal = 0;						
		}
		
		/* Total data set length */
		$sQuery = "
			SELECT COUNT(".$sIndexColumn.")
			FROM   $sTable
		";
		$iTotal = $db->QuerySingleValue( $sQuery );

						
		/*
		Gestione di colonne da sommare per la creazione di totalizzatori (29 Aprile 2013)		
		*/
		$sumCols = "";
		$sumQuery = "";
		if ( count( $this->col_to_sum ) > 0 ){
			$str_sql_sum = "";
			foreach( $this->col_to_sum as $c ){
				$str_sql_sum .= " SUM($c) AS $c,";
			}

			$str_sql_sum = substr( $str_sql_sum, 0, strlen( $str_sql_sum )-1);
			
			$sumQuery = "SELECT $str_sql_sum FROM $sTable $sWhere";
			$sumCols = $db->QuerySingleValue( $sumQuery );
			$sumCols = $db->RecordsArray(MYSQL_ASSOC);
		}
		
		/*
		Gestione di campi extra da ritornare in ajax/json (02 Maggio 2013)
		*/
		if ( count( $this->extraSQL ) > 0 ){
			foreach( $this->extraSQL as $eSQL ){
				$rs = $db->QuerySingleValue( $eSQL );
				$rs = $db->RecordsArray(MYSQL_ASSOC);
				foreach( array_keys($rs[0]) as $key ){
					$this->extraCol[$key] = $rs[0][$key];
				}
			}
		}
		
		/*
		* Output
		*/
		if($iFilteredTotal == 0) {
			$output = array(
				"sEcho" => intval($Parameters['sEcho']),
				"iTotalRecords" => 0,
				"iTotalDisplayRecords" => 0,
				"aaData" => array(),			
				"SQL" => $this->debugSQL,
				"sumCol" => $sumCols,			
				"sumQuery" => $sumQuery,
				"extraCol" => $this->extraCol
			);
			
		} else {
			$output = array(
				"sEcho" => intval($Parameters['sEcho']),
				"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => $rResult,			
				"SQL" => $this->debugSQL,
				"sumCols" => $sumCols,
				"sumQuery" => $sumQuery,
				"extraCol" => $this->extraCol
			);
		}
		return $output;
	}	

}
?>