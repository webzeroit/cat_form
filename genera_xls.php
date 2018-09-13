<?

include_once( "model/class.database.php");
include_once( "model/class.utente.php" );
include_once( "model/class.checklist.php" );
include_once( "add-on/PHPExcel-1.8/PHPExcel.php" );
include_once( "model/class.excel.php" );

if (isset($_REQUEST["op"]))
{
    $cod_export = $_REQUEST["op"];

    $Excel = new Excel();

    switch ($cod_export)
    {
        case "xls_domande":
            $Excel->Table = "v_cform_domanda_export";
            $Excel->IndexColumn = "id_progressivo";
            $Excel->SelectColumns = array('id_arrivo','id_istanza','denominazione_ente',
                'identificativo_fiscale_ente','data_invio','ora_invio',
                'numero_corsi','nome','cognome',
                'id_stato_checklist','data_caricamento','esito','note');
            $Excel->HeaderColumns = array('ID PROGR.', 'ID ISTANZA', 'DENOMINAZIONE ENTE', 
                                'CF ENTE','DATA INVIO ISTANZA','ORA INVIO ISTANZA',
                                'NUM. CORSI ISTANZA','NOME ISTRUTTORE', 'COGNOME ISTRUTTORE',
                                'STATO CHECKLIST','DATA ULTIMO SALVATAGGIO', 'ESITO VALUTAZIONE','NOTE');
            $Excel->Order = "id_arrivo";
            $Excel->NomeFile = "Lista-Domande-" . date('d-m-Y');
            $Excel->TitoloFoglio = "ListaDomande";
            if (isset($_GET['id_ruolo']) && ($_GET['id_ruolo']) != 'AB_000')
            {
                $Excel->Where = "id_utente=" . $_GET['id_utente'];
            }
            $Excel->setMetaDati();
            $Excel->setIntestazioneFoglio();
            $Excel->setDatiFoglio();
            $Excel->setNomeFoglio();
            break;       

        default:
            exit;
    }

    // Redirect output to a client’s web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $Excel->NomeFile . '.xls"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($Excel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}
exit;