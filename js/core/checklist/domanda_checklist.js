// JavaScript Document
var oTable;
var id_utente = 0;
var id_ruolo = "";
var validator;
$(document).ready(function () {
    //VARIABILI DEL FORM
    id_utente = $('#id_utente').val();
    id_ruolo = $('#id_ruolo').val();


    /* PULSANTI SU TABELLA	*/
    $('#btn_export').attr("href", "genera_xls.php?op=xls_domande&id_utente=" + id_utente + "&id_ruolo=" + id_ruolo);

    caricaTabella();

});


/*TABELLA*/
function caricaTabella() {

    oTable = $('#tbl_elenco_domande').dataTable({
        "oLanguage": {"sUrl": "plugins/datatables/it_IT.txt"},
        "iDisplayLength": 10,
        "bFilter": true,
        "bAutoWidth": false,
        "sPaginationType": "full_numbers",
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "ajax.php?ajaxOP=get_cform_domanda_list&id_utente=" + id_utente + "&id_ruolo=" + id_ruolo,
        "aoColumns":
                [
                    {"mData": "id_progressivo", "bSearchable": true, "bSortable": true, "bVisible": true},
                    {"mData": "id_istanza", "bSearchable": true, "bSortable": true, "bVisible": true},
                    {"mData": "denominazione_ente", "bSearchable": true, "bSortable": true, "bVisible": true},
                    {"mData": "identificativo_fiscale_ente", "bSearchable": true, "bSortable": true, "bVisible": true},
                    {"mData": "stato_istanza", "bSearchable": true, "bSortable": false, "bVisible": false},
                    {"mData": "data_invio", "bSearchable": true, "bSortable": true, "bVisible": true},
                    {"mData": "ora_invio", "bSearchable": true, "bSortable": true, "bVisible": true},
                    {"mData": "id_utente", "bSearchable": true, "bSortable": false, "bVisible": false},
                    {"mData": "data_caricamento", "bSearchable": true, "bSortable": true, "bVisible": false},
                    {"mData": "percorso_checklist", "bSearchable": true, "bSortable": true, "bVisible": true},
                    {"mData": "id_stato_checklist", "bSearchable": true, "bSortable": true, "bVisible": true},
                    {"mData": "id_esito", "bSearchable": true, "bSortable": true, "bVisible": true}
                ],
        "aoColumnDefs": [
            {
                "mRender": function (data, type, full) {
                    return "<center><a href=\"javascript:ScaricaCheckList('" + full['id_progressivo'] + "','" + full['percorso_checklist'] + "');\" class=\"btn btn-small\"><i class=\"icon-download-2\"></i></a></center>";
                },
                "aTargets": [9]
            },
            {
                "mRender": function (data, type, full) {
                    var file = full['id_stato_checklist'];
                    if (file == 0)
                        col_file = "<span class='badge badge-important'>Non Caricata</span>";
                    else if (file == 1)
                        col_file = "<span class='badge badge-success'>Caricata</span>";
                    return 	"<center>" + col_file + "</center>";
                },
                "aTargets": [10]
            },
            {
                "mRender": function (data, type, full) {
                    var esito = full['id_esito'];
                    if (esito == 0)
                        col_esito = "<span class='badge badge-info'>Non Definito</span>";
                    else if (esito == 1)
                        col_esito = "<span class='badge badge-success'>Ammissibile</span>";
                    else if (esito == 2)
                        col_esito = "<span class='badge badge-important'>Non Ammissibile</span>";
                    else if (esito == 3)
                        col_esito = "<span class='badge badge-warning'>Valutazione Sospesa</span>";
                    else if (esito == 4)
                        col_esito = "<span class='badge badge-warning'>Da Integrare</span>";
                    return 	"<center>" + col_esito + "</center>";
                },
                "aTargets": [11]
            }
        ]
    });

    /* Click event handler */
    $('#tbl_elenco_domande tbody tr').live('click', function () {
        if ($(this).hasClass('row_selected')) {
            $(this).removeClass('row_selected');
            $('#btn_modifica').attr("href", "#");
        } else {
            oTable.$('tr.row_selected').removeClass('row_selected');

            var aData = oTable.fnGetData(this); // get datarow

            if (aData != null) {
                var id = aData["id_progressivo"];
                $('#btn_modifica').attr("href", "index.php?op=fn_domanda_dettaglio&id_progressivo=" + id);
            }
            $(this).addClass('row_selected');
            chiudiForm();
        }
    });
}

/*CHECKLIST*/
function ScaricaCheckList(id_progressivo, percorso_checklist) {
    var randNumber = Math.floor(Math.random() * 9999);
    if (percorso_checklist == 'null') {
        window.open("./ajax.php?ajaxOP=genera_checklist&id=" + id_progressivo + "&sess_id=" + randNumber);
    } else {

        window.open("./" + percorso_checklist + "?sess_id=" + randNumber);
    }

}