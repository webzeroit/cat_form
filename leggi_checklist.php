<?

include_once( "model/class.database.php");
include_once( "add-on/PHPExcel-1.8/PHPExcel.php" );


ini_set('memory_limit', '1600M');
ini_set('max_execution_time', 6000);
$sql ="";
$db = new MySQL();
$sql = "SELECT * FROM v_cform_domanda WHERE id_stato_checklist=1";
$db->Query($sql);
$domande = $db->RecordsArray(MYSQL_ASSOC);


$sql = "SELECT * FROM ar_campi_excel_out";
$db->Query($sql);
$matrice_campi = $db->RecordsArray(MYSQL_ASSOC);



$esito = createTableCheckList($matrice_campi, TRUE);
/*
  echo "<code><pre>";
  print_r($domande);
  echo "<hr>";
  print_r($matrice_campi);
  echo "<hr>";
  print_r($esito);
  echo "</pre></code>";
 */
foreach ($domande as $domanda)
{
    $id_progressivo = $domanda["id_progressivo"];
    $id_istanza = $domanda["id_istanza"];
    $denominazione_ente = $domanda["denominazione_ente"];
    $identificativo_fiscale_ente = $domanda["identificativo_fiscale_ente"];
    $filename = $domanda["percorso_checklist"];

    if (file_exists($filename))
    {
        $file_type = pathinfo($filename, PATHINFO_EXTENSION);
        if ($file_type == 'xls')
        {
            $objReader = PHPExcel_IOFactory::createReader('Excel5');
        }
        elseif ($file_type == 'xlsx')
        {
            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        }
        $objPHPExcel = $objReader->load($filename);

        $array_insert["id_progressivo"] = $id_progressivo;
        $array_insert["id_istanza"] = $id_istanza;
        $array_insert["denominazione_ente"] = MySQL::SQLValue($denominazione_ente);
        $array_insert["identificativo_fiscale_ente"] = MySQL::SQLValue($identificativo_fiscale_ente);

        foreach ($matrice_campi as $cella)
        {
            $tabella = $cella["tabella_destinazione"];
            $nome_campo = $cella["nome_campo"];
            $cella_excel = $cella["cella_excel"];
            $foglio_excel = $cella["foglio_excel"];
            if ($foglio_excel != "-1")
            {
                if (strpos($cella["nome_campo"], "data") === 0)
                {
                    $valore = $objPHPExcel->setActiveSheetIndex($foglio_excel)->getCell($cella_excel)->getValue();
                    $valore = \PHPExcel_Style_NumberFormat::toFormattedString($valore, 'YYYY-MM-DD');
                }
                else if (strpos($cella["nome_campo"], "ora") === 0)
                {
                    $valore = $objPHPExcel->setActiveSheetIndex($foglio_excel)->getCell($cella_excel)->getValue();
                    $valore = \PHPExcel_Style_NumberFormat::toFormattedString($valore, 'hh:mm');
                }
                else
                {
                    $valore = $objPHPExcel->setActiveSheetIndex($foglio_excel)->getCell($cella_excel)->getCalculatedValue();
                }
                $array_insert[$nome_campo] = MySQL::SQLValue($valore);
            }
        }
        $ret = $db->InsertRow($tabella, $array_insert);
        if ($db->Error())
        {
            echo $db->Error();
            echo "<hr>";
            echo $db->GetLastSQL();
            die();
        }
        $objPHPExcel->disconnectWorksheets();
        unset($objPHPExcel);
    }
}
echo "<h1>FINE</h1>";

function createTableCheckList($array_campi, $drop_if_exists = false)
{
    $success = "";
    $sql_create = "";
    /* SELEZIONO LE TABELLE DA CREARE */
    $sql = "SELECT DISTINCT tabella_destinazione FROM ar_campi_excel_out";

    $db = new MySQL();

    $db->Query($sql);
    $array = $db->RecordsArray(MYSQL_ASSOC);

    foreach ($array as $tabella)
    {

        /* SELEZIONO I CAMPI PER LE TABELLE DA CREARE */
        $nome_tabella = $tabella["tabella_destinazione"];

        /* VERIFICA SE LA TABELLA ESISTE */
        $tabella_esistente = verificaTableCheckList($nome_tabella);

        /* SE LA TABELLA NON ESISTE O VOGLIO FORZARE IL DROP ENTRO QUI */
        if (($tabella_esistente == false) || ($drop_if_exists == true))
        {
            $sql_drop = "DROP TABLE IF EXISTS $nome_tabella;";
            $sql_create = "CREATE TABLE $nome_tabella(";
            foreach ($array_campi as $campo)
            {
                $sql_create .= $campo["nome_campo"] . " " . $campo["tipo_dato"] . ",";
            }
            $sql_create = substr($sql_create, 0, -1);
            $sql_create .= ");";
            if ($db->Query($sql_drop))
            {
                if ($db->Query($sql_create))
                    $success = "CREATA";
            }
        }
    }
    return $success;
}

function verificaTableCheckList($nome_tabella)
{
    $sql = "SHOW TABLES LIKE '$nome_tabella'";

    $db = new MySQL();
    $db->Query($sql);
    if ($db->RowCount() > 0)
    {
        return true;
    }
    else
    {
        return false;
    }
}
