// JavaScript Document

$(document).ready(function () {
    settaElementi();
});



function settaElementi() {
    $.getJSON("ajax.php?ajaxOP=stats_stato_checklist")
        .success(function (dati) {
            if (dati != null) {
                $('#stat_tot_presentate').html(dati.Presentate);
                $('#stat_tot_checklist').html(dati.Caricate);
                $('#stat_tot_checklist_ko').html(dati.NonCaricate);
                $('#num_sospese').html(dati.Sospese);
                $('#num_non_ammissibili').html(dati.NonAmmissibile);
                $('#num_ammissibili').html(dati.Ammissibile);
                $('#num_integrare').html(dati.Integrare);
            }
        })
        .error(function () {
            show_alert('Si sono verificati degli errori nel recupero dei dati.', 'ATTENZIONE');
        });
}