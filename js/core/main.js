/*
 * MWS Admin v2.1 - Core JS
 * This file is part of MWS Admin, an Admin template build for sale at ThemeForest.
 * All copyright to this file is hold by Mairel Theafila <maimairel@yahoo.com> a.k.a nagaemas on ThemeForest.
 * Last Updated:
 * December 08, 2012
 *
 */
 
(function($) {
	$(document).ready(function() {	


		$.ajaxSetup ({
			// Disable caching of AJAX responses
			cache: false
		});

		// Collapsible Panels
		$( '.mws-panel.mws-collapsible' ).each(function(i, element) {
			var p = $( element ),	
				header = p.find( '.mws-panel-header' );

			if( header && header.length) {
				var btn = $('<div class="mws-collapse-button mws-inset"><span></span></div>').appendTo(header);
				$('span', btn).on( 'click', function(e) {
					var p = $( this ).parents( '.mws-panel' );
					if( p.hasClass('mws-collapsed') ) {
						p.removeClass( 'mws-collapsed' )
							.children( '.mws-panel-inner-wrap' ).hide().slideDown( 250 );
					} else {
						p.children( '.mws-panel-inner-wrap' ).slideUp( 250, function() {
							p.addClass( 'mws-collapsed' );
						});
					}
					e.preventDefault();
				});
			}

			if( !p.children( '.mws-panel-inner-wrap' ).length ) {
				p.children( ':not(.mws-panel-header)' )
					.wrapAll( $('<div></div>').addClass( 'mws-panel-inner-wrap' ) );
			}
		})
	
		/* Side dropdown menu */
		$("div#mws-navigation ul li a, div#mws-navigation ul li span")
			.on('click', function(event) {
				if(!!$(this).next('ul').length) {
					$(this).next('ul').slideToggle('fast', function() {
						$(this).toggleClass('closed');
					});
					event.preventDefault();
				}
			});
		
		/* Responsive Layout Script */
		$("#mws-nav-collapse").on('click', function(e) {
			$( '#mws-navigation > ul' ).slideToggle( 'normal', function() {
				$(this).css('display', '').parent().toggleClass('toggled');
			});
			e.preventDefault();
		});
		
		/* Form Messages */
		$(".mws-form-message").on("click", function() {
			$(this).animate({ opacity:0 }, function() {
				$(this).slideUp("normal", function() {
					$(this).css("opacity", '');
				});
			});
		});

		// Checkable Tables
		$( 'table thead th.checkbox-column :checkbox' ).on('change', function() {
			var checked = $( this ).prop( 'checked' );
			$( this ).parents('table').children('tbody').each(function(i, tbody) {
				$(tbody).find('.checkbox-column').each(function(j, cb) {
					$( ':checkbox', $(cb) ).prop( "checked", checked ).trigger('change');
				});
			});
		});
		
		/* Chosen Select2 Box Plugin */
		if( $.fn.select2 ) {
			$("select.mws-select2").select2();
		}	

		/* Button */
		if( $.fn.button ) {
			$(".mws-ui-button").button();
		}

       	// jQuery-UI Datepicker
        if( $.fn.datepicker ) {
            $(".mws-datepicker").datepicker({
                showOtherMonths: true
            });

            $(".mws-datepicker-wk").datepicker({
                showOtherMonths: true,
                showWeek: true
            });

            $(".mws-datepicker-mm").datepicker({
                showOtherMonths: true,
                numberOfMonths: 3
            });

            $( "#mws-datepicker-from" ).datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 3,
                showOtherMonths: true,
                onSelect: function( selectedDate ) {
                    $( "#mws-datepicker-to" ).datepicker( "option", "minDate", selectedDate );
                }
            });
            $( "#mws-datepicker-to" ).datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 3,
                showOtherMonths: true,
                onSelect: function( selectedDate ) {
                    $( "#mws-datepicker-from" ).datepicker( "option", "maxDate", selectedDate );
                }
            });		
    	}
		
		// Scrittura in maiuscolo
		$(".uppercase").keyup(function() {
			$(this).val($(this).val().toUpperCase());
		});				
		
		$(".numerico").keydown(function(event) {
			// Allow only backspace and delete and tab
			if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9) {
				// let it happen, don't do anything
			}
			else {
				// Ensure that it is a number and stop the keypress
				if ((event.keyCode < 48 || event.keyCode > 57 ) && (event.keyCode < 96 || event.keyCode > 105 )) {
					event.preventDefault();	
				}	
			}
		});		
		
		// Validator Numero cellulare
		$.validator.addMethod("cellulare", function(value, element) {  
			return this.optional(element) || /^(39)((3[0-9][0-9]))(\d{7})$/.test(value);  
		}, "Numero di cellulare non valido.");	
		
		$.validator.addMethod("importo", function(value, element) {  
			return this.optional(element) ||  /^-?(?:\d+|\d{1,3}(?:.\d{3})+)(?:\,\d+)?$/.test(value);  
		}, "Inserire un importo valido.");	
		
		// Validator Numero cellulare
		$.validator.addMethod('time', function(value, element) {
			return value == '' || value.match(/^([01][0-9]|2[0-3]):[0-5][0-9]$/);
		}, "Orario non valido.");	
				
		$.validator.addMethod("cf_piva", function(value, element) {  
			return this.optional(element) || /^([A-Za-z]{6}[0-9lmnpqrstuvLMNPQRSTUV]{2}[abcdehlmprstABCDEHLMPRST]{1}[0-9lmnpqrstuvLMNPQRSTUV]{2}[A-Za-z]{1}[0-9lmnpqrstuvLMNPQRSTUV]{3}[A-Za-z]{1})$/.test(value) || /^([0-9]{11})$/.test(value);  
		}, "CUAA formalmente non valido.");			
	
		$.validator.addMethod("piva", function(value, element) {  
			return this.optional(element) || /^([0-9]{11})$/.test(value);  
		}, "CUAA formalmente non valido.");		
	
		$.validator.addMethod("cod_fis", function(value, element) {  
			return this.optional(element) || /^([A-Za-z]{6}[0-9lmnpqrstuvLMNPQRSTUV]{2}[abcdehlmprstABCDEHLMPRST]{1}[0-9lmnpqrstuvLMNPQRSTUV]{2}[A-Za-z]{1}[0-9lmnpqrstuvLMNPQRSTUV]{3}[A-Za-z]{1})$/.test(value);  
		}, "Codice Fiscale formalmente invalido.");		
	
		// Validator Numero cellulare
		$.validator.addMethod("barcode", function(value, element) {  
			return valid_barcode(value);
		}, "Barcode domanda non valido.");		
	
		// Bootstrap Dropdown Workaround
		$(document).on('touchstart.dropdown.data-api', '.dropdown-menu', function (e) { e.stopPropagation() });
		
		/* File Input Styling */
		$.fn.fileInput && $("input[type='file']").fileInput();

		// Placeholders
		$.fn.placeholder && $('[placeholder]').placeholder();

		// Tooltips
		$.fn.tooltip && $('[rel="tooltip"]').tooltip();

		// Popovers
		$.fn.popover && $('[rel="popover"]').popover();
		
		// DataTables Ajax reload
		$.fn.dataTableExt.oApi.fnReloadAjax = function ( oSettings, sNewSource, fnCallback, bStandingRedraw )
		{
			if ( typeof sNewSource != 'undefined' && sNewSource != null ) {
				oSettings.sAjaxSource = sNewSource;
			}
		 
			// Server-side processing should just call fnDraw
			if ( oSettings.oFeatures.bServerSide ) {
				this.fnDraw();
				return;
			}
		 
			this.oApi._fnProcessingDisplay( oSettings, true );
			var that = this;
			var iStart = oSettings._iDisplayStart;
			var aData = [];
		  
			this.oApi._fnServerParams( oSettings, aData );
			  
			oSettings.fnServerData.call( oSettings.oInstance, oSettings.sAjaxSource, aData, function(json) {
				/* Clear the old information from the table */
				that.oApi._fnClearTable( oSettings );
				  
				/* Got the data - add it to the table */
				var aData =  (oSettings.sAjaxDataProp !== "") ?
					that.oApi._fnGetObjectDataFn( oSettings.sAjaxDataProp )( json ) : json;
				  
				for ( var i=0 ; i<aData.length ; i++ )
				{
					that.oApi._fnAddData( oSettings, aData[i] );
				}
				  
				oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
				  
				if ( typeof bStandingRedraw != 'undefined' && bStandingRedraw === true )
				{
					oSettings._iDisplayStart = iStart;
					that.fnDraw( false );
				}
				else
				{
					that.fnDraw();
				}
				  
				that.oApi._fnProcessingDisplay( oSettings, false );
				  
				/* Callback user function - for event handlers etc */
				if ( typeof fnCallback == 'function' && fnCallback != null )
				{
					fnCallback( oSettings );
				}
			}, oSettings );
		};	

		//Multifilter
		$.fn.dataTableExt.oApi.fnMultiFilter = function( oSettings, oData ) {
		
				for ( var key in oData )
				{					
						if ( oData.hasOwnProperty(key) )
						{
								for ( var i=0, iLen=oSettings.aoColumns.length ; i<iLen ; i++ )
								{
										//if( oSettings.aoColumns[i].sName == key )
										if(i == key )
										{
												/* Add single column filter */
												oSettings.aoPreSearchCols[ i ].sSearch = oData[key];
												break;
										}
								}
						}
				}
				oSettings._iDisplayStart = 0;
				this.oApi._fnDraw( oSettings );
		};			
		
		
		$.fn.dataTableExt.oApi.fnReloadAjax = function ( oSettings, sNewSource, fnCallback, bStandingRedraw )
		{
			if ( typeof sNewSource != 'undefined' && sNewSource != null ) {
				oSettings.sAjaxSource = sNewSource;
			}
		 
			// Server-side processing should just call fnDraw
			if ( oSettings.oFeatures.bServerSide ) {
				this.fnDraw();
				return;
			}
		 
			this.oApi._fnProcessingDisplay( oSettings, true );
			var that = this;
			var iStart = oSettings._iDisplayStart;
			var aData = [];
		  
			this.oApi._fnServerParams( oSettings, aData );
			  
			oSettings.fnServerData.call( oSettings.oInstance, oSettings.sAjaxSource, aData, function(json) {
				/* Clear the old information from the table */
				that.oApi._fnClearTable( oSettings );
				  
				/* Got the data - add it to the table */
				var aData =  (oSettings.sAjaxDataProp !== "") ?
					that.oApi._fnGetObjectDataFn( oSettings.sAjaxDataProp )( json ) : json;
				  
				for ( var i=0 ; i<aData.length ; i++ )
				{
					that.oApi._fnAddData( oSettings, aData[i] );
				}
				  
				oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
				  
				if ( typeof bStandingRedraw != 'undefined' && bStandingRedraw === true )
				{
					oSettings._iDisplayStart = iStart;
					that.fnDraw( false );
				}
				else
				{
					that.fnDraw();
				}
				  
				that.oApi._fnProcessingDisplay( oSettings, false );
				  
				/* Callback user function - for event handlers etc */
				if ( typeof fnCallback == 'function' && fnCallback != null )
				{
					fnCallback( oSettings );
				}
			}, oSettings );
		};				
				
				
	});
}) (jQuery);



