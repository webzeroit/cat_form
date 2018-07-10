<?

class CheckList
{
    /* Indicano rispettivamente la tabella e la vista che contengono le checklist */

    const CKL_NON_CARICATA = 0;
    const CKL_CARICATA = 1;
    const ESITO_IN_ISTRUTTORIA = 0;
    const ESITO_POSITIVO = 1;
    const ESITO_NEGATIVO = 2;
    const ESITO_SOSPESA = 3;

    /* SALVA I DATI DEL FORM */

    public function saveFormCheckList($dati_checklist)
    {
        $db = new MySQL();

        $id_progressivo = trim($dati_checklist["id_progressivo"]);

        //DATI COMUNI		
        $array_where["id_progressivo"] = MySQL::SQLValue($id_progressivo);

        if ($dati_checklist["percorso_checklist"] != "ND")
        {
            $array_insert["id_stato_checklist"] = MySQL::SQLValue($dati_checklist["id_stato_checklist"]);
            $array_insert["percorso_checklist"] = MySQL::SQLValue($dati_checklist["percorso_checklist"]);
            $array_insert["data_caricamento"] = MySQL::SQLValue(date('Y-m-d H:i:s'), MySQL::SQLVALUE_DATETIME);
        }
        $array_insert["id_esito"] = MySQL::SQLValue($dati_checklist["id_esito"]);
        $array_insert["note"] = MySQL::SQLValue($dati_checklist["note"]);

        $ret = $db->AutoInsertUpdate("cform_domanda", $array_insert, $array_where);

        if ($ret)
            return 1;
        else
            return 0;
    }

    /* OTTIENE I DATI DEL FORM */

    public function getRowCheckList($id_progressivo, $toJson = false)
    {
        $sql = "SELECT * FROM v_cform_domanda WHERE id_progressivo=" . $id_progressivo;

        $db = new MySQL();
        if (!$db->Query($sql))
            echo $db->Kill();

        if (!$toJson)
            return ($db->RowCount() != 0) ? $db->RowArray(null, MYSQL_ASSOC) : null;
        else
            return $db->GetJSON();
    }

    /* OTTIENE TUTTI I DATI DELLE DOMANDE CON CHECKLIST CARICATA */

    public function getDomandeCheckList($id_stato_checklist, $toJson = false)
    {
        $sql = "SELECT * FROM v_cform_domanda WHERE id_stato_checklist=" . $id_stato_checklist;

        $db = new MySQL();
        if (!$db->Query($sql))
            echo $db->Kill();

        if (!$toJson)
            return ($db->RowCount() != 0) ? $db->RecordsArray(MYSQL_ASSOC) : null;
        else
            return ($db->RowCount() != 0) ? $db->RecordsArray(MYSQL_NUM) : null;
    }

    /* AGGIORNAMENTO STATO CHECKLIST DOPO LA LETTURA */

    public function set_stato_checklist($id_progressivo, $stato_checklist)
    {
        $db = new MySQL();

        $array_where["id_progressivo"] = $id_progressivo;
        $array_insert["id_stato_checklist"] = MySQL::SQLValue($stato_checklist);

        $ret = $db->AutoInsertUpdate("cform_domanda", $array_insert, $array_where);

        if ($ret)
            return 1;
        else
            return 0;
    }

    public function stats_stato_checklist()
    {

        $sql = "SELECT 
                COUNT(id_progressivo) as 'Presentate',
                COUNT(IF(id_stato_checklist = 1, 1, NULL)) as 'Caricate',
                COUNT(IF(id_stato_checklist = 0, 1, NULL)) as 'NonCaricate',
                COUNT(IF(id_esito = 1, 1, NULL)) as 'Ammissibile',
                COUNT(IF(id_esito = 2, 1, NULL)) as 'NonAmmissibile',
                COUNT(IF(id_esito = 3, 1, NULL)) as 'Sospese',
                COUNT(IF(id_esito = 4, 1, NULL)) as 'Integrare'
                FROM cform_domanda";

        $db = new MySQL();
        if (!$db->Query($sql))
            echo $db->Kill();

        return ($db->RowCount() != 0) ? $db->RowArray(null, MYSQL_ASSOC) : null;
    }


}
