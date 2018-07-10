// JavaScript Document
var id_progressivo = 0;
var id_istanza = 0;
// JavaScript Document
var oTableCorsi;

$(document).ready(function () {
    id_progressivo = getParameterByName('id_progressivo');

    //FORM VALIDATOR
    validator = $("#form-ins-mod").validate({
        invalidHandler: function (form, validator) {
            var errors = validator.numberOfInvalids();
            if (errors) {
                var message = errors == 1
                        ? 'Compilare il campo evidenziato'
                        : 'Compilare i ' + errors + ' campi evidenziati.';

                $("#mws-form-message").addClass("mws-form-message error")
                $("#mws-form-message").html(message).show();
            } else {
                $("#mws-form-message").hide();
            }
        }
    });

    var options = {
        //target:   '#output',   // target element(s) to be updated with server response 
        beforeSubmit: beforeSubmit, // pre-submit callback 
        success: afterSuccess, // post-submit callback 
        //uploadProgress: OnProgress, //upload progress callback 
        //resetForm: true        // reset the form after successful submit 
        dataType: 'json'
    };

    $('#form-ins-mod').submit(function () {
        $('#form-ins-mod').ajaxSubmit(options);
        return false;
    });


    $("#btn_salva").click(function () {
        if ($('#form-ins-mod').valid()) {
            $('#form-ins-mod').submit();
        } else {

            show_alert('Verificare i dati inseriti', 'Validazione input');
        }
    });


    if (id_progressivo !== '') {
        caricaDatiDomanda(id_progressivo);
    }

});


/**********************************************************
 CARICAMENTI
 **********************************************************/
function caricaDatiDomanda(id_progressivo) {
    $.getJSON("ajax.php?ajaxOP=get_cform_domanda_row", {'id': id_progressivo})
            .success(function (json) {
                id_istanza = json.id_istanza;
                $('#id_esito').select2("val", json.id_esito);
                $('#form-ins-mod').loadJSON(json);
                caricaFileAllegati(id_progressivo, id_istanza);
                caricaTabellaCorsi(id_istanza);
            })
            .error(function () {
                show_alert('Si sono verificati degli errori nel caricamento dei dati.', 'ATTENZIONE');
            })
}

function afterSuccess(data) {
    var int_esito = parseInt(data);

    if (int_esito > 0) {
        show_alert('Caricamento effettuato con successo.', 'Caricamento Effettuato');
        caricaDatiDomanda(id_progressivo);
    } else {
        switch (int_esito)
        {
            case - 1:
                show_alert('Attenzione, non è possibile caricare file di dimensione maggiore ai 5MB.', 'Caricamento Fallito');
                break;
            case - 2:
                show_alert('Attenzione, sono ammessi solo file di tipo Excel.', 'Caricamento Fallito');
                break;
            case - 3:
                show_alert('Attenzione, si sono verificati problemi durante il caricamento del file, riprovare.', 'Caricamento Fallito');
                break;
            default:
                show_alert('Attenzione, si sono verificati problemi durante salvataggio dei dati, verificare i valori immessi e riprovare.', 'Caricamento Fallito');
                break;
        }
    }
}

function beforeSubmit() {
    if ($('#FileInput').val() === "") {
        return true;
    }
    //check whether client browser fully supports all File API
    if (window.File && window.FileReader && window.FileList && window.Blob)
    {
        var fsize = $('#FileInput')[0].files[0].size; //get file size
        var ftype = $('#FileInput')[0].files[0].type; // get file type		
        switch (ftype)
        {
            case 'application/vnd.ms-excel':
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                break;
            default:
                show_alert('Attenzione, sono supportati solo file Excel.', 'Caricamento Fallito');
                return false;
                break;
        }
        //Allowed file size is less than 5 MB (1048576 = 1 mb)
        if (fsize > 5242880)
        {
            show_alert('Attenzione, il file deve essere inferione ai 5 MB Excel.', 'Caricamento Fallito');
            return false;
        }
    } else
    {
        //Error for older unsupported browsers that doesn't support HTML5 File API
        show_alert('ATTENZIONE! Il browser che stai utilizzando non supporta la funzionalità.', 'Caricamento Fallito');
    }
}


function caricaFileAllegati(id_progressivo, id_istanza) {
    var path_download = "";
    //getPathAllegati(id_progressivo, id_istanza);

    $.getJSON("ajax.php?ajaxOP=getRootAllegati&id_progressivo=" + id_progressivo + "&id_istanza=" + id_istanza, function (json) {
        console.log("JSON Data: " + json.path);
        path_download = json.path + "/";
    });

    $.getJSON("ajax.php?ajaxOP=getFileAllegati", {'id_progressivo': id_progressivo, 'id_istanza': id_istanza})
            .success(function (json) {
                if (json.length > 0) {
                    var tabella = "";
                    //
                    for (i = 0; i < json.length; i++) {
                        colonna_download = '<center><a href="' + path_download + json[i] + '" class="btn btn-small" target="_blank"><i class="icon-download-2"></i></a> ';
                        tabella += "<tr>";
                        tabella += "<td>" + json[i] + "</td>";
                        tabella += "<td>" + colonna_download + "</td>";
                        tabella += "</tr>";
                    }
                    $('#table_controlli tbody').html("");
                    $('#table_controlli tbody').append(tabella);

                    show_div("div_file_controlli", true);
                }
            })
            .error(function () {
                show_alert('Si sono verificati degli errori nel caricamento dei dati.', 'ATTENZIONE Carica File Controlli');
            });
}

function caricaTabellaCorsi(id_istanza)
{

    if (typeof oTableCorsi != 'undefined')
    {
        oTable.fnClearTable();
        oTable.fnAdjustColumnSizing();
    }
    oTableCorsi = $('#table_elenco_corsi').dataTable({
        "oLanguage": {"sUrl": "plugins/datatables/it_IT.txt"},
        "bLengthChange": false,
        "bFilter": false,
        "bAutoWidth": false,
        "bPaginate": false,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "ajax.php?ajaxOP=get_cform_domanda_corsi_list&id_istanza=" + id_istanza,
        "aoColumns": [
            {"mData": "id_istanza", "bSearchable": false, "bSortable": false, "bVisible": false},
            {"mData": "codice_percorso", "bSearchable": true, "bSortable": true, "bVisible": true},
            {"mData": "titolo_corso", "bSearchable": true, "bSortable": true, "bVisible": true},
            {"mData": "aula_svolgimento", "bSearchable": true, "bSortable": true, "bVisible": true},
            {"mData": "sede_svolgimento", "bSearchable": true, "bSortable": true, "bVisible": true},
            {"mData": "data_inizio_prevista", "bSearchable": true, "bSortable": true, "bVisible": true},
            {"mData": "data_fine_prevista", "bSearchable": true, "bSortable": true, "bVisible": true}
        ]
    });


}