/*********************************************************************************************
	COMMON FUNCTION
*********************************************************************************************/

function getParameterByName(name)
{
  name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
  var regexS = "[\\?&]" + name + "=([^&#]*)";
  var regex = new RegExp(regexS);
  var results = regex.exec(window.location.search);
  if(results == null)
    return "";
  else
    return decodeURIComponent(results[1].replace(/\+/g, " "));
}


function resettaForm(FormID) {
	
	var ElementiDaSalvare = $(".noreset", '#'+FormID);
	
	// Salva gli elementi da non resettare
	ElementiDaSalvare.each(function() {				
		$(this).data('oldValue', $(this).val()); 
	});
	
   // Lista dei tipi di campi input da resettare
   $(':text, :password, :file', '#'+FormID).val(''); 
   $('textarea', '#'+FormID).val(''); 
   // Deseleziona checkbox, radio e select
   $(':input,select option', '#'+FormID).removeAttr('checked').removeAttr('selected');
   $(':input,select option', '#'+FormID).removeClass('error');
   // Seleziona il primo valore della select
   //$('select option:first', '#'+FormID).attr('selected',true);
	if( $.fn.select2 ) {
		$("#" + FormID + " select.mws-select2").select2("val", "");
	}	
	
	// Ripristino gli elementi da non resettare
	ElementiDaSalvare.each(function() {
		$(this).val($(this).data('oldValue')); 
	});	

}


