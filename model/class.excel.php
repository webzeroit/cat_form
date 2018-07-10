<?

class Excel extends PHPExcel {
	
	public $Table 		= "";  		// Tabella o Vista da cui selezionare
	public $SelectColumns  = array();  // Elenco colonne da selezionare
	public $HeaderColumns  = array();
	public $IndexColumn = "";   
	public $Order = "";
	public $Limit = "";
	public $Where = ""; 
	public $NomeFile = "";
	public $TitoloFoglio = "";
	private $NumColonne = 0;
	private $NumRighe = 0;
	private $data;
	private $BStyle = array(
						'borders' => array(
							'allborders' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN	
								)
							)
						);


	function setMetaDati(){
		
		$this->getProperties()->setCreator("Regione Basilicata")
                             ->setLastModifiedBy("Regione Basilicata")
                             ->setTitle($this->NomeFile);
				
	}
	
	function setIntestazioneFoglio(){
		$riga=1;
		$colonna=0;
		$this->NumColonne = count($this->HeaderColumns);
		foreach( $this->HeaderColumns as $titolo ){
			$this->setActiveSheetIndex(0)->setCellValueByColumnAndRow($colonna, $riga, $titolo);
			$this->setActiveSheetIndex(0)->getStyleByColumnAndRow($colonna, $riga)->getFont()->setBold(true);
			$colonna++;
		}
		
	}
	
	function setIntestazioneFoglioElenchi(){
		$objDrawingPType = new PHPExcel_Worksheet_Drawing();
		$objDrawingPType->setWorksheet($this->setActiveSheetIndex(0));
		$objDrawingPType->setName("Logo Basilicata");
		$objDrawingPType->setPath("images/logorg.png");
		$objDrawingPType->setCoordinates('A1');
		$objDrawingPType->setHeight(50);
		$objDrawingPType->setOffsetX(10);
		$objDrawingPType->setOffsetY(10);	
		
		
		
		$this->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2,1,"DIPARTIMENTO POLITICHE AGRICOLE E FORESTALI DELLA REGIONE BASILICATA");
		$this->setActiveSheetIndex(0)->getStyleByColumnAndRow(2,1)->getFont()->setBold(false);
		
		$this->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2, 2, "UFFICIO AUTORITÀ DI GESTIONE PSR BASILICATA");
		$this->setActiveSheetIndex(0)->getStyleByColumnAndRow(2,2)->getFont()->setBold(false);
		
		$this->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2, 4, "Misura 6 \"Sviluppo delle aziende agricole e delle imprese\" - Sottomisura 6.1 \"Aiuto all’avviamento di imprese per i giovani agricoltori\" - Operazione 6.1.1 \"Incentivi per la costituzione di nuove aziende agricole da parte di giovani agricoltori\" - Prima finestra");
    	$this->setActiveSheetIndex(0)->mergeCells('C4:F4');		
		$this->setActiveSheetIndex(0)->getStyle('C4:F4')->getFont()->setBold(false);		
		$this->setActiveSheetIndex(0)->getRowDimension(4)->setRowHeight(30);
		$this->getActiveSheet()->getStyle('C4:F4')->getAlignment()->setWrapText(true);
		
		$this->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2,6,"Domande di aiuto pervenute - Allegato A");
		$this->setActiveSheetIndex(0)->getStyleByColumnAndRow(2,6)->getFont()->setBold(false);		

		$this->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 8, "Nr.");
		$this->setActiveSheetIndex(0)->getStyleByColumnAndRow(0, 8)->getFont()->setBold(true);	
		
		$this->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, 8, "Nr. posizione");
		$this->setActiveSheetIndex(0)->getStyleByColumnAndRow(1, 8)->getFont()->setBold(true);

		$this->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2, 8, "Nr. domanda");
		$this->setActiveSheetIndex(0)->getStyleByColumnAndRow(2, 8)->getFont()->setBold(true);

		$this->setActiveSheetIndex(0)->setCellValueByColumnAndRow(3, 8, "Cognome/Nome/Ragione Sociale");
		$this->setActiveSheetIndex(0)->getStyleByColumnAndRow(3, 8)->getFont()->setBold(true);

		$this->setActiveSheetIndex(0)->setCellValueByColumnAndRow(4, 8, "Residenza (Indirizzo e Comune)");
		$this->setActiveSheetIndex(0)->getStyleByColumnAndRow(4, 8)->getFont()->setBold(true);

		$this->setActiveSheetIndex(0)->setCellValueByColumnAndRow(5, 8, "CUAA");
		$this->setActiveSheetIndex(0)->getStyleByColumnAndRow(5, 8)->getFont()->setBold(true);
	
	}
	
	function setDatiFoglio(){
		$this->getData();
		$riga=2;
		foreach( $this->data as $row ){ 
			for ($colonna = 0; $colonna < $this->NumColonne; $colonna++){
				$this->setActiveSheetIndex(0)->setCellValueByColumnAndRow($colonna, $riga, $row[$colonna], 'inlineStr');								
			}
			$riga++;
		}
		$this->NumRighe = $riga;
	}
	
	function setNomeFoglio(){		
		$this->getActiveSheet()->setTitle($this->TitoloFoglio);	
	}

	function setGriglia() {	
		$fine = $this->coordinates($this->NumColonne-1, $this->NumRighe-1);
		$range = "A1:" . $fine;
		$this->getActiveSheet()->getStyle($range)->applyFromArray($this->BStyle);
	}
	
	
	private function getData() {	
		$db = new MySQL();
		
		$aColumns = $this->SelectColumns;
		$sIndexColumn = $this->IndexColumn;
		$sTable = $this->Table;
		
		$sWhere = "";
		if ($this->Where != "") 					
			$sWhere = "WHERE $this->Where";		

		$sOrder = "";
		if ( $this->Order != "" )
			$sOrder = "ORDER BY $this->Order";
		
		$sLimit = "";
		if ( $this->Limit != "" )
			$sLimit = "LIMIT $this->Limit";
			


		
		$sQuery = "
			SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
			FROM   $sTable
			$sWhere
			$sOrder
			$sLimit
		";	
		
		
		if (! $db->Query($sQuery)) $db->Kill();	
		
		/* Data set length after filtering */
		if ($db->RowCount() != 0)
		{
			$this->data = $db->RecordsArray(MYSQL_NUM);					
		}
		
		
	}
	private function coordinates($x,$y){
	 	return PHPExcel_Cell::stringFromColumnIndex($x).$y;
	}
	
}

?>