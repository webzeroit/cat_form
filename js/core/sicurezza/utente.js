// JavaScript Document
var oTable;
var validator;

$(document).ready(function() {
	
	
	
	creaTabella();	
	settaValidator();
	chiudiForm();
	caricaTabellaProfili();
	$('#btn_aggiungi').attr("href","javascript:apriForm(0);");		
	
	$("#btn_chiudi").click(function() {	
		chiudiForm(); 
	});	

	$("#btn_salva").click(function() {
		if($('#form-ins-mod').valid()){
			salvaDatiForm();	 
		}
	});			
	
	/* Click event handler */
	$('#mws-table tbody tr').live('click', function () {
		if ( $(this).hasClass('row_selected') ) {
			$(this).removeClass('row_selected');			
			$('#btn_modifica').attr("href","javascript:chiudiForm()");
			$('#btn_elimina').attr("href", "#" );
		}
		else {
			oTable.$('tr.row_selected').removeClass('row_selected');				
			
			var aData = oTable.fnGetData(this); // get datarow
			if (aData != null){
				var id = aData[0];				
				$('#btn_modifica').attr("href", "javascript:apriForm("+ id + ");" );
				$('#btn_elimina').attr("href", "javascript:eliminaRiga("+ id + ");" );
			}
			$(this).addClass('row_selected');
		}
	} );			
	
});

$(window).bind('resize', function() {
	if(typeof(oTable) !== 'undefined') {
		oTable.fnAdjustColumnSizing();		
	}
});


function creaTabella(){	

	if (typeof oTable != 'undefined') 
	{
		oTable.fnClearTable();
		oTable.fnAdjustColumnSizing();
	}		
	
		
	oTable = $('#mws-table').dataTable( {
		"oLanguage": {"sUrl": "plugins/datatables/it_IT.txt"},			
		"iDisplayLength": 10,
		"bScrollCollapse": false,
		"bAutoWidth": false,
		"bFilter": true,		
		"sPaginationType": "full_numbers",		
		"bProcessing": false,	
		"sAjaxSource": "ajax.php?ajaxOP=getUtenteList",
		"aoColumns": [
				{ "bSearchable": false ,"bSortable": false, "bVisible": false },
				{ "bSearchable": true ,"bSortable": true, "bVisible": true },
				{ "bSearchable": true ,"bSortable": true, "bVisible": true },
				{ "bSearchable": true ,"bSortable": true, "bVisible": true }
		]		
	} );	
}

function caricaTabellaProfili(){		

	$.getJSON("ajax.php?ajaxOP=getListFunzioni")
		.success(function(json) {	
			
			var tabella = "";
			
			$.each( json, function( index, item){				
				tabella += "<tr>";
					tabella += "<td width='80%'>" + item[1] + "</td>";					
					tabella += "<td width='20%' class='checkbox-column'>";
						tabella += "<input id='cod_funzione' name='cod_funzione[]' value='" + item[0] + "' type='checkbox'></td>";
					tabella += "</td>";				
				tabella += "</tr>";
			});
			$('#table_utente_funzioni tbody').html("");
			$('#table_utente_funzioni tbody').append(tabella);
		
		})
		.error(function() {			
			show_alert('Si sono verificati degli errori nel caricamento dei dati.', 'ATTENZIONE');	
		})						
}



/*****************************************************************************
  Apre il From in Modifica o Inserimento a seconda che l'ID sia valorizzato
*****************************************************************************/

function apriForm(id_riga){	
	id = id_riga;
	pulisciForm();
	$('#panel_ins_mod').show();	
	if (id > 0){
		$('#titolo_form').html('<i class="icon-list"></i> Modifica');	
		
		$.getJSON("ajax.php?ajaxOP=getUtenteRow&id=" + id)
		.success(function(dati) {
			$('#form-ins-mod').loadJSON(dati);	
		})
		.error(function() {
			show_alert('Si sono verificati degli errori nel recupero dei dati.' , 'ATTENZIONE');	
		});	
		
		$.getJSON("ajax.php?ajaxOP=getUtenteFunzioni&id=" + id)
		.success(function(dati) {
			for(var indice in dati){
				 $('input:checkbox[value=' + dati[indice] + ']').attr('checked', true);	
			}
		})
		.error(function() {
			show_alert('Si sono verificati degli errori nel recupero dei dati.' , 'ATTENZIONE');	
		});				
	}
		
}

function chiudiForm(){
	pulisciForm();
	$('#panel_ins_mod').hide();	
}

function pulisciForm(){
	$('#titolo_form').html('<i class="icon-list"></i> Aggiungi');				
	$('#flag_obbligatorio').iButton("toggle", false);
	$("#mws-form-message").hide();	
	validator.resetForm();
	resettaForm("form-ins-mod");
}

function settaValidator(){	
	//FORM VALIDATOR
	validator = $("#form-ins-mod").validate({		
		invalidHandler: function(form, validator) {
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

}

/*****************************************************************************
  Elimina la riga selezionata
*****************************************************************************/
function eliminaRiga(id_riga){	
	id = id_riga;
	if(id != 0){
		//VISUALIZZA IL MESSAGGOI DI CANFERMA				
		show_confirm(
			'Vuoi eliminare la riga selezionata?',
			'Conferma Operazione',
			function () {
				//PROSEGUI											
				$.ajax({
					type: 'POST',
					url: 'ajax.php?ajaxOP=deleteUtente&id=' + id,
					cache: false,
					//data: {id: id},
					success: function(data){
						switch (data) {
							case "1":
								oTable.fnReloadAjax();
								show_alert('Cancellazione effettuata correttamente.', 'Operazione completata');							
								break;	
							case "0":
								show_alert('Errore nella cancellazione.', 'Cancellazione Fallita');
								break;	
							case "-1":
								show_alert('Impossibile cancellare, ci sono dei record collegati.', 'Cancellazione Fallita');
								break;	
						}
					},
					error: function(){
						show_alert('Si sono verificati degli errori nella cancellazione.', 'Cancellazione Fallita');
					}				
				});						
			}
		);					
	}			
}


function salvaDatiForm(){
	//Catturo l'ID per determinare se insert/update
	if (id=="") id=0;
	
	//Catturo i dati del form
	var formData = $("#form-ins-mod").serialize();

	$.ajax({
		type: 'POST',
		url: 'ajax.php?ajaxOP=saveUtente&id=' + id,
		cache: false,
		data: formData,
		success: function(esito){
			if (esito > 0) {
				id = esito;
				show_alert('Salvataggio effettuato con successo.', 'Caricamento Effettuato');
				oTable.fnReloadAjax();
				chiudiForm();
			} else if (esito == -1) {
				show_alert('ATTENZIONE! Esiste già un servizio con questo codice.', 'Caricamento Fallito');
			} else {
				show_alert('Nessun record salvato.', 'Caricamento Fallito');
			}
		},
		error: function(){
			show_alert('Si sono verificati degli errori nel salvataggio dei dati.', 'Caricamento Fallito');
		},
		complete: function(){

		
		}
	});		

}