function show_alert(messaggio, titolo){
	if (titolo == undefined) 
		titolo="ATTENZIONE";
	
	$(".mws-dialog-inner").html(messaggio);
	$( "#mws-jui-dialog" ).dialog({
		buttons: [
			{
				text: "Chiudi",
				click: function() { $(this).dialog("close"); }
			}
		],			
		modal: true,
		title: titolo,
		width: 460
	});
	
	
}


function show_confirm(messaggio, titolo, okFunction, cancelFunction) {
	if (titolo == undefined) 
		titolo="Conferma operazione";	
		
	$(".mws-dialog-inner").html(messaggio);
	$( "#mws-jui-dialog" ).dialog({
		modal: true,
		title: titolo,
		width: 460,
		minHeight: 75,
		buttons: {
			OK: function () {
				if (typeof (okFunction) == 'function') { setTimeout(okFunction, 50); }
				$(this).dialog("close");
			},
			Cancel: function () {
				if (typeof (cancelFunction) == 'function') { setTimeout(cancelFunction, 50); }
				$(this).dialog("close");
			}
		}
	});
}



function show_div(element_name, expand){
	var div = $('#' + element_name);
	div.show();
	
	if (expand == true)	
		div.removeClass('mws-collapsed').children( '.mws-panel-inner-wrap' ).hide().slideDown( 250 );	
	else
		div.children( '.mws-panel-inner-wrap' ).slideUp( 250, function() {
			div.addClass( 'mws-collapsed' );
		});
}

