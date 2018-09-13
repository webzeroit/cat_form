<?

class ElenchiExcel extends PHPExcel {
	
	public $NomeFile = "";  //NOME DEL FILE EXCEL DEGLI ELENCHI
	public $NumeroFogli = 5;  //NOME DEL FILE EXCEL DEGLI ELENCHI
	public $ID_Elenco = 0;
	private $dotazione_finanziaria = 0;
	private $data;
	private $secFinestra = 0;
	private $BStyle = array(
			'borders' => array(
			'allborders' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN	
			)
		)
	);
	private $AlignCenter = array(
			'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
	);	
	
	private $AlignLeft = array(
			'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
        )
	);		
	
	//COMUNE ALLE 2 FINESTRE
	public function setMetaDati(){
		if ($this->ID_Elenco > 1)
			$this->secFinestra = 1;
	 	else 
			$this->secFinestra = 0;
		
		$this->getProperties()->setCreator("Regione Basilicata")
                             ->setLastModifiedBy("Regione Basilicata")
                             ->setTitle($this->NomeFile);
				
	}
	
	
	//COMUNE ALLE 2 FINESTRE
	public function creaFogliElenchi(){
		$titoloFoglio = array(); 
		$IntestazioneFoglio = array(); 
		
		//17/07/2017 MODIFICA PER DOMANDE CONFERMA
		if ($this->secFinestra == 0) {
			$this->NumeroFogli = 5;
			$titoloFoglio[0] = "Sintesi";
			$titoloFoglio[1] = "Pervenute";
			$titoloFoglio[2] = "Ammissibili";
			$titoloFoglio[3] = "Finanziabili";
			$titoloFoglio[4] = "Non Ammesse";		

			$IntestazioneFoglio[0] = "Quadro di sintesi";
			$IntestazioneFoglio[1] = "Elenco delle domande di sostegno pervenute - Allegato A";
			$IntestazioneFoglio[2] = "Elenco delle domande di sostegno ammissibili - Allegato B"; //TUTTE
			$IntestazioneFoglio[3] = "Elenco delle domande di sostegno ammesse e finanziabili - Allegato D";
			$IntestazioneFoglio[4] = "Elenco delle domande di sostegno non ammesse con relative motivazioni - Allegato C";		
		} else {
			$this->NumeroFogli = 6;
			$titoloFoglio[0] = "Sintesi";
			$titoloFoglio[1] = "Pervenute";
			$titoloFoglio[2] = "Conferme";
			$titoloFoglio[3] = "Ammissibili";	
			$titoloFoglio[4] = "Non Ammesse";	
			$titoloFoglio[5] = "Finanziabili";	
			
			$IntestazioneFoglio[0] = "Quadro di sintesi";
			$IntestazioneFoglio[1] = "Elenco delle domande di sostegno pervenute - Allegato A";
			$IntestazioneFoglio[2] = "Elenco delle domande di conferma pervenute - Allegato B";	
			$IntestazioneFoglio[3] = "Elenco delle domande di sostegno e delle domande di conferma ammissibili - Allegato C"; 
			$IntestazioneFoglio[4] = "Elenco delle domande di sostegno e delle domande di conferma non ammesse con relative motivazioni - Allegato D";	
			$IntestazioneFoglio[5] = "Elenco delle domande di sostegno ammesse e finanziabili - Allegato E";		
		}
		$this->setDotazione();
		
		$i=0;
		while ($i < $this->NumeroFogli) {	
			// primo foglio
			if ($i == 0)	{		
				$objWorkSheet = $this->getActiveSheet();	
			} else {
				$objWorkSheet = $this->createSheet($i); 
			}
			// rinomino il foglio
			$objWorkSheet->setTitle($titoloFoglio[$i]);
			$this->setIntestazioneFoglioElenchi($i , $IntestazioneFoglio[$i] );
			$this->setDatiFoglio($i);
			$i++;
		}		
		$this->setActiveSheetIndex(0);
	}
	
	private function setIntestazioneFoglioElenchi($indice_foglio, $nome_elenco){
		if ($indice_foglio > 0) {
		
			$objDrawingPType = new PHPExcel_Worksheet_Drawing();
			$objDrawingPType->setWorksheet($this->setActiveSheetIndex($indice_foglio));
			$objDrawingPType->setName("Logo Basilicata");
			$objDrawingPType->setPath("images/logorg.png");
			$objDrawingPType->setCoordinates('A1');
			$objDrawingPType->setHeight(50);
			$objDrawingPType->setOffsetX(10);
			$objDrawingPType->setOffsetY(15);	
					
			$foglio_correte = $this->setActiveSheetIndex($indice_foglio);
			
			$foglio_correte->setCellValueByColumnAndRow(2,1,"DIPARTIMENTO POLITICHE AGRICOLE E FORESTALI DELLA REGIONE BASILICATA");
			$foglio_correte->getStyleByColumnAndRow(2,1)->getFont()->setBold(false);
			
			$foglio_correte->setCellValueByColumnAndRow(2, 2, "UFFICIO AUTORITÀ DI GESTIONE PSR BASILICATA");
			$foglio_correte->getStyleByColumnAndRow(2,2)->getFont()->setBold(false);
			//17/07/2017 MODIFICA DOMANDE CONFERMA
			if ($this->secFinestra == 0)
				$finestra = "Prima";
			else
				$finestra = "Seconda";
			
			$foglio_correte->setCellValueByColumnAndRow(2, 4, "Misura 6 \"Sviluppo delle aziende agricole e delle imprese\" - Sottomisura 6.1 \"Aiuto all’avviamento di imprese per i giovani agricoltori\" - Operazione 6.1.1 \"Incentivi per la costituzione di nuove aziende agricole da parte di giovani agricoltori\" - " . $finestra . " finestra");
			//FINE
			$foglio_correte->mergeCells('C4:D4');		
			$foglio_correte->getStyle('C4:D4')->getFont()->setBold(false);		
			$foglio_correte->getRowDimension(4)->setRowHeight(50);
			$foglio_correte->getStyle('C4:D4')->getAlignment()->setWrapText(true);
			
			$foglio_correte->setCellValueByColumnAndRow(2,6, $nome_elenco);
			$foglio_correte->getStyleByColumnAndRow(2,6)->getFont()->setBold(false);		
		} else {
			$foglio_correte = $this->setActiveSheetIndex($indice_foglio);
			$foglio_correte->setCellValueByColumnAndRow(3,1, $nome_elenco);
			$foglio_correte->getStyleByColumnAndRow(3,1)->getFont()->setBold(false);			
		}
	}
	
	private function setDatiFoglio($indice_foglio){

		$foglio_correte = $this->setActiveSheetIndex($indice_foglio);
		
		//SE CI SONO LE RICONFERME AGGIUNGO 5 ALL'INDICE PER GESTIRE LE NUOVE QUERY
		if ($this->secFinestra == 1)
			$indice_foglio = $indice_foglio + 5;
		
		
		
		switch ($indice_foglio) {	
			case 0:
				/*PRIMO SPECCHIETTO*/				
							
				$foglio_correte->setCellValueByColumnAndRow(1, 7, "DOMANDE PERVENTUE");
				$foglio_correte->getStyleByColumnAndRow(1, 7)->getFont()->setBold(true);								
				$foglio_correte->setCellValue('C7', "=COUNTA(Pervenute!A9:A65000)"); 							

				$foglio_correte->setCellValueByColumnAndRow(1, 8, "AMMISSIBILI");
				$foglio_correte->getStyleByColumnAndRow(1, 8)->getFont()->setBold(true);
				$foglio_correte->setCellValue('C8', "=COUNTA(Ammissibili!A9:A65000)"); 
				
				$foglio_correte->setCellValueByColumnAndRow(1, 9, "FINANZIABILI");
				$foglio_correte->getStyleByColumnAndRow(1, 9)->getFont()->setBold(true);
				$foglio_correte->setCellValue('C9', "=COUNTA('Finanziabili'!A9:A65000)"); 
				
				$foglio_correte->setCellValueByColumnAndRow(1, 10, "NON AMMESSE");
				$foglio_correte->getStyleByColumnAndRow(1, 10)->getFont()->setBold(true);
				$foglio_correte->setCellValue('C10', "=COUNTA('Non Ammesse'!A9:A65000)");
				
				/*SECONDO SPECCHIETTO*/			
				
				$foglio_correte->setCellValueByColumnAndRow(5, 7, "DOTAZIONE FINANZIATIA");
				$foglio_correte->getStyleByColumnAndRow(5, 7)->getFont()->setBold(true);	
				
				$foglio_correte->setCellValueByColumnAndRow(6, 7, $this->dotazione_finanziaria);						

				$foglio_correte->setCellValueByColumnAndRow(5, 8, "IMPORTO FINANZIATO");
				$foglio_correte->getStyleByColumnAndRow(5, 8)->getFont()->setBold(true);	
				$foglio_correte->getStyle('G7')->getNumberFormat()->setFormatCode("#,##0.00");
				
				$foglio_correte->setCellValue('G8', "=SUM(Finanziabili!I9:I65000)"); 		
				$foglio_correte->getStyle('G8')->getNumberFormat()->setFormatCode("#,##0.00");
				
				$foglio_correte->setCellValueByColumnAndRow(5, 9, "RESIDUO");
				$foglio_correte->getStyleByColumnAndRow(5, 9)->getFont()->setBold(true);	
				$foglio_correte->setCellValue('G9', "=G7-G8"); 	
				$foglio_correte->getStyle('G9')->getNumberFormat()->setFormatCode("#,##0.00");
				
				$foglio_correte->setCellValueByColumnAndRow(5, 10, "IMPORTO NON FINANZIABILE");
				$foglio_correte->getStyleByColumnAndRow(5, 10)->getFont()->setBold(true);	
				
				$foglio_correte->setCellValue('G10', "=SUM('Ammissibili'!I9:I65000)-G8"); 		
				$foglio_correte->getStyle('G10')->getNumberFormat()->setFormatCode("#,##0.00");
				
				/*LARGHEZZA*/
				$foglio_correte->getColumnDimension('C')->setWidth(20);	
				$foglio_correte->getColumnDimension('B')->setWidth(30);
				
				$foglio_correte->getColumnDimension('F')->setWidth(30);	
				$foglio_correte->getColumnDimension('G')->setWidth(20);				

				/*GRIGLIA*/
				$this->getActiveSheet()->getStyle('B7:C10')->applyFromArray($this->BStyle);
				$this->getActiveSheet()->getStyle('F7:G10')->applyFromArray($this->BStyle);					
				break;
		
		
			case 1: //PERVENUTE	
				$NumColonne = 7;
				$Riga = 9;
				
				/* INTESTAZIONE */
				$foglio_correte->setCellValueByColumnAndRow(0, 8, "Nr.");
				$foglio_correte->getStyleByColumnAndRow(0, 8)->getFont()->setBold(true);	
				
				$foglio_correte->setCellValueByColumnAndRow(1, 8, "Nr. posizione");
				$foglio_correte->getStyleByColumnAndRow(1, 8)->getFont()->setBold(true);
		
				$foglio_correte->setCellValueByColumnAndRow(2, 8, "Nr. domanda");
				$foglio_correte->getStyleByColumnAndRow(2, 8)->getFont()->setBold(true);
		
				$foglio_correte->setCellValueByColumnAndRow(3, 8, "Cognome/Nome/Ragione Sociale");
				$foglio_correte->getStyleByColumnAndRow(3, 8)->getFont()->setBold(true);
		
				$foglio_correte->setCellValueByColumnAndRow(4, 8, "Residenza (Indirizzo e Comune)");
				$foglio_correte->getStyleByColumnAndRow(4, 8)->getFont()->setBold(true);
		
				$foglio_correte->setCellValueByColumnAndRow(5, 8, "CUAA");
				$foglio_correte->getStyleByColumnAndRow(5, 8)->getFont()->setBold(true);		

				$foglio_correte->setCellValueByColumnAndRow(6, 8, "Tecnico Istruttore");
				$foglio_correte->getStyleByColumnAndRow(6, 8)->getFont()->setBold(true);
				/*DATI*/		
				$this->getData($indice_foglio);

				
				foreach( $this->data as $row ){ 
					for ($colonna = 0; $colonna < $NumColonne; $colonna++){
						$foglio_correte->setCellValueByColumnAndRow($colonna, $Riga, $row[$colonna], 'inlineStr');								
					}
					$Riga++;
				}
					
				/*GRIGLIA*/
				$fine = $this->coordinates($NumColonne-1, $Riga-1);
				$range = "A8:" . $fine;
				$this->getActiveSheet()->getStyle($range)->applyFromArray($this->BStyle);		
						
						
				/*LARGHEZZA COLONNE*/
				$foglio_correte->getColumnDimension('A')->setWidth(8);								
				$foglio_correte->getColumnDimension('B')->setWidth(11);								
				$foglio_correte->getColumnDimension('C')->setWidth(13);								
				$foglio_correte->getColumnDimension('D')->setWidth(65);								
				$foglio_correte->getColumnDimension('E')->setWidth(65);					
				$foglio_correte->getColumnDimension('F')->setWidth(20);		
				$foglio_correte->getColumnDimension('G')->setWidth(15);		
				$foglio_correte->getColumnDimension('H')->setWidth(21);	
				break;	
			case 2: //AMMISSIBILI
			case 3: //FINANZIBILI								
				$NumColonne = 9;
				$Riga = 9;
				
				/* INTESTAZIONE */
				$foglio_correte->setCellValueByColumnAndRow(0, 8, "Posizione in graduatoria");
				$foglio_correte->getStyleByColumnAndRow(0, 8)->getFont()->setBold(true);	
				
				$foglio_correte->setCellValueByColumnAndRow(1, 8, "Nr. posizione");
				$foglio_correte->getStyleByColumnAndRow(1, 8)->getFont()->setBold(true);
		
				$foglio_correte->setCellValueByColumnAndRow(2, 8, "Nr. domanda");
				$foglio_correte->getStyleByColumnAndRow(2, 8)->getFont()->setBold(true);
		
				$foglio_correte->setCellValueByColumnAndRow(3, 8, "Cognome/Nome/Ragione Sociale");
				$foglio_correte->getStyleByColumnAndRow(3, 8)->getFont()->setBold(true);
		
				$foglio_correte->setCellValueByColumnAndRow(4, 8, "Residenza (Indirizzo e Comune)");
				$foglio_correte->getStyleByColumnAndRow(4, 8)->getFont()->setBold(true);
		
				$foglio_correte->setCellValueByColumnAndRow(5, 8, "CUAA");
				$foglio_correte->getStyleByColumnAndRow(5, 8)->getFont()->setBold(true);	

				$foglio_correte->setCellValueByColumnAndRow(6, 8, "Punteggio totale");
				$foglio_correte->getStyleByColumnAndRow(6, 8)->getFont()->setBold(true);
		
				$foglio_correte->setCellValueByColumnAndRow(7, 8, "Data di nascita");
				$foglio_correte->getStyleByColumnAndRow(7, 8)->getFont()->setBold(true);		

				$foglio_correte->setCellValueByColumnAndRow(8, 8, "Aiuto ammesso");
				$foglio_correte->getStyleByColumnAndRow(8, 8)->getFont()->setBold(true);	
					
				/*DATI*/		
				$this->getData($indice_foglio);

				
				foreach( $this->data as $row ){ 
					for ($colonna = 0; $colonna < $NumColonne; $colonna++){
						$foglio_correte->setCellValueByColumnAndRow($colonna, $Riga, $row[$colonna], 'inlineStr');								
					}
					$Riga++;
				}
					
				/*GRIGLIA*/
				$fine = $this->coordinates($NumColonne-1, $Riga-1);
				$range = "A8:" . $fine;
				$this->getActiveSheet()->getStyle($range)->applyFromArray($this->BStyle);	
				
				/*LARGHEZZA COLONNE*/
				$foglio_correte->getColumnDimension('A')->setWidth(8);								
				$foglio_correte->getColumnDimension('B')->setWidth(11);								
				$foglio_correte->getColumnDimension('C')->setWidth(13);								
				$foglio_correte->getColumnDimension('D')->setWidth(65);								
				$foglio_correte->getColumnDimension('E')->setWidth(65);					
				$foglio_correte->getColumnDimension('F')->setWidth(20);	
				$foglio_correte->getColumnDimension('G')->setWidth(20);	
				$foglio_correte->getColumnDimension('H')->setWidth(20);	
				$foglio_correte->getColumnDimension('I')->setWidth(20);						
				break;	
			case 4: //NON FINANZIBILI								
				$NumColonne = 8;
				$Riga = 9;
				
				/* INTESTAZIONE */
				$foglio_correte->setCellValueByColumnAndRow(0, 8, "Nr.");
				$foglio_correte->getStyleByColumnAndRow(0, 8)->getFont()->setBold(true);	
				
				$foglio_correte->setCellValueByColumnAndRow(1, 8, "Nr. posizione");
				$foglio_correte->getStyleByColumnAndRow(1, 8)->getFont()->setBold(true);
		
				$foglio_correte->setCellValueByColumnAndRow(2, 8, "Nr. domanda");
				$foglio_correte->getStyleByColumnAndRow(2, 8)->getFont()->setBold(true);
		
				$foglio_correte->setCellValueByColumnAndRow(3, 8, "Cognome/Nome/Ragione Sociale");
				$foglio_correte->getStyleByColumnAndRow(3, 8)->getFont()->setBold(true);
		
				$foglio_correte->setCellValueByColumnAndRow(4, 8, "Residenza (Indirizzo e Comune)");
				$foglio_correte->getStyleByColumnAndRow(4, 8)->getFont()->setBold(true);
		
				$foglio_correte->setCellValueByColumnAndRow(5, 8, "CUAA");
				$foglio_correte->getStyleByColumnAndRow(5, 8)->getFont()->setBold(true);		
	
				$foglio_correte->setCellValueByColumnAndRow(6, 8, "Motivo di esclusione");
				$foglio_correte->getStyleByColumnAndRow(6, 8)->getFont()->setBold(true);	


				$foglio_correte->setCellValueByColumnAndRow(7, 8, "Tecnico Istruttore");
				$foglio_correte->getStyleByColumnAndRow(7, 8)->getFont()->setBold(true);				
				/*DATI*/		
				$this->getData($indice_foglio);
				
				foreach( $this->data as $row ){ 
					for ($colonna = 0; $colonna < $NumColonne; $colonna++){
						$foglio_correte->setCellValueByColumnAndRow($colonna, $Riga, $row[$colonna], 'inlineStr');								
					}
					$Riga++;
				}
					
				/*GRIGLIA*/
				$fine = $this->coordinates($NumColonne-1, $Riga-1);
				$range = "A8:" . $fine;
				$this->getActiveSheet()->getStyle($range)->applyFromArray($this->BStyle);	
					
				/*LARGHEZZA COLONNE*/
				$foglio_correte->getColumnDimension('A')->setWidth(8);								
				$foglio_correte->getColumnDimension('B')->setWidth(11);								
				$foglio_correte->getColumnDimension('C')->setWidth(13);								
				$foglio_correte->getColumnDimension('D')->setWidth(65);								
				$foglio_correte->getColumnDimension('E')->setWidth(65);					
				$foglio_correte->getColumnDimension('F')->setWidth(20);		
				$foglio_correte->getColumnDimension('G')->setWidth(65);	
				$foglio_correte->getColumnDimension('H')->setWidth(20);						
				break;
/************************************************************************************************************************************
										
										SECONDA FINESTRA				

************************************************************************************************************************************/
		case 5:
				/*PRIMO SPECCHIETTO*/				
							
				$foglio_correte->setCellValueByColumnAndRow(1, 7, "DOMANDE PERVENUTE");
				$foglio_correte->getStyleByColumnAndRow(1, 7)->getFont()->setBold(true);								
				$foglio_correte->setCellValue('C7', "=COUNTA(Pervenute!A9:A65000)"); 		
				
				$foglio_correte->setCellValueByColumnAndRow(1, 8, "DOMANDE DI CONFERMA");
				$foglio_correte->getStyleByColumnAndRow(1, 8)->getFont()->setBold(true);								
				$foglio_correte->setCellValue('C8', "=COUNTA(Conferme!A9:A65000)"); 						

				$foglio_correte->setCellValueByColumnAndRow(1, 9, "AMMISSIBILI");
				$foglio_correte->getStyleByColumnAndRow(1, 9)->getFont()->setBold(true);
				$foglio_correte->setCellValue('C9', "=COUNTA(Ammissibili!A9:A65000)"); 
				
				$foglio_correte->setCellValueByColumnAndRow(1, 10, "FINANZIABILI");
				$foglio_correte->getStyleByColumnAndRow(1, 10)->getFont()->setBold(true);
				$foglio_correte->setCellValue('C10', "=COUNTA('Finanziabili'!A9:A65000)"); 
				
				$foglio_correte->setCellValueByColumnAndRow(1, 11, "NON AMMESSE");
				$foglio_correte->getStyleByColumnAndRow(1, 11)->getFont()->setBold(true);
				$foglio_correte->setCellValue('C11', "=COUNTA('Non Ammesse'!A9:A65000)");

				
				/*SECONDO SPECCHIETTO*/			
				
				$foglio_correte->setCellValueByColumnAndRow(5, 7, "DOTAZIONE FINANZIATIA");
				$foglio_correte->getStyleByColumnAndRow(5, 7)->getFont()->setBold(true);	
				
				$foglio_correte->setCellValueByColumnAndRow(6, 7, $this->dotazione_finanziaria);						

				$foglio_correte->setCellValueByColumnAndRow(5, 8, "IMPORTO FINANZIATO");
				$foglio_correte->getStyleByColumnAndRow(5, 8)->getFont()->setBold(true);	
				$foglio_correte->getStyle('G7')->getNumberFormat()->setFormatCode("#,##0.00");
				
				$foglio_correte->setCellValue('G8', "=SUM(Finanziabili!I9:I65000)"); 		
				$foglio_correte->getStyle('G8')->getNumberFormat()->setFormatCode("#,##0.00");
				
				$foglio_correte->setCellValueByColumnAndRow(5, 9, "RESIDUO");
				$foglio_correte->getStyleByColumnAndRow(5, 9)->getFont()->setBold(true);	
				$foglio_correte->setCellValue('G9', "=G7-G8"); 	
				$foglio_correte->getStyle('G9')->getNumberFormat()->setFormatCode("#,##0.00");
				
				$foglio_correte->setCellValueByColumnAndRow(5, 10, "IMPORTO NON FINANZIABILE");
				$foglio_correte->getStyleByColumnAndRow(5, 10)->getFont()->setBold(true);	
				
				$foglio_correte->setCellValue('G10', "=SUM('Ammissibili'!I9:I65000)-G8"); 		
				$foglio_correte->getStyle('G10')->getNumberFormat()->setFormatCode("#,##0.00");
				
				/*LARGHEZZA*/
				$foglio_correte->getColumnDimension('C')->setWidth(20);	
				$foglio_correte->getColumnDimension('B')->setWidth(30);
				
				$foglio_correte->getColumnDimension('F')->setWidth(30);	
				$foglio_correte->getColumnDimension('G')->setWidth(20);				

				/*GRIGLIA*/
				$this->getActiveSheet()->getStyle('B7:C11')->applyFromArray($this->BStyle);
				$this->getActiveSheet()->getStyle('F7:G10')->applyFromArray($this->BStyle);					
				break;				
			case 6: //PERVENUTE
			case 7: //CONFERME				
				$NumColonne = 7;
				$Riga = 9;
				
				/* INTESTAZIONE */
				$foglio_correte->setCellValueByColumnAndRow(0, 8, "Nr.");
				$foglio_correte->getStyleByColumnAndRow(0, 8)->getFont()->setBold(true);	
				
				$foglio_correte->setCellValueByColumnAndRow(1, 8, "Nr. posizione");
				$foglio_correte->getStyleByColumnAndRow(1, 8)->getFont()->setBold(true);
		
				$foglio_correte->setCellValueByColumnAndRow(2, 8, "Nr. domanda");
				$foglio_correte->getStyleByColumnAndRow(2, 8)->getFont()->setBold(true);
		
				$foglio_correte->setCellValueByColumnAndRow(3, 8, "Cognome/Nome/Ragione Sociale");
				$foglio_correte->getStyleByColumnAndRow(3, 8)->getFont()->setBold(true);
		
				$foglio_correte->setCellValueByColumnAndRow(4, 8, "Residenza (Indirizzo e Comune)");
				$foglio_correte->getStyleByColumnAndRow(4, 8)->getFont()->setBold(true);
		
				$foglio_correte->setCellValueByColumnAndRow(5, 8, "CUAA");
				$foglio_correte->getStyleByColumnAndRow(5, 8)->getFont()->setBold(true);		

				$foglio_correte->setCellValueByColumnAndRow(6, 8, "Tecnico Istruttore");
				$foglio_correte->getStyleByColumnAndRow(6, 8)->getFont()->setBold(true);
				/*DATI*/		
				$this->getData($indice_foglio);

				
				foreach( $this->data as $row ){ 
					for ($colonna = 0; $colonna < $NumColonne; $colonna++){
						$foglio_correte->setCellValueByColumnAndRow($colonna, $Riga, $row[$colonna], 'inlineStr');								
					}
					$Riga++;
				}
					
				/*GRIGLIA*/
				$fine = $this->coordinates($NumColonne-1, $Riga-1);
				$range = "A8:" . $fine;
				$this->getActiveSheet()->getStyle($range)->applyFromArray($this->BStyle);		
						
						
				/*LARGHEZZA COLONNE*/
				$foglio_correte->getColumnDimension('A')->setWidth(8);								
				$foglio_correte->getColumnDimension('B')->setWidth(11);								
				$foglio_correte->getColumnDimension('C')->setWidth(13);								
				$foglio_correte->getColumnDimension('D')->setWidth(65);								
				$foglio_correte->getColumnDimension('E')->setWidth(65);					
				$foglio_correte->getColumnDimension('F')->setWidth(20);		
				$foglio_correte->getColumnDimension('G')->setWidth(15);		
				$foglio_correte->getColumnDimension('H')->setWidth(40);	
				break;						
			case 8: //AMMISSIBILI				
			case 10: //FINANZIBILI								
				$NumColonne = 10;
				$Riga = 9;
				
				/* INTESTAZIONE */
				$foglio_correte->setCellValueByColumnAndRow(0, 8, "Posizione in graduatoria");
				$foglio_correte->getStyleByColumnAndRow(0, 8)->getFont()->setBold(true);	
				
				$foglio_correte->setCellValueByColumnAndRow(1, 8, "Nr. posizione");
				$foglio_correte->getStyleByColumnAndRow(1, 8)->getFont()->setBold(true);
		
				$foglio_correte->setCellValueByColumnAndRow(2, 8, "Nr. domanda");
				$foglio_correte->getStyleByColumnAndRow(2, 8)->getFont()->setBold(true);
		
				$foglio_correte->setCellValueByColumnAndRow(3, 8, "Cognome/Nome/Ragione Sociale");
				$foglio_correte->getStyleByColumnAndRow(3, 8)->getFont()->setBold(true);
		
				$foglio_correte->setCellValueByColumnAndRow(4, 8, "Residenza (Indirizzo e Comune)");
				$foglio_correte->getStyleByColumnAndRow(4, 8)->getFont()->setBold(true);
		
				$foglio_correte->setCellValueByColumnAndRow(5, 8, "CUAA");
				$foglio_correte->getStyleByColumnAndRow(5, 8)->getFont()->setBold(true);	

				$foglio_correte->setCellValueByColumnAndRow(6, 8, "Punteggio totale");
				$foglio_correte->getStyleByColumnAndRow(6, 8)->getFont()->setBold(true);
		
				$foglio_correte->setCellValueByColumnAndRow(7, 8, "Data di nascita");
				$foglio_correte->getStyleByColumnAndRow(7, 8)->getFont()->setBold(true);		

				$foglio_correte->setCellValueByColumnAndRow(8, 8, "Aiuto ammesso");
				$foglio_correte->getStyleByColumnAndRow(8, 8)->getFont()->setBold(true);	
					
				$foglio_correte->setCellValueByColumnAndRow(9, 8, "Domanda conferma");
				$foglio_correte->getStyleByColumnAndRow(9, 8)->getFont()->setBold(true);	
				
				/*DATI*/		
				$this->getData($indice_foglio);

				
				foreach( $this->data as $row ){ 
					for ($colonna = 0; $colonna < $NumColonne; $colonna++){
						$foglio_correte->setCellValueByColumnAndRow($colonna, $Riga, $row[$colonna], 'inlineStr');								
					}
					$Riga++;
				}
					
				/*GRIGLIA*/
				$fine = $this->coordinates($NumColonne-1, $Riga-1);
				$range = "A8:" . $fine;
				$this->getActiveSheet()->getStyle($range)->applyFromArray($this->BStyle);	
				
				/*NOTA*/
				$foglio_correte->setCellValueByColumnAndRow(1, $Riga+2, "* Domande di conferma");
				$foglio_correte->getStyleByColumnAndRow(1, $Riga+2)->getFont()->setBold(true);	
				
				
				/*LARGHEZZA COLONNE*/
				$foglio_correte->getColumnDimension('A')->setWidth(8);								
				$foglio_correte->getColumnDimension('B')->setWidth(11);								
				$foglio_correte->getColumnDimension('C')->setWidth(13);								
				$foglio_correte->getColumnDimension('D')->setWidth(65);								
				$foglio_correte->getColumnDimension('E')->setWidth(65);					
				$foglio_correte->getColumnDimension('F')->setWidth(20);	
				$foglio_correte->getColumnDimension('G')->setWidth(20);	
				$foglio_correte->getColumnDimension('H')->setWidth(20);	
				$foglio_correte->getColumnDimension('I')->setWidth(20);		
				$foglio_correte->getColumnDimension('J')->setWidth(15);		
				break;	
			case 9: //NON FINANZIBILI								
				$NumColonne = 9;
				$Riga = 9;
				
				/* INTESTAZIONE */
				$foglio_correte->setCellValueByColumnAndRow(0, 8, "Nr.");
				$foglio_correte->getStyleByColumnAndRow(0, 8)->getFont()->setBold(true);	
				
				$foglio_correte->setCellValueByColumnAndRow(1, 8, "Nr. posizione");
				$foglio_correte->getStyleByColumnAndRow(1, 8)->getFont()->setBold(true);
		
				$foglio_correte->setCellValueByColumnAndRow(2, 8, "Nr. domanda");
				$foglio_correte->getStyleByColumnAndRow(2, 8)->getFont()->setBold(true);
		
				$foglio_correte->setCellValueByColumnAndRow(3, 8, "Cognome/Nome/Ragione Sociale");
				$foglio_correte->getStyleByColumnAndRow(3, 8)->getFont()->setBold(true);
		
				$foglio_correte->setCellValueByColumnAndRow(4, 8, "Residenza (Indirizzo e Comune)");
				$foglio_correte->getStyleByColumnAndRow(4, 8)->getFont()->setBold(true);
		
				$foglio_correte->setCellValueByColumnAndRow(5, 8, "CUAA");
				$foglio_correte->getStyleByColumnAndRow(5, 8)->getFont()->setBold(true);		
				
				$foglio_correte->setCellValueByColumnAndRow(6, 8, "Domanda conferma");
				$foglio_correte->getStyleByColumnAndRow(6, 8)->getFont()->setBold(true);					
	
				$foglio_correte->setCellValueByColumnAndRow(7, 8, "Motivo di esclusione");
				$foglio_correte->getStyleByColumnAndRow(7, 8)->getFont()->setBold(true);	

				$foglio_correte->setCellValueByColumnAndRow(8, 8, "Tecnico Istruttore");
				$foglio_correte->getStyleByColumnAndRow(8, 8)->getFont()->setBold(true);				
				/*DATI*/		
				$this->getData($indice_foglio);
				
				foreach( $this->data as $row ){ 
					for ($colonna = 0; $colonna < $NumColonne; $colonna++){
						$foglio_correte->setCellValueByColumnAndRow($colonna, $Riga, $row[$colonna], 'inlineStr');								
					}
					$Riga++;
				}
					
				/*GRIGLIA*/
				$fine = $this->coordinates($NumColonne-1, $Riga-1);
				$range = "A8:" . $fine;
				$this->getActiveSheet()->getStyle($range)->applyFromArray($this->BStyle);	
					
				/*NOTA*/
				$foglio_correte->setCellValueByColumnAndRow(1, $Riga+2, "* Domande di conferma");
				$foglio_correte->getStyleByColumnAndRow(1, $Riga+2)->getFont()->setBold(true);	
				
				/*LARGHEZZA COLONNE*/
				$foglio_correte->getColumnDimension('A')->setWidth(8);								
				$foglio_correte->getColumnDimension('B')->setWidth(11);								
				$foglio_correte->getColumnDimension('C')->setWidth(13);								
				$foglio_correte->getColumnDimension('D')->setWidth(65);								
				$foglio_correte->getColumnDimension('E')->setWidth(65);					
				$foglio_correte->getColumnDimension('F')->setWidth(20);	
				$foglio_correte->getColumnDimension('G')->setWidth(15);	
				$foglio_correte->getColumnDimension('H')->setWidth(65);	
				$foglio_correte->getColumnDimension('I')->setWidth(20);						
				break;				
		}		
	}	
	
	private function getData($indice_foglio) {	
		$db = new MySQL();
		$this->data = null;
		
		switch ($indice_foglio) {	
		
			case 1: //PERVENUTE	
				$sQuery = "
							SELECT @riga:=@riga+1 AS n_progressivo, n_fascicolazione,barcode_DA,ragione_sociale,indirizzo,cuaa,tecnico
							FROM (
								SELECT
									tb_domanda.n_fascicolazione,
									tb_domanda.barcode_DA,
									v_anagrafica.ragione_sociale,
									CONCAT(UPPER(v_anagrafica.residenza_indirizzo), ' - ', v_anagrafica.residenza_cap, ' ',
									UPPER(v_anagrafica.des_residenza_comune),' (', v_anagrafica.residenza_sigla_provincia, ')') as indirizzo,
									tb_domanda.cuaa, CONCAT(sc_utente.cognome, ' ',sc_utente.nome) as tecnico
								FROM 
									tb_domanda,v_anagrafica,sc_utente
								WHERE tb_domanda.cuaa = v_anagrafica.cuaa AND
									tb_domanda.id_utente=sc_utente.id_utente AND
									tb_domanda.id_graduatoria=". $this->ID_Elenco . "
								ORDER BY ragione_sociale
							) AS PRESENTATE, (SELECT @riga:=0) table_progressivo
						";				
				break;	
			case 2:
				$sQuery = "
					SELECT n_progressivo, n_fascicolazione,barcode_DA,ragione_sociale,indirizzo,cuaa,punteggio,data_nascita,contributo_ammesso FROM 
					(
						SELECT @riga:=@riga+1 AS n_progressivo, n_fascicolazione,barcode_DA,
										ragione_sociale,indirizzo,cuaa,punteggio,date_format(data_nascita,'%d/%m/%Y') AS data_nascita,
										contributo_ammesso , @total:=@total+contributo_ammesso AS RunningSum FROM
						(
							SELECT
								tb_domanda.n_fascicolazione,
								tb_domanda.barcode_DA,
								v_anagrafica.ragione_sociale,
								CONCAT(UPPER(v_anagrafica.residenza_indirizzo), ' - ', v_anagrafica.residenza_cap, ' ',
								UPPER(v_anagrafica.des_residenza_comune),' (', v_anagrafica.residenza_sigla_provincia, ')') as indirizzo,
								tb_domanda.cuaa,
								tb_domanda.punteggio,
								STR_TO_DATE(v_anagrafica.data_nascita, '%d/%m/%Y') as data_nascita,
								tb_domanda.contributo_ammesso
							FROM 
								tb_domanda,v_anagrafica,tb_graduatoria
							WHERE tb_domanda.cuaa = v_anagrafica.cuaa
								AND tb_domanda.id_graduatoria = tb_graduatoria.id_graduatoria
								AND id_stato_domanda>=5000 
								AND tb_domanda.punteggio>=tb_graduatoria.punteggio_min
								AND tb_domanda.id_graduatoria=". $this->ID_Elenco . "
							ORDER BY punteggio DESC, data_nascita DESC
						) AS AMMESSE,(SELECT @riga:=0) AS table_progressivo,(SELECT @total:=0) table_totale
					) AS TUTTE_AMMESSE ";	
				break;					
			case 3:
				$sQuery = "
					SELECT n_progressivo, n_fascicolazione,barcode_DA,ragione_sociale,indirizzo,cuaa,punteggio,data_nascita,contributo_ammesso FROM 
					(
						SELECT @riga:=@riga+1 AS n_progressivo, n_fascicolazione,barcode_DA,
										ragione_sociale,indirizzo,cuaa,punteggio,date_format(data_nascita,'%d/%m/%Y') AS data_nascita,
										contributo_ammesso , @total:=@total+contributo_ammesso AS RunningSum FROM
						(
							SELECT
								tb_domanda.n_fascicolazione,
								tb_domanda.barcode_DA,
								v_anagrafica.ragione_sociale,
								CONCAT(UPPER(v_anagrafica.residenza_indirizzo), ' - ', v_anagrafica.residenza_cap, ' ',
								UPPER(v_anagrafica.des_residenza_comune),' (', v_anagrafica.residenza_sigla_provincia, ')') as indirizzo,
								tb_domanda.cuaa,
								tb_domanda.punteggio,
								STR_TO_DATE(v_anagrafica.data_nascita, '%d/%m/%Y') as data_nascita,
								tb_domanda.contributo_ammesso
							FROM 
								tb_domanda,v_anagrafica,tb_graduatoria
							WHERE tb_domanda.cuaa = v_anagrafica.cuaa
								AND tb_domanda.id_graduatoria = tb_graduatoria.id_graduatoria
								AND id_stato_domanda>=5000 
								AND tb_domanda.punteggio>=tb_graduatoria.punteggio_min
								AND tb_domanda.id_graduatoria=". $this->ID_Elenco . "
							ORDER BY punteggio DESC, data_nascita DESC
						) AS AMMESSE,(SELECT @riga:=0) AS table_progressivo,(SELECT @total:=0) table_totale
					) AS TUTTE_AMMESSE WHERE  RunningSum <= " . $this->dotazione_finanziaria;				
				break;		
			case 4:
				$sQuery = "
					SELECT @riga:=@riga+1 AS n_progressivo, n_fascicolazione,barcode_DA,ragione_sociale,
						indirizzo,cuaa,motivazioni_esclusione,tecnico
						FROM (
							SELECT
								tb_domanda.n_fascicolazione,
								tb_domanda.barcode_DA,
								v_anagrafica.ragione_sociale,
								CONCAT(UPPER(v_anagrafica.residenza_indirizzo), ' - ', v_anagrafica.residenza_cap, ' ',
								UPPER(v_anagrafica.des_residenza_comune),' (', v_anagrafica.residenza_sigla_provincia, ')') as indirizzo,
								tb_domanda.cuaa,
								tb_domanda.motivazioni_esclusione,CONCAT(sc_utente.cognome, ' ',sc_utente.nome) as tecnico
							FROM 
								tb_domanda,v_anagrafica,sc_utente
							WHERE tb_domanda.cuaa = v_anagrafica.cuaa
								AND id_stato_domanda in (2101,3101,4101)
								AND tb_domanda.id_graduatoria=". $this->ID_Elenco . "
								AND tb_domanda.id_utente=sc_utente.id_utente 
							ORDER BY ragione_sociale
						) AS ESCLUSE, (SELECT @riga:=0) table_progressivo							
				";	
				break;	
				
				//SECONDA FINESTRA
				
			case 6: //PERVENUTE	
				$sQuery = "
							SELECT @riga:=@riga+1 AS n_progressivo, n_fascicolazione,barcode_DA,ragione_sociale,indirizzo,cuaa,tecnico
							FROM (
								SELECT
									tb_domanda.n_fascicolazione,
									tb_domanda.barcode_DA,
									v_anagrafica.ragione_sociale,
									CONCAT(UPPER(v_anagrafica.residenza_indirizzo), ' - ', v_anagrafica.residenza_cap, ' ',
									UPPER(v_anagrafica.des_residenza_comune),' (', v_anagrafica.residenza_sigla_provincia, ')') as indirizzo,
									tb_domanda.cuaa, CONCAT(sc_utente.cognome, ' ',sc_utente.nome) as tecnico
								FROM 
									tb_domanda,v_anagrafica,sc_utente
								WHERE tb_domanda.cuaa = v_anagrafica.cuaa AND
									tb_domanda.id_utente=sc_utente.id_utente AND
									tb_domanda.id_graduatoria=". $this->ID_Elenco . "
								ORDER BY ragione_sociale
							) AS PRESENTATE, (SELECT @riga:=0) table_progressivo
						";				
				break;	
			case 7: //CONFERME
				$sQuery = "
							SELECT @riga:=@riga+1 AS n_progressivo, n_fascicolazione,barcode_DA,ragione_sociale,indirizzo,cuaa,tecnico
							FROM (
								SELECT
									tb_domanda.n_fascicolazione,
									tb_domanda.barcode_DA,
									v_anagrafica.ragione_sociale,
									CONCAT(UPPER(v_anagrafica.residenza_indirizzo), ' - ', v_anagrafica.residenza_cap, ' ',
									UPPER(v_anagrafica.des_residenza_comune),' (', v_anagrafica.residenza_sigla_provincia, ')') as indirizzo,
									tb_domanda.cuaa, CONCAT(sc_utente.cognome, ' ',sc_utente.nome) as tecnico
								FROM 
									tb_domanda,v_anagrafica,sc_utente
								WHERE tb_domanda.cuaa = v_anagrafica.cuaa AND
									tb_domanda.id_utente=sc_utente.id_utente AND
									tb_domanda.flg_domanda_ripresentata!=0
								ORDER BY ragione_sociale
							) AS PRESENTATE, (SELECT @riga:=0) table_progressivo
						";				
				break;					
			case 8: //AMMESSE
				$sQuery = "
							SELECT n_progressivo, n_fascicolazione,barcode_DA,ragione_sociale,indirizzo,cuaa,punteggio,data_nascita,
							contributo_ammesso,CASE WHEN flg_domanda_ripresentata=0 THEN '' ELSE '*' END AS flg_domanda_ripresentata FROM 
							(
								SELECT @riga:=@riga+1 AS n_progressivo, n_fascicolazione,barcode_DA,
												ragione_sociale,indirizzo,cuaa,punteggio,date_format(data_nascita,'%d/%m/%Y') AS data_nascita,
												contributo_ammesso , flg_domanda_ripresentata, @total:=@total+contributo_ammesso AS RunningSum FROM
								(
									SELECT
										tb_domanda.n_fascicolazione,
										tb_domanda.barcode_DA,
										v_anagrafica.ragione_sociale,
										CONCAT(UPPER(v_anagrafica.residenza_indirizzo), ' - ', v_anagrafica.residenza_cap, ' ',
										UPPER(v_anagrafica.des_residenza_comune),' (', v_anagrafica.residenza_sigla_provincia, ')') as indirizzo,
										tb_domanda.cuaa,
										tb_domanda.punteggio,
										STR_TO_DATE(v_anagrafica.data_nascita, '%d/%m/%Y') as data_nascita,
										tb_domanda.contributo_ammesso,
										flg_domanda_ripresentata
									FROM 
										tb_domanda,v_anagrafica,tb_graduatoria
									WHERE tb_domanda.cuaa = v_anagrafica.cuaa
										AND tb_domanda.id_graduatoria = tb_graduatoria.id_graduatoria
										AND id_stato_domanda>=5000 
										AND tb_domanda.punteggio>=tb_graduatoria.punteggio_min
										AND (tb_domanda.flg_domanda_ripresentata=1 OR tb_domanda.id_graduatoria=". $this->ID_Elenco . ")
									ORDER BY punteggio DESC, data_nascita DESC
								) AS AMMESSE,(SELECT @riga:=0) AS table_progressivo,(SELECT @total:=0) table_totale
							) AS TUTTE_AMMESSE";	
				break;
			case 9: //ESCLUSE
				$sQuery = "
					SELECT @riga:=@riga+1 AS n_progressivo, n_fascicolazione,barcode_DA,ragione_sociale,
					indirizzo,cuaa,
					CASE WHEN flg_domanda_ripresentata=0 THEN '' ELSE '*' END AS flg_domanda_ripresentata,
					motivazioni_esclusione,tecnico
					FROM (
						SELECT
							tb_domanda.n_fascicolazione,
							tb_domanda.barcode_DA,
							v_anagrafica.ragione_sociale,
							CONCAT(UPPER(v_anagrafica.residenza_indirizzo), ' - ', v_anagrafica.residenza_cap, ' ',
							UPPER(v_anagrafica.des_residenza_comune),' (', v_anagrafica.residenza_sigla_provincia, ')') as indirizzo,
							tb_domanda.cuaa, tb_domanda.flg_domanda_ripresentata,
							tb_domanda.motivazioni_esclusione,CONCAT(sc_utente.cognome, ' ',sc_utente.nome) as tecnico
						FROM 
							tb_domanda,v_anagrafica,sc_utente
						WHERE tb_domanda.cuaa = v_anagrafica.cuaa
							AND (id_stato_domanda in (2101,3101,4101)	OR tb_domanda.flg_domanda_ripresentata=-1) 
							AND (tb_domanda.flg_domanda_ripresentata=-1 OR tb_domanda.id_graduatoria=". $this->ID_Elenco . ")
							AND tb_domanda.id_utente=sc_utente.id_utente 
						ORDER BY ragione_sociale
					) AS ESCLUSE, (SELECT @riga:=0) table_progressivo				
				";	
				break;				
			case 10: //FINANZIATE
				$sQuery = "
							SELECT n_progressivo, n_fascicolazione,barcode_DA,ragione_sociale,indirizzo,cuaa,punteggio,data_nascita,contributo_ammesso,
							CASE WHEN flg_domanda_ripresentata=0 THEN '' ELSE '*' END AS flg_domanda_ripresentata FROM 
							(
								SELECT @riga:=@riga+1 AS n_progressivo, n_fascicolazione,barcode_DA,
												ragione_sociale,indirizzo,cuaa,punteggio,date_format(data_nascita,'%d/%m/%Y') AS data_nascita,
												contributo_ammesso , flg_domanda_ripresentata, @total:=@total+contributo_ammesso AS RunningSum FROM
								(
									SELECT
										tb_domanda.n_fascicolazione,
										tb_domanda.barcode_DA,
										v_anagrafica.ragione_sociale,
										CONCAT(UPPER(v_anagrafica.residenza_indirizzo), ' - ', v_anagrafica.residenza_cap, ' ',
										UPPER(v_anagrafica.des_residenza_comune),' (', v_anagrafica.residenza_sigla_provincia, ')') as indirizzo,
										tb_domanda.cuaa,
										tb_domanda.punteggio,
										STR_TO_DATE(v_anagrafica.data_nascita, '%d/%m/%Y') as data_nascita,
										tb_domanda.contributo_ammesso,
										flg_domanda_ripresentata
									FROM 
										tb_domanda,v_anagrafica,tb_graduatoria
									WHERE tb_domanda.cuaa = v_anagrafica.cuaa
										AND tb_domanda.id_graduatoria = tb_graduatoria.id_graduatoria
										AND id_stato_domanda>=5000 
										AND tb_domanda.punteggio>=tb_graduatoria.punteggio_min
										AND (tb_domanda.flg_domanda_ripresentata=1 OR tb_domanda.id_graduatoria=". $this->ID_Elenco . ")
									ORDER BY punteggio DESC, data_nascita DESC
								) AS AMMESSE,(SELECT @riga:=0) AS table_progressivo,(SELECT @total:=0) table_totale
							) AS TUTTE_AMMESSE WHERE  RunningSum <= " . $this->dotazione_finanziaria;			
				break;				
		}
				
		
		if (! $db->Query($sQuery)) $db->Kill();	
		
		/* Data set length after filtering */
		if ($db->RowCount() != 0)
		{
			$this->data = $db->RecordsArray(MYSQL_NUM);					
		}
		
		
	}
	
	private function setDotazione(){
		
		$sql = "SELECT dotazione_finanziaria FROM tb_graduatoria 
				WHERE id_graduatoria=" . $this->ID_Elenco;

		$db = new MySQL();
		if (! $db->Query($sql)) echo $db->Kill();
		
		$this->dotazione_finanziaria = $db->QuerySingleValue( $sql );				
		
	}
	
	private function coordinates($x,$y){
	 	return PHPExcel_Cell::stringFromColumnIndex($x).$y;
	}
	
}

?>