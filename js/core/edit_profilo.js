// JavaScript Document
var validator;

$(document).ready(function() {

	settaValidator();		
	
	$("#btn_salva_password").click(function() {
		if($('#form-password').valid()){
			salvaPassword();	 
		}
	});							
	
});


function settaValidator(){	
	//FORM VALIDATOR

	
	validator = $("#form-password").validate({
		rules: {
			password: {
				required: true,
				minlength: 5
			},
			conferma_password: {
				required: true,
				minlength: 5,
				equalTo: "#password"
			}			
		},
		messages: {       
			password: {
				required: "Password obbligatoria",
				minlength: "La password deve contenere almeno 5 caratteri"
			},
			conferma_password: {
				required: "Conferma password obbligatoria",
				minlength: "La password deve contenere almeno 5 caratteri",
				equalTo: "La password e la sua conferma devono coincidere"
			}
		},
		invalidHandler: function(form, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {
				var message = errors == 1
				? 'Compilare il campo evidenziato'
				: 'Compilare i ' + errors + ' campi evidenziati.';
				
				$("form-password#mws-form-message").addClass("mws-form-message error")
				$("form-password#mws-form-message").html(message).show();
			} else {
				$("form-password#mws-form-message").hide();
			}
		}
	});		
	
	
}


function salvaPassword(){
	
	//Catturo i dati del form
	var formData = $("#form-password").serialize();
	
	show_confirm(
		'Procedere con l\' aggiornamento della basedati? <br/>L\'utente verrà disconnesso.',
		'Conferma Operazione',
		function () {
			//PROSEGUI
			$.ajax({
				type: 'POST',
				url: 'ajax.php?ajaxOP=setPassword',
				cache: false,
				data: formData,
				success: function(esito){
					if (esito == 0) {
						show_alert('Si sono verificati degli errori nel salvataggio dei dati.', 'Aggiornamento Fallito');
					}else {
						window.location.href = 'index.php?logout=true';  					
					}
				},
				error: function(){
					show_alert('Si sono verificati degli errori nel salvataggio dei dati.', 'Aggiornamento Fallito');
				},
				complete: function(){											
				}
			});						
								
		}
	);				
				
}