function hide_div(element_name){	
	var div = $('#' + element_name);
	div.children( '.mws-panel-inner-wrap' ).slideUp( 250, function() {
		//div.addClass( 'mws-collapsed' );
	});

	div.hide();	
}


function carica_comuni_provincia(select_provincia, select_comune, set_des_comune) {

	var sigla_provincia = $(select_provincia).val();
	if (sigla_provincia == "") {
		$(select_comune).select2("val", "");
		$(select_comune + ' option').each(function(){$(this).remove()});
		$(select_comune).append('<option></option>');		
	} else {
		var ajax_url = "ajax.php?ajaxOP=getComuneListJSON";	
		$.ajax({
				cache: true,
				type: "POST",
				url: ajax_url,	
				data: "istat_provincia="+sigla_provincia,		
				dataType: 'json',            
				success: function(data){
						$(select_comune).select2("val", "");
						$(select_comune + ' option').each(function(){$(this).remove()});
						$(select_comune).append('<option></option>');
						$.each(data, function(i, e){
							if (set_des_comune == e.id){
								$(select_comune).append('<option value="' + e.id + '">' + e.text + '</option>');
								$(select_comune).select2("val", e.id);
							}
							else										
								$(select_comune).append('<option value="' + e.id + '">' + e.text + '</option>');									
						});
					   
					   
				},
				error: function(e){
					show_alert('Si sono verificati degli errori nel recupero dei dati.' , 'ATTENZIONE');
				} 
		});      		
	}
}


function formatNumberFromDB(number){
	
	if (number == null){ 
		number=0;
		return "0,00";	
	}
	var numberStr = parseFloat(number).toFixed(2).toString();
	var numFormatDec = numberStr.slice(-2); /*decimal 00*/
	numberStr = numberStr.substring(0, numberStr.length-3); /*cut last 3 strings*/
	var numFormat = new Array;
	while (numberStr.length > 3) {
		numFormat.unshift(numberStr.slice(-3));
		numberStr = numberStr.substring(0, numberStr.length-3);
	}
	numFormat.unshift(numberStr);
	return numFormat.join('.')+','+numFormatDec; /*format 000.000.000,00 */
}

function aggiungiParametroURL(key, value)
{
	key = escape(key); value = escape(value);

	var kvp = document.location.search.substr(1).split('&');

	var i=kvp.length; var x; while(i--) 
	{
		x = kvp[i].split('=');

		if (x[0]==key)
		{
			x[1] = value;
			kvp[i] = x.join('=');
			break;
		}
	}

	if(i<0) {kvp[kvp.length] = [key,value].join('=');}

	//this will reload the page, it's likely better to store this until finished
	document.location.search = kvp.join('&'); 
}

// takes the form field value and returns true on valid number
function valid_barcode(value) {
	// accept only digits, dashes or spaces
    if (/[^0-9-\s]+/.test(value)) return false;

	// The Luhn Algorithm. It's so pretty.
    var nCheck = 0, nDigit = 0, bEven = false;
    value = value.replace(/\D/g, "");

    for (var n = value.length - 1; n >= 0; n--) {
        var cDigit = value.charAt(n),
            nDigit = parseInt(cDigit, 10);

        if (bEven) {
            if ((nDigit *= 2) > 9) nDigit -= 9;
        }

        nCheck += nDigit;
        bEven = !bEven;
    }

    return (nCheck % 10) == 0;
}