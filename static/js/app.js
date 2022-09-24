var ExcelFormulas = {

	PVIF: function(rate, nper) {
		return Math.pow(1 + rate, nper);
	},

	FVIFA: function(rate, nper) {
		return rate == 0? nper: (this.PVIF(rate, nper) - 1) / rate;
	},	

	PMT: function(rate, nper, pv, fv, type) {
		if (!fv) fv = 0;
		if (!type) type = 0;

		if (rate == 0) return -(pv + fv)/nper;
		
		var pvif = Math.pow(1 + rate, nper);
		var pmt = rate / (pvif - 1) * -(pv * pvif + fv);

		if (type == 1) {
			pmt /= (1 + rate);
		};

		return pmt;
	},

	IPMT: function(pv, pmt, rate, per) {
		var tmp = Math.pow(1 + rate, per);
		return 0 - (pv * tmp * rate + pmt * (tmp - 1));
	},

	PPMT: function(rate, per, nper, pv, fv, type) {
		if (per < 1 || (per >= nper + 1)) return null;
		var pmt = this.PMT(rate, nper, pv, fv, type);
		var ipmt = this.IPMT(pv, pmt, rate, per - 1);
		return pmt - ipmt;
	},

	IPMT2: function(rate, per, nper, pv, fv, type) {
		if (per < 1 || (per >= nper + 1)) return null;
		var pmt = this.PMT(rate, nper, pv, fv, type);
		var ipmt = this.IPMT(pv, pmt, rate, per - 1);
		return ipmt;
	},
	
	DaysBetween: function(date1, date2) {
		var oneDay = 24*60*60*1000;
		return Math.round(Math.abs((date1.getTime() - date2.getTime())/oneDay));
	},
	
	// Change Date and Flow to date and value fields you use
	XNPV: function(rate, values) {
		var xnpv = 0.0;
		var firstDate = new Date(values[0].Date);
		for (var key in values) {
			var tmp = values[key];
			var value = tmp.Flow;
			var date = new Date(tmp.Date);
			xnpv += value / Math.pow(1 + rate, this.DaysBetween(firstDate, date)/365);
		};
		return xnpv;
	},

	XIRR: function(values, guess) {
		if (!guess) guess = 0.1;
		
		var x1 = 0.0;
		var x2 = guess;
		var f1 = this.XNPV(x1, values);
		var f2 = this.XNPV(x2, values);
		
		for (var i = 0; i < 100; i++) {
			if ((f1 * f2) < 0.0) break;
			if (Math.abs(f1) < Math.abs(f2)) {
				f1 = this.XNPV(x1 += 1.6 * (x1 - x2), values);
			}
			else {
				f2 = this.XNPV(x2 += 1.6 * (x2 - x1), values);
			}
		};
		
		if ((f1 * f2) > 0.0) return null;
		
		var f = this.XNPV(x1, values);
		if (f < 0.0) {
			var rtb = x1;
			var dx = x2 - x1;
		}
		else {
			var rtb = x2;
			var dx = x1 - x2;
		};
		
		for (var i = 0; i < 100; i++) {
			dx *= 0.5;
			var x_mid = rtb + dx;
			var f_mid = this.XNPV(x_mid, values);
			if (f_mid <= 0.0) rtb = x_mid;
			if ((Math.abs(f_mid) < 1.0e-6) || (Math.abs(dx) < 1.0e-6)) return x_mid;
		};
		
		return null;
	}

};

$(function(){
	
	$('.kt_datatable-0').DataTable({
		responsive: true,
		order: [[ 0, "desc" ]],
		lengthMenu: [
	        [10, 50, 100, -1],
	        [10, 50, 100, "Todos"]
	    ],
	    dom: "<'row'<'col-md-6'l><'col-md-6'p>r>t<'row'<'col-md-6'i><'col-md-6'p>r>",
		language: {
	        sProcessing:     "Procesando...",
	        sLengthMenu:     "Mostrar _MENU_ registros",
	        sZeroRecords:    "No se encontraron resultados",
	        sEmptyTable:     "Ningún registro disponible",
	        sInfo:           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
	        sInfoEmpty:      "Mostrando registros del 0 al 0 de un total de 0 registros",
	        sInfoFiltered:   "(filtrado de un total de _MAX_ registros)",
	        sInfoPostFix:    "",
	        sSearch:         "Buscar:",
	        sUrl:            "",
	        sInfoThousands:  ",",
	        sLoadingRecords: "Cargando...",
	        oPaginate: {
	            sFirst:    "Primero",
	            sLast:     "Último",
	            sNext:     "Siguiente",
	            sPrevious: "Anterior"
	        },
	        oAria: {
	            sSortAscending:  ": Activar para ordenar la columna de manera ascendente",
	            sSortDescending: ": Activar para ordenar la columna de manera descendente"
	        }
	    },
	});

	$('.kt_datatable-cuotas').DataTable({
		responsive: true,
		order: [[ 0, "asc" ]],
		lengthMenu: [
	        [-1],
	        ["Todos"]
	    ],
	    dom: "<'row'<'col-md-6'l><'col-md-6'p>r>t<'row'<'col-md-6'i><'col-md-6'p>r>",
		language: {
	        sProcessing:     "Procesando...",
	        sLengthMenu:     "Mostrar _MENU_ registros",
	        sZeroRecords:    "No se encontraron resultados",
	        sEmptyTable:     "Ningún registro disponible",
	        sInfo:           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
	        sInfoEmpty:      "Mostrando registros del 0 al 0 de un total de 0 registros",
	        sInfoFiltered:   "(filtrado de un total de _MAX_ registros)",
	        sInfoPostFix:    "",
	        sSearch:         "Buscar:",
	        sUrl:            "",
	        sInfoThousands:  ",",
	        sLoadingRecords: "Cargando...",
	        oPaginate: {
	            sFirst:    "Primero",
	            sLast:     "Último",
	            sNext:     "Siguiente",
	            sPrevious: "Anterior"
	        },
	        oAria: {
	            sSortAscending:  ": Activar para ordenar la columna de manera ascendente",
	            sSortDescending: ": Activar para ordenar la columna de manera descendente"
	        }
	    },
	});

	$('.kt_datatable-1').DataTable({
		responsive: true,
		order: [[ 1, "asc" ]],
		lengthMenu: [
	        [10, 50, 100, -1],
	        [10, 50, 100, "Todos"]
	    ],
	    dom: "<'row'<'col-md-6'l><'col-md-6'p>r>t<'row'<'col-md-6'i><'col-md-6'p>r>",
		language: {
	        sProcessing:     "Procesando...",
	        sLengthMenu:     "Mostrar _MENU_ registros",
	        sZeroRecords:    "No se encontraron resultados",
	        sEmptyTable:     "Ningún registro disponible",
	        sInfo:           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
	        sInfoEmpty:      "Mostrando registros del 0 al 0 de un total de 0 registros",
	        sInfoFiltered:   "(filtrado de un total de _MAX_ registros)",
	        sInfoPostFix:    "",
	        sSearch:         "Buscar:",
	        sUrl:            "",
	        sInfoThousands:  ",",
	        sLoadingRecords: "Cargando...",
	        oPaginate: {
	            sFirst:    "Primero",
	            sLast:     "Último",
	            sNext:     "Siguiente",
	            sPrevious: "Anterior"
	        },
	        oAria: {
	            sSortAscending:  ": Activar para ordenar la columna de manera ascendente",
	            sSortDescending: ": Activar para ordenar la columna de manera descendente"
	        }
	    },
	});

	$('.kt_datatable-2').DataTable({
		responsive: true,
		order: [[ 0, "asc" ]],
		lengthMenu: [
	        [10, 50, 100, -1],
	        [10, 50, 100, "Todos"]
	    ],
	    dom: "<'row'<'col-md-6'l><'col-md-6'p>r>t<'row'<'col-md-6'i><'col-md-6'p>r>",
		language: {
	        sProcessing:     "Procesando...",
	        sLengthMenu:     "Mostrar _MENU_ registros",
	        sZeroRecords:    "No se encontraron resultados",
	        sEmptyTable:     "Ningún registro disponible",
	        sInfo:           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
	        sInfoEmpty:      "Mostrando registros del 0 al 0 de un total de 0 registros",
	        sInfoFiltered:   "(filtrado de un total de _MAX_ registros)",
	        sInfoPostFix:    "",
	        sSearch:         "Buscar:",
	        sUrl:            "",
	        sInfoThousands:  ",",
	        sLoadingRecords: "Cargando...",
	        oPaginate: {
	            sFirst:    "Primero",
	            sLast:     "Último",
	            sNext:     "Siguiente",
	            sPrevious: "Anterior"
	        },
	        oAria: {
	            sSortAscending:  ": Activar para ordenar la columna de manera ascendente",
	            sSortDescending: ": Activar para ordenar la columna de manera descendente"
	        }
	    },
	});

	$('.kt_datatable-todos').DataTable({
		responsive: true,
		order: [[ 0, "desc" ]],
		lengthMenu: [
	        [-1],
	        ["Todos"]
	    ],
	    dom: "<'row'<'col-md-6'l><'col-md-6'p>r>t<'row'<'col-md-6'i><'col-md-6'p>r>",
		language: {
	        sProcessing:     "Procesando...",
	        sLengthMenu:     "Mostrar _MENU_ registros",
	        sZeroRecords:    "No se encontraron resultados",
	        sEmptyTable:     "Ningún registro disponible",
	        sInfo:           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
	        sInfoEmpty:      "Mostrando registros del 0 al 0 de un total de 0 registros",
	        sInfoFiltered:   "(filtrado de un total de _MAX_ registros)",
	        sInfoPostFix:    "",
	        sSearch:         "Buscar:",
	        sUrl:            "",
	        sInfoThousands:  ",",
	        sLoadingRecords: "Cargando...",
	        oPaginate: {
	            sFirst:    "Primero",
	            sLast:     "Último",
	            sNext:     "Siguiente",
	            sPrevious: "Anterior"
	        },
	        oAria: {
	            sSortAscending:  ": Activar para ordenar la columna de manera ascendente",
	            sSortDescending: ": Activar para ordenar la columna de manera descendente"
	        }
	    },
	});

	$('.kt_datatable-boveda').DataTable({
		responsive: true,
		order: [[ 0, "desc" ]],
		lengthMenu: [
	        [10, 50, 100, -1],
	        [10, 50, 100, "Todos"]
	    ],
		columns: [
    		null,
    		null,
		    null,
		    null,
		    null,
		    null,
		    null,
		    null,
		    { "searchable": false },
		    null
    	],
	    dom: "<'row'<'col-md-6'l><'col-md-6'p>r>t<'row'<'col-md-6'i><'col-md-6'p>r>",
		language: {
	        sProcessing:     "Procesando...",
	        sLengthMenu:     "Mostrar _MENU_ registros",
	        sZeroRecords:    "No se encontraron resultados",
	        sEmptyTable:     "Ningún registro disponible",
	        sInfo:           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
	        sInfoEmpty:      "Mostrando registros del 0 al 0 de un total de 0 registros",
	        sInfoFiltered:   "(filtrado de un total de _MAX_ registros)",
	        sInfoPostFix:    "",
	        sSearch:         "Buscar:",
	        sUrl:            "",
	        sInfoThousands:  ",",
	        sLoadingRecords: "Cargando...",
	        oPaginate: {
	            sFirst:    "Primero",
	            sLast:     "Último",
	            sNext:     "Siguiente",
	            sPrevious: "Anterior"
	        },
	        oAria: {
	            sSortAscending:  ": Activar para ordenar la columna de manera ascendente",
	            sSortDescending: ": Activar para ordenar la columna de manera descendente"
	        }
	    },
	});

	$('.kt_datatable-boveda-otro').DataTable({
		responsive: true,
		order: [[ 0, "desc" ]],
		lengthMenu: [
	        [10, 50, 100, -1],
	        [10, 50, 100, "Todos"]
	    ],
		columns: [
    		null,
    		null,
		    null,
		    null,
		    null,
		    null,
		    null,
		    null,
		    { "searchable": false },
		    null,
		    null
    	],
	    dom: "<'row'<'col-md-6'l><'col-md-6'p>r>t<'row'<'col-md-6'i><'col-md-6'p>r>",
		language: {
	        sProcessing:     "Procesando...",
	        sLengthMenu:     "Mostrar _MENU_ registros",
	        sZeroRecords:    "No se encontraron resultados",
	        sEmptyTable:     "Ningún registro disponible",
	        sInfo:           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
	        sInfoEmpty:      "Mostrando registros del 0 al 0 de un total de 0 registros",
	        sInfoFiltered:   "(filtrado de un total de _MAX_ registros)",
	        sInfoPostFix:    "",
	        sSearch:         "Buscar:",
	        sUrl:            "",
	        sInfoThousands:  ",",
	        sLoadingRecords: "Cargando...",
	        oPaginate: {
	            sFirst:    "Primero",
	            sLast:     "Último",
	            sNext:     "Siguiente",
	            sPrevious: "Anterior"
	        },
	        oAria: {
	            sSortAscending:  ": Activar para ordenar la columna de manera ascendente",
	            sSortDescending: ": Activar para ordenar la columna de manera descendente"
	        }
	    },
	});

	$('.kt_datatable-boveda-mini').DataTable({
		responsive: true,
		order: false,
		lengthMenu: [
	        [3, 10],
	        [3, 10]
	    ],
		columns: [
    		null,
    		null,
		    null,
		    null,
		    null,
		    null,
		    null,
		    null,
		    { "searchable": false },
		    null
    	],
	    dom: "<'row'<'col-md-6'l><'col-md-6'p>r>t",
		language: {
	        sProcessing:     "Procesando...",
	        sLengthMenu:     "Mostrar _MENU_ registros",
	        sZeroRecords:    "No se encontraron resultados",
	        sEmptyTable:     "Ningún registro disponible",
	        sInfo:           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
	        sInfoEmpty:      "Mostrando registros del 0 al 0 de un total de 0 registros",
	        sInfoFiltered:   "(filtrado de un total de _MAX_ registros)",
	        sInfoPostFix:    "",
	        sSearch:         "Buscar:",
	        sUrl:            "",
	        sInfoThousands:  ",",
	        sLoadingRecords: "Cargando...",
	        oPaginate: {
	            sFirst:    "Primero",
	            sLast:     "Último",
	            sNext:     "Sig",
	            sPrevious: "Ant"
	        },
	        oAria: {
	            sSortAscending:  ": Activar para ordenar la columna de manera ascendente",
	            sSortDescending: ": Activar para ordenar la columna de manera descendente"
	        }
	    },
	});

	$('#kt_datatable_search').on('keyup', function () {
	    $('.kt_datatable').DataTable().search( this.value ).draw();
		$('.kt_datatable-0').DataTable().search( this.value ).draw();
		$('.kt_datatable-1').DataTable().search( this.value ).draw();
		$('.kt_datatable-2').DataTable().search( this.value ).draw();
		$('.kt_datatable-boveda').DataTable().search( this.value ).draw();
		$('.kt_datatable-boveda-otro').DataTable().search( this.value ).draw();
	} );

	// Datepicker
	jQuery(function($){
		$.datepicker.regional['es'] = {
				closeText: 'Cerrar',
				prevText: '<Ant',
				nextText: 'Sig>',
				currentText: 'Hoy',
				monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
				'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
				monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
				'Jul','Ago','Sep','Oct','Nov','Dic'],
				dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
				dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','Sáb'],
				dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
				weekHeader: 'Sm',
				dateFormat: 'dd/mm/yy',
				firstDay: 0,
				isRTL: false,
				showMonthAfterYear: false,
				altFormat: "yy-mm-dd",
				yearSuffix: ''};
		$.datepicker.setDefaults($.datepicker.regional['es']);
	});
	$('.datepicker').datepicker({
        todayHighlight: true,
	});

	// Agregar partida
	$("#agregar-fila").on("click", function(event){
		var filas = parseInt($("#filas").val());
		filas++;
		var idFila = filas;
		$('#filas').val(idFila);

		$('#fila' + idFila).removeClass('hidden');
	});

	// Agregar partida
	$("#agregar-extra").on("click", function(event){
		var filas = parseInt($("#filas-extras").val());
		filas++;
		var idFila = filas;
		$('#filas-extras').val(idFila);

		$('#fila-extra' + idFila).removeClass('hidden');
	});

	$(document).on('click', '.btn-cancelar-requisicion', function() {
		var id = $(this).attr('data-id');
		$('#btn-cancelar').attr('href', './movimientos/compras/cancelar_requisicion/' + id);
	});
	$(document).on('click', '.btn-autorizar-requisicion', function() {
		var id = $(this).attr('data-id');
		$('#btn-autorizar').attr('href', './movimientos/compras/autorizar/' + id);
	});
	$(document).on('click', '.btn-cancelar-parte', function() {
		var id = $(this).attr('data-id');
		$('#btn-cancelar-parte').attr('href', './movimientos/compras/cancelar_parte/' + id);
	});
	
	// Una sola
	$(document).on('click', '.btn-recibir', function() {
		var id = $(this).attr('data-id');
		$('#btn-recibir-final').attr('href', './movimientos/compras/entregar/' + id);
	});
	$(document).on('click', '.btn-recibir2', function() {
		var id = $(this).attr('data-id');
		$('#btn-recibir2-final').attr('href', './movimientos/compras/recibir/' + id);
	});

	// Multiples
	$(document).on('click', '.btn-recibir-multiples', function() {
		var id = $(this).attr('data-id');
		$('#btn-recibir-multiples-final').attr('href', './movimientos/compras/entregar/' + id);
	});
	// Atrasadas
	$(document).on('click', '.btn-recibir-atrasadas', function() {
		var id = $(this).attr('data-id');
		$('#btn-recibir-atrasadas-final').attr('href', './movimientos/compras/entregar/' + id);
	});

	$(document).on('click', '#btn-recibir-multiples', function() {
		$('#form-recibir-multiples').submit();
	});
	$(document).on('click', '#btn-recibir2-multiples', function() {
		$('#form-recibir2-multiples').submit();
	});
	$(document).on('click', '#btn-recibir-atrasadas', function() {
		$('#form-recibir-atrasadas').submit();
	});

	$(document).on('click', '.btn-finalizar', function() {
		var id = $(this).attr('data-id');
		$('#btn-finalizar').attr('href', './movimientos/solicitudes/finalizar/' + id);
	});

	$(".requisicion-producto").keyup(function(){
		// Se obtiene el ID del campo de precio actual
		var id = $(this).attr('id');
		var f = id.replace(/\D/g, '');
		f = parseInt(f, 10);

		// Checar si este campo ya tiene un valor
		if ($(this).val() != '') {
			$('#tipo' + f).attr('required', 'required');
			$('#cantidad' + f).attr('required', 'required');
			$('#um' + f).attr('required', 'required');
			$('#justificacion' + f).attr('required', 'required');
		} else {
			$('#tipo' + f).removeAttr('required');
			$('#cantidad' + f).removeAttr('required');
			$('#um' + f).removeAttr('required');
			$('#justificacion' + f).removeAttr('required');
		}
	});

	// Venta Destinatario
	$("#venta-destinatario").on("change", function(event){
        var o = $(this).val();

        // Prospecto
        if (o == 1) {
        	$('#venta-propietario').slideUp();
        	$('.venta-prospecto').slideDown();

			$('#propietario').removeAttr('required');
			$('#nombre_prospecto').attr('required', 'required');
			$('#tel_prospecto').attr('required', 'required');
			$('#correo_prospecto').attr('required', 'required');
        } else {
        	$('#venta-propietario').slideDown();
        	$('.venta-prospecto').slideUp();

        	$('#propietario').attr('required', 'required');
			$('#nombre_prospecto').removeAttr('required');
			$('#tel_prospecto').removeAttr('required');
			$('#correo_prospecto').removeAttr('required');
        }
	});

	// Venta Destinatario
	$("#cc-cuenta").on("change", function(event){
        var o = $(this).val();

        // SI
        if (o == 1) {
        	$('#cc-adeudo').val('');
        	$('#cc-adeudo').removeClass('form-disabled');
			$('#cc-adeudo').removeAttr('readonly');
        } else {
			$('#cc-adeudo').val('0.00');
			$('#cc-adeudo').addClass('form-disabled');
			$('#cc-adeudo').attr('readonly', 'readonly');
        }
	});

	$("#solicitud-corriente").on("change", function(event){
        var o = $(this).val();

        // SI
        if (o == 1) {
        	$('#solicitud-responsable').slideDown();
        	$('#solicitud-cobranza-responsable').hide();

			$('#solicitud-id_responsable').attr('required', 'required');
			$('#solicitud-id_cobranza_responsable').removeAttr('required');
        } else {
			$('#solicitud-responsable').hide();
			$('#solicitud-cobranza-responsable').slideDown();

			$('#solicitud-id_cobranza_responsable').attr('required', 'required');
			$('#solicitud-id_responsable').removeAttr('required');
        }
	});

	$('#cc-adeudo').on('keyup', function () {
	    if ($(this).val() == '0' && $('#cc-cuenta').val() == 1) {
	    	$('#cc-archivo').removeAttr('disabled');
	    } else {
	    	$('#cc-archivo').attr('disabled', 'disabled');
	    }
	});

	$(document).on('click', '.checkbox-procesar', function() {
		var ids = new Array();
		$("input:checkbox[name=checkboxIds]:checked").each(function(){
		    ids.push($(this).val());
		});

		$('#ids').val(ids);

		if($('.checkbox-procesar[type=checkbox]:checked').length) {
			$('#btn-cargar').show();
		} else {
			$('#btn-cargar').hide();
		}
	});

	$(document).on('click', '.checkbox-recibir', function() {
		var ids = new Array();
		$("input:checkbox[name=checkboxIds]:checked").each(function(){
		    ids.push($(this).val());
		});

		$('#ids-recibir').val(ids);

		if($('.checkbox-recibir[type=checkbox]:checked').length) {
			$('#btn-recibir-multiples-mini').show();
		} else {
			$('#btn-recibir-multiples-mini').hide();
		}
	});

	$(document).on('click', '.checkbox-recibir-atrasadas', function() {
		var ids = new Array();
		$("input:checkbox[name=checkboxIds]:checked").each(function(){
		    ids.push($(this).val());
		});

		$('#ids-recibir-atrasadas').val(ids);

		if($('.checkbox-recibir-atrasadas[type=checkbox]:checked').length) {
			$('#btn-recibir-atrasadas-mini').show();
		} else {
			$('#btn-recibir-atrasadas-mini').hide();
		}
	});

	$(document).on('click', '.checkbox-recibir2', function() {
		var ids = new Array();
		$("input:checkbox[name=checkboxIds]:checked").each(function(){
		    ids.push($(this).val());
		});

		$('#ids-recibir2').val(ids);

		if($('.checkbox-recibir2[type=checkbox]:checked').length) {
			$('#btn-recibir2-multiples-mini').show();
		} else {
			$('#btn-recibir2-multiples-mini').hide();
		}
	});

	$(document).on('click', '.cc-adjuntar', function() {
		var filas = parseInt($("#cc-num").val());
		filas++;
		var idFila = filas;
		$('#cc-num').val(idFila);
		$('#cc-'+idFila).removeClass('hidden');
	});

	$("#cotizacion-propietario").on("change", function(event){
        var idPropietario = $(this).val();

    	$.ajax({
	        url: window.STASIS + "/movimientos/cotizaciones/info_propietario",
	        type: "post",
	        data: {idPropietario: idPropietario},
	        success: function(output){
	            $('#log').html(output);
	        }
	    });
	});

	$(".campo-precio").keyup(function(){
		// Se obtiene el ID del campo de precio actual
		var id = $(this).attr('id');
		var idNumero = id.replace(/\D/g, '');
		idNumero = parseInt(idNumero, 10);

		// Se obtienen los valores actuales
		var cantidad = parseFloat($('#cantidad' + idNumero).val());
		var precio = parseFloat($('#precio' + idNumero).val()) || 0;
		
		// Se saca el total
		var total = cantidad * precio;
		if (!isNaN(total)) {
			$('#total' + idNumero).val(total.toFixed(2));
		}

		// Se checa diferencia del precio escondido
		var precioEscondido = parseFloat($('#precio-escondido' + idNumero).val());
		var tooltipActual = "#" + $(this).attr('aria-describedby');
		
		if (precio == precioEscondido) {
			$(this).removeClass('input-error').removeClass('input-ok');
			$(tooltipActual + ' div').html('Precio Igual');
			$('#'+id).attr('title', 'Precio Igual');
		}
		if (precio < precioEscondido) {
			$(this).removeClass('input-ok').addClass('input-error');
			var perdida = parseFloat(precioEscondido - precio).toFixed(2);
			var perdidaPorcentaje = (parseFloat(precio) / parseFloat(precioEscondido) * 100).toFixed(2);
			$(tooltipActual + ' div').html('Pérdida: -$' + perdida + '<br />Porcentaje: ' + perdidaPorcentaje + '%');
			$('#'+id).attr('title', 'Pérdida: -$' + perdida + '<br />Porcentaje: ' + perdidaPorcentaje + '%');
		}
		if (precio > precioEscondido) {
			$(this).removeClass('input-error').addClass('input-ok');
			var ganancia = parseFloat(precio - precioEscondido).toFixed(2);
			var gananciaPorcentaje = (parseFloat(precio) * parseFloat(precioEscondido) / 100).toFixed(2);
			$(tooltipActual + ' div').html('Ganancia Extra: $' + ganancia + '<br />Porcentaje: ' + gananciaPorcentaje + '%');
			$('#'+id).attr('title', 'Ganancia Extra: $' + ganancia + '<br />Porcentaje: ' + gananciaPorcentaje + '%');
		}

		// Se calculan totales de todos los precios
		for (var i=1; i<=100; i++) {
			var cantidad = parseFloat($('#cantidad' + i).val());
			var precio = parseFloat($('#precio' + i).val()) || 0;
			
			var total = cantidad * precio;
			if (!isNaN(total)) {
				$('#total' + i).val(total.toFixed(2));
			}
		}

		var totales = new Array();
		for (var i=1; i<=100; i++) {
			var total = parseFloat($('#total' + i).val()) || 0;
			totales.push(parseFloat(total || 0));
		}

		var totalFinal = 0.000;
		$.each(totales,function(){totalFinal+=parseFloat(this) || 0;});
		$('#subtotal').val(totalFinal.toFixed(2));

		var subtotal = parseFloat($('#subtotal').val());
		var impuesto = parseFloat(subtotal * .08);
		$('#impuesto').val(impuesto.toFixed(2));

		var totalDeTotales = subtotal + impuesto;
		$('#total').val(totalDeTotales.toFixed(2));
	});

	$(".campo-precio-cotizacion").keyup(function(){
		// Se obtiene el ID del campo de precio actual
		var id = $(this).attr('id');
		var idNumero = id.replace(/\D/g, '');
		idNumero = parseInt(idNumero, 10);

		// Se obtienen los valores actuales
		var cantidad = parseFloat($('#cantidad' + idNumero).val());
		var precio = parseFloat($('#precio' + idNumero).val()) || 0;
		
		// Se saca el total
		var total = cantidad * precio;
		if (!isNaN(total)) {
			$('#total' + idNumero).val(total.toFixed(2));
		}

		// Se checa diferencia del precio escondido
		var precioEscondido = parseFloat($('#precio-escondido' + idNumero).val());
		var tooltipActual = "#" + $(this).attr('aria-describedby');
		
		if (precio == precioEscondido) {
			$(this).removeClass('input-error').removeClass('input-ok');
			$(tooltipActual + ' div').html('Precio Igual');
			$('#'+id).attr('title', 'Precio Igual');
		}
		if (precio < precioEscondido) {
			$(this).removeClass('input-ok').addClass('input-error');
			var perdida = parseFloat(precioEscondido - precio).toFixed(2);
			var perdidaPorcentaje = (parseFloat(precio) / parseFloat(precioEscondido) * 100).toFixed(2);
			$(tooltipActual + ' div').html('Pérdida: -$' + perdida + '<br />Porcentaje: ' + perdidaPorcentaje + '%');
			$('#'+id).attr('title', 'Pérdida: -$' + perdida + '<br />Porcentaje: ' + perdidaPorcentaje + '%');
		}
		if (precio > precioEscondido) {
			$(this).removeClass('input-error').addClass('input-ok');
			var ganancia = parseFloat(precio - precioEscondido).toFixed(2);
			var gananciaPorcentaje = (parseFloat(precio) * parseFloat(precioEscondido) / 100).toFixed(2);
			$(tooltipActual + ' div').html('Ganancia Extra: $' + ganancia + '<br />Porcentaje: ' + gananciaPorcentaje + '%');
			$('#'+id).attr('title', 'Ganancia Extra: $' + ganancia + '<br />Porcentaje: ' + gananciaPorcentaje + '%');
		}

		// Se calculan totales de todos los precios
		for (var i=1; i<=100; i++) {
			var cantidad = parseFloat($('#cantidad' + i).val());
			var precio = parseFloat($('#precio' + i).val()) || 0;
			
			var total = cantidad * precio;
			if (!isNaN(total)) {
				$('#total' + i).val(total.toFixed(2));
			}
		}

		var totales = new Array();
		for (var i=1; i<=100; i++) {
			var total = parseFloat($('#total' + i).val()) || 0;
			totales.push(parseFloat(total || 0));
		}

		var totalFinal = 0.000;
		$.each(totales,function(){totalFinal+=parseFloat(this) || 0;});
		$('#subtotal').val(totalFinal.toFixed(2));

		var porImpuesto = parseFloat($('#porImpuesto').val()/100 || 0);

		var subtotal = parseFloat($('#subtotal').val());
		var impuesto = parseFloat(subtotal * porImpuesto);
		$('#impuesto').val(impuesto.toFixed(2));

		var totalDeTotales = subtotal + impuesto;
		$('#total').val(totalDeTotales.toFixed(2));
	});

	$(".campo-precio-cotizacion").on("change", function(event){
		// Se calculan totales de todos los precios
		for (var i=1; i<=1; i++) {
			var cantidad = parseFloat($('#cantidad' + i).val());
			var precio = parseFloat($('#precio' + i).val()) || 0;
			
			var total = cantidad * precio;
			if (!isNaN(total)) {
				$('#total' + i).val(total.toFixed(2));
			}
		}

		var totales = new Array();
		for (var i=1; i<=100; i++) {
			var total = parseFloat($('#total' + i).val()) || 0;
			totales.push(parseFloat(total || 0));
		}

		var totalFinal = 0.000;
		$.each(totales,function(){totalFinal+=parseFloat(this) || 0;});
		$('#subtotal').val(totalFinal.toFixed(2));

		var porImpuesto = parseFloat($('#porImpuesto').val()/100 || 0);

		var subtotal = parseFloat($('#subtotal').val());
		var impuesto = parseFloat(subtotal * porImpuesto);
		$('#impuesto').val(impuesto.toFixed(2));

		var totalDeTotales = subtotal + impuesto;
		$('#total').val(totalDeTotales.toFixed(2));
	});

	$("#porImpuesto").on("change", function(event){
		// Se calculan totales de todos los precios
		for (var i=1; i<=100; i++) {
			var cantidad = parseFloat($('#cantidad' + i).val());
			var precio = parseFloat($('#precio' + i).val()) || 0;
			
			var total = cantidad * precio;
			if (!isNaN(total)) {
				$('#total' + i).val(total.toFixed(2));
			}
		}

		var totales = new Array();
		for (var i=1; i<=100; i++) {
			var total = parseFloat($('#total' + i).val()) || 0;
			totales.push(parseFloat(total || 0));
		}

		var totalFinal = 0.000;
		$.each(totales,function(){totalFinal+=parseFloat(this) || 0;});
		$('#subtotal').val(totalFinal.toFixed(2));

		var porImpuesto = parseFloat($('#porImpuesto').val());
		var subtotal = parseFloat($('#subtotal').val());
		var impuesto = parseFloat(subtotal * porImpuesto);
		$('#impuesto').val(impuesto.toFixed(2));

		var totalDeTotales = subtotal + impuesto;
		$('#total').val(totalDeTotales.toFixed(2));
	});

	$(document).on('click', '.btn-notificacion', function() {
		var id = $(this).attr('data-id');
		var correo = $(this).attr('data-correo');
		var celular = $(this).attr('data-celular');

		$('#propietario-correo').html(correo);
		$('#propietario-celular').html(celular);

		$('#btn-enviar-notificacion').attr('href', './movimientos/cotizaciones/enviar/' + id);
	});

	// Cambio de tipo de pago en cuentas por cobrar
    $(document).on("change", '.cpc-tipopago', function(event){
		var tipoPago = $(this).val();

		if (tipoPago == 1) {
			$('.tipopago-pago').slideDown();
			$('.tipopago-abono').slideUp();
		} else if (tipoPago == 2) {
			$('.tipopago-pago').slideUp();
			$('.tipopago-abono').slideDown();
		}
    });

    // Abonos en estado de cuenta
    var totalAbonado = 0;
	for (var i=1; i<=5; i++) {
		var abono = parseFloat($('#importe_pagado' + i).val()) || 0;
		totalAbonado += abono;
	}
	$('#total_abonado').val(totalAbonado.toFixed(2));

    $(".abonos-importe").keyup(function(){
    	var totalAbonado = 0;
		
		for (var i=1; i<=5; i++) {
			var abono = parseFloat($('#importe_pagado' + i).val()) || 0;

			totalAbonado += abono;
		}

		$('#total_abonado').val(totalAbonado.toFixed(2));
	});

	$(document).on("change", '.cpc-metodopago', function(event){
		var metodoPago = $(this).val();

		if (metodoPago == 1) {
			$('.campo-banco').slideUp();
			$('.campo-aut').slideUp();
			$('.campo-cheque').slideUp();
		} else if (metodoPago == 2) {
			$('.campo-banco').slideDown();
			$('.campo-aut').slideUp();
			$('.campo-cheque').slideDown();
		} else if (metodoPago == 3) {
			$('.campo-banco').slideDown();
			$('.campo-aut').slideDown();
			$('.campo-cheque').slideUp();
		} else if (metodoPago == 4) {
			$('.campo-banco').slideDown();
			$('.campo-aut').slideDown();
			$('.campo-cheque').slideUp();
		} else if (metodoPago == 5) {
			$('.campo-banco').slideUp();
			$('.campo-aut').slideDown();
			$('.campo-cheque').slideUp();
		}
    });

    $(".cotizacion-servicio").on("change", function(event){
        var idConcepto = $(this).val();

    	$.ajax({
	        url: window.STASIS + "/catalogos/conceptos/info_concepto",
	        type: "post",
	        data: {idConcepto: idConcepto},
	        success: function(output){
	            $('#log').html(output);
	        }
	    });
	});

	$('#mask-cc').mask('0000 0000 0000 0000', {
        placeholder: "0000 0000 0000 0000"
    });

    $('.mask-telefono').mask('(000) 000-0000', {
        placeholder: "(999) 999-9999"
    });

    $('.money2').mask("#,##0", {reverse: true});

	$('.presupuesto-importe').on('keyup', function () {
		var tc = parseFloat($('#tc').val());

		var ene = parseFloat($('#ene').val().replace(/,/g, '')) || 0;
		$('#ene-usd').val((ene/tc).toLocaleString('en-US', {maximumFractionDigits:2}));
		var feb = parseFloat($('#feb').val().replace(/,/g, '')) || 0;
		$('#feb-usd').val((feb/tc).toLocaleString('en-US', {maximumFractionDigits:2}));
		var mar = parseFloat($('#mar').val().replace(/,/g, '')) || 0;
		$('#mar-usd').val((mar/tc).toLocaleString('en-US', {maximumFractionDigits:2}));
		var abr = parseFloat($('#abr').val().replace(/,/g, '')) || 0;
		$('#abr-usd').val((abr/tc).toLocaleString('en-US', {maximumFractionDigits:2}));
		var may = parseFloat($('#may').val().replace(/,/g, '')) || 0;
		$('#may-usd').val((may/tc).toLocaleString('en-US', {maximumFractionDigits:2}));
		var jun = parseFloat($('#jun').val().replace(/,/g, '')) || 0;
		$('#jun-usd').val((jun/tc).toLocaleString('en-US', {maximumFractionDigits:2}));
		var jul = parseFloat($('#jul').val().replace(/,/g, '')) || 0;
		$('#jul-usd').val((jul/tc).toLocaleString('en-US', {maximumFractionDigits:2}));
		var ago = parseFloat($('#ago').val().replace(/,/g, '')) || 0;
		$('#ago-usd').val((ago/tc).toLocaleString('en-US', {maximumFractionDigits:2}));
		var sep = parseFloat($('#sep').val().replace(/,/g, '')) || 0;
		$('#sep-usd').val((sep/tc).toLocaleString('en-US', {maximumFractionDigits:2}));
		var oct = parseFloat($('#oct').val().replace(/,/g, '')) || 0;
		$('#oct-usd').val((oct/tc).toLocaleString('en-US', {maximumFractionDigits:2}));
		var nov = parseFloat($('#nov').val().replace(/,/g, '')) || 0;
		$('#nov-usd').val((nov/tc).toLocaleString('en-US', {maximumFractionDigits:2}));
		var dic = parseFloat($('#dic').val().replace(/,/g, '')) || 0;
		$('#dic-usd').val((dic/tc).toLocaleString('en-US', {maximumFractionDigits:2}));

		var totalMxn = ene+feb+mar+abr+may+jun+jul+ago+sep+oct+nov+dic;
		var totalUsd = (ene+feb+mar+abr+may+jun+jul+ago+sep+oct+nov+dic)/tc;
		$('#presupuesto-mxn').val(totalMxn.toLocaleString('en-US', {maximumFractionDigits:2}));
		$('#presupuesto-usd').val(totalUsd.toLocaleString('en-US', {maximumFractionDigits:2}));
    });

    $("#norequiero").on("click", function(event){
		$('#requieres').slideUp();
		$('#infopago').slideDown();
	});

	$("#sirequiero").on("click", function(event){
		$('#requieres').slideUp();
		$('#tipo-requieres').slideDown();
	});

	$("#personafisica").on("click", function(event){
		$('#tipo-requieres').slideUp();
		$('#datosnacional').slideDown();
	});

	$("#extranjero").on("click", function(event){
		$('#tipo-requieres').slideUp();
		$('#infopago').slideDown();
	});

	$("#fact-continuar").on("click", function(event){
		var razon_social = $('#razon_social').val();
		var rfc = $('#rfc').val();
		var uso_cfdi = $('#uso_cfdi').val();
		var regimen = $('#regimen').val();
		var nombre_calle = $('#nombre_calle').val();
		var num_exterior = $('#num_exterior').val();
		var num_interior = $('#num_interior').val();
		var colonia = $('#colonia').val();
		var cp = $('#cp').val();
		var ciudad = $('#ciudad').val();
		var estado = $('#estado').val();
		var pais = $('#pais').val();
		var idCotizacion = $('#idCotizacion').val();

		var factPropietario = $('#fact-propietario').val();
		var factCorreo = $('#fact-correo').val();
		var factLote = $('#fact-lote').val();
		var factConcepto = $('#fact-concepto').val();
		var factImporte = $('#fact-importe').val();
		var factObservaciones = $('#fact-observaciones').val();
		var factMoneda = $('#fact-moneda').val();
		var factSubtotal = $('#fact-subtotal').val();
		var factImpuesto = $('#fact-impuesto').val();
		var factTotal = $('#fact-total').val();
		var factPorimpuesto = $('#fact-porimpuesto').val();
		
		var factUm = $('#fact-um').val();
		var factUmAbreviacion = $('#fact-umAbreviacion').val();
		var factClaveProdserv = $('#fact-claveProdserv').val();
		var factIdConcepto = $('#fact-idConcepto').val();

		$.ajax({
	        url: window.STASIS + "/movimientos/cotizaciones/nuevo_cliente",
	        type: "post",
	        data: {
				razon_social: razon_social,
				rfc: rfc,
				uso_cfdi: uso_cfdi,
				regimen: regimen,
				nombre_calle: nombre_calle,
				num_exterior: num_exterior,
				num_interior: num_interior,
				colonia: colonia,
				cp: cp,
				ciudad: ciudad,
				estado: estado,
				pais: pais,
				
				idCotizacion: idCotizacion,

				factPropietario: factPropietario,
				factCorreo: factCorreo,
				factLote: factLote,
				factConcepto: factConcepto,
				factImporte: factImporte,
				factObservaciones: factObservaciones,
				factMoneda: factMoneda,
				factSubtotal: factSubtotal,
				factImpuesto: factImpuesto,
				factTotal: factTotal,
				factPorimpuesto: factPorimpuesto,

				factUm: factUm,
				factUmAbreviacion: factUmAbreviacion,
				factClaveProdserv: factClaveProdserv,
				factIdConcepto: factIdConcepto,
	        },
	        success: function(output){
	            $('#log').html(output);
	        }
	    });

		$('#datosnacional').slideUp();
		$('#infopago').slideDown();
	});

	// Venta Destinatario
	$("#requi-cc").on("change", function(event){
        var director = $(this).find(':selected').attr('data-director');
        var comprador = $(this).find(':selected').attr('data-comprador');

        $('#director').val(director);
        $('#comprador').val(comprador);
	});

	$(".agregar-fila-cotizacion").on("click", function(event){
		// Numero de juego
		var idActual = $(this).attr('id');
		var nJuego = idActual.replace( /^\D+/g, '');

		// Filas en juego
		var filasJuego = parseInt($('#filas-juego-' + nJuego).val());

		if (filasJuego <= 7) {
			filasJuego++;

			$('#noPartida' + filasJuego + nJuego).show();
			$('#descripcion' + filasJuego + nJuego).show();
			$('#unidad' + filasJuego + nJuego).show();
			$('#cantidad' + filasJuego + nJuego).show();
			$('#pu' + filasJuego + nJuego).show();
			$('#subtotal' + filasJuego + nJuego).show();

			$('#filas-juego-' + nJuego).val(filasJuego);
		}

	});

	$('.cotizacion-formulas').on('keyup', function(e) {
		for (var f=1; f<=10; f++) {
			var totalGrupo = 0;

			// Columnas
			for (var c=1; c<=7; c++) {
				var cantidad = parseFloat($("#cantidad" + c + f).val()) || 0;
				var pu = parseFloat($("#pu" + c + f).val()) || 0;

				var subtotal = parseFloat(pu*cantidad);
				$("#subtotal" + c + f).val(subtotal.toFixed(2));

				totalGrupo += subtotal;

				$('#total-' + f).html('$' + totalGrupo.toFixed(2));
			}

		}
	});

	$("#contrato-lote").on("change", function(event){
        var superficie = $(this).find(':selected').attr('data-superficie');
        $('#contrato-superficie').val(superficie);
	});

	$('.contrato-precios').on('keyup', function(e) {
		var precio = parseFloat($('#contrato-precio').val()) || 0;
		var pactado = parseFloat($('#contrato-enganche-pactado').val()) || 0;
		var pagado = parseFloat($('#contrato-enganche-pagado').val()) || 0;
		var plazo = parseFloat($('#contrato-enganche-plazo').val()) || 0;
		var interes = parseFloat($('#contrato-enganche-interes').val()/100) || 0;

		var engancheDiferido = parseFloat(pactado-pagado);
		var importeFinanciar = parseFloat(precio-pactado);
		
		$("#contrato-enganche-diferido").val(engancheDiferido.toFixed(2));
		$("#contrato-importe-financiar").val(importeFinanciar.toFixed(2));

		var abonoCapitalTotal = 0;
		var interesTotal = 0;
		var mensualidadTotal = 0;

		$('.enganches-tr').hide();

		for (var x=1; x<=plazo; x++) {
			$('#enganche-'+x).show();
			var periodo = parseInt($('#enganche-periodo-'+x).val());

			abonoCapitalTotal += parseFloat(ExcelFormulas.PPMT(interes, periodo, plazo, -engancheDiferido).toFixed(2));
			mensualidadTotal += parseFloat(ExcelFormulas.PPMT(interes, periodo, plazo, -engancheDiferido).toFixed(2));

			$('#enganche-capital-'+x).val('$ ' + ExcelFormulas.PPMT(interes, periodo, plazo, -engancheDiferido).toFixed(2));
			$('#enganche-interes-'+x).val('$ 0.00');
			$('#enganche-mensualidad-'+x).val('$ ' + ExcelFormulas.PPMT(interes, periodo, plazo, -engancheDiferido).toFixed(2));

			if (x == 1) {
				var primeroSaldo = engancheDiferido - ExcelFormulas.PPMT(interes, periodo, plazo, -engancheDiferido).toFixed(2);
				$('#enganche-saldo-'+x).val('$ ' + primeroSaldo.toFixed(2));
			} else {
				var formulaResultado = ExcelFormulas.PPMT(interes, periodo, plazo, -engancheDiferido).toFixed(0);
				var saldoSuperior = parseFloat($('#enganche-saldo-' + (x-1)).val().replace(/[^\d.-]/g, ''));
				var resultado = saldoSuperior-formulaResultado;

				if (resultado < 0) {
					resultado = 0;
				}

				$('#enganche-saldo-'+x).val('$ ' + resultado.toFixed(2));
			}
		}

		$('#enganche-capital-f').val('$ ' + abonoCapitalTotal.toFixed(2));
		$('#enganche-interes-f').val('$ 0.00');
		$('#enganche-mensualidad-f').val('$ ' + mensualidadTotal.toFixed(2));

		// Mensualidades
		var ordinarioAnual = parseFloat($('#contrato-mensualidad-ordinario-anual').val()) || 0;
		var ordinarioAnualRes = ordinarioAnual/12;
		$('#contrato-mensualidad-ordinario-mensual').val(ordinarioAnualRes.toFixed(2));

		var moratorioAnual = parseFloat($('#contrato-mensualidad-moratorio-anual').val()) || 0;
		var moratorioAnualRes = moratorioAnual/12;
		$('#contrato-mensualidad-moratorio-mensual').val(moratorioAnualRes.toFixed(2));

		var mensualidadPlazo = parseFloat($('#contrato-mensualidad-plazo').val()) || 0;
		var interes = parseFloat($('#contrato-mensualidad-ordinario-mensual').val()/100) || 0;

		var abonoCapitalTotal = 0;
		var interesTotal = 0;
		var mensualidadTotal = 0;

		$('.mensualidades-tr').hide();

		for (var x=1; x<=mensualidadPlazo; x++) {
			$('#mensualidad-'+x).show();
			var periodo = parseInt($('#mensualidad-periodo-'+x).val());

			abonoCapitalTotal += parseFloat(ExcelFormulas.PPMT(interes, periodo, mensualidadPlazo, -importeFinanciar).toFixed(2));
			interesTotal += parseFloat(ExcelFormulas.IPMT2(interes, periodo, mensualidadPlazo, -importeFinanciar).toFixed(2));
			mensualidadTotal += parseFloat(ExcelFormulas.PPMT(interes, periodo, mensualidadPlazo, -importeFinanciar).toFixed(2));

			$('#mensualidad-capital-'+x).val('$ ' + ExcelFormulas.PPMT(interes, periodo, mensualidadPlazo, -importeFinanciar).toFixed(2));
			$('#mensualidad-interes-'+x).val('$ ' + ExcelFormulas.IPMT2(interes, periodo, mensualidadPlazo, -importeFinanciar).toFixed(2));

			var totalMensualidad = parseFloat(
				ExcelFormulas.PPMT(interes, periodo, mensualidadPlazo, -importeFinanciar) +
				ExcelFormulas.IPMT2(interes, periodo, mensualidadPlazo, -importeFinanciar)
			);

			$('#mensualidad-mensualidad-'+x).val('$ ' + totalMensualidad.toFixed(2));

			if (x == 1) {
				var primeroSaldo = importeFinanciar - ExcelFormulas.PPMT(interes, periodo, mensualidadPlazo, -importeFinanciar).toFixed(2);
				$('#mensualidad-saldo-'+x).val('$ ' + primeroSaldo.toFixed(2));
			} else {
				var formulaResultado = ExcelFormulas.PPMT(interes, periodo, mensualidadPlazo, -importeFinanciar).toFixed(0);
				var saldoSuperior = parseFloat($('#mensualidad-saldo-' + (x-1)).val().replace(/[^\d.-]/g, ''));
				var resultado = saldoSuperior-formulaResultado;

				if (resultado < 0) {
					resultado = 0;
				}

				$('#mensualidad-saldo-'+x).val('$ ' + resultado.toFixed(2));
			}
		}

		$('#mensualidad-capital-f').val('$ ' + abonoCapitalTotal.toFixed(2));
		$('#mensualidad-interes-f').val('$ ' + interesTotal.toFixed(2));
		$('#mensualidad-mensualidad-f').val('$ ' + mensualidadTotal.toFixed(2));
	});

	$(document).on('click', '.verificar-pago', function() {
		var mesChecar = parseInt($(this).attr('data-id'));
        var mesesChecados = new Array();
		$('.verificar-pago').each(function(){
			var checked = $(this).prop('checked');
			mesesChecados.push(checked);
		});

		var mesesPagar = 1;

		for (var x=0; x<=mesChecar-1; x++) {
			if (mesesChecados[x] === false) {
				Swal.fire({
			        title: "Advertencia",
			        text: "Para pagar este mes, primero se deben pagar las mensualidades anteriores.",
			        icon: "warning",
			        confirmButtonText: "Aceptar"
			    });

			    $('.verificar-pago').each(function(){
					$(this).prop('checked', false);
				});

				$('#tabla-pago').hide();

				return false;
			} else {
				mesesPagar++;
			}
		}

		var totalPagar = mesesPagar*250;

		$('#tabla-pago-subtotal').html('$ ' + totalPagar.toFixed(2) + ' USD');
		$('#tabla-pago-total').html('$ ' + totalPagar.toFixed(2) + ' USD');

		$('#tabla-pago').show();
	});

	var precioBoleto = 900;
	$("#fact-boletos").on("change", function(event){
        var b = $(this).val();
        var res = parseFloat(b*precioBoleto);

        $('#fact-importe').val(res.toFixed(2));
	});

	$('#asistencia').bind("keypress", function(e) {
		var boleto = $("#asistencia").val();

		var code = e.keyCode || e.which; 
		if (code  == 13) {
	    	$.ajax({
		        url: window.STASIS + "/movimientos/eventos/confirmar/" + boleto,
		        success: function(output){
		            $('#contenedor-asistencia').html(output);
		        }
		    });
		return false;
		}
	});







	






















































	$('.kt_datatable_sort').DataTable({
	    responsive: true,
	    order: [[ 0, "asc" ]],
	    lengthMenu: [
	        [10, 50, 100, -1],
	        [10, 50, 100, "All"]
	    ],
	    dom: "<'row'<'col-md-6'l><'col-md-6'p>r>t<'row'<'col-md-6'i><'col-md-6'p>r>",
	});

	$('.kt_datatable_nosort').DataTable({
	    responsive: true,
	    order: false,
	    lengthMenu: [
	        [10, 50, 100, -1],
	        [10, 50, 100, "All"]
	    ],
	    dom: "<'row'<'col-md-6'l><'col-md-6'p>r>t<'row'<'col-md-6'i><'col-md-6'p>r>",
	});

	$('#kt_datatable_search').on('keyup', function () {
	    $('.kt_datatable_sort').DataTable().search( this.value ).draw();
	} );

	$("#add-link").on("click", function(event){
		$('#new-link').fadeIn();
	});

	//

	$("#listadoEmpleados").on("change", function(event){
        var idEmpleado = $(this).val();

    	$.ajax({
	        url: window.STASIS + "/rrhh/obtener_info/" + idEmpleado,
	        success: function(output){
	            $('#log').html(output);
	        	$('#contenedor-temp').slideUp(function(){
					$('#contenedor-escondido').slideDown();
				});
	        }
	    });
	});
	// Cuando se selecciona un archivo en Descriptivo de Puestos/Expediente
    $(document).on('change', ':file', function() {
        var input = $(this),
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [label]);
    });
    // $(document).ready( function() {
    //     $(':file').on('fileselect', function(event, label) {
    //     	$('#btn-subir-imagen-descriptivo').removeClass('disabled');
    //         $('#btn-subir-imagen-descriptivo').html('<i class="fa fa-upload"></i> Subir Imagen: ' + label);
    //     });
    // });

    // Cuando se selecciona un archivo en el Expediente
    $('.archivo-expediente').on('fileselect', function(event, label) {
    	var id = ($(this).attr('class').split(' ')[0]);
    	$('.btn-'+id).removeClass('disabled');
    });

	$("#prevent").bind("contextmenu cut copy paste", function(e) {
	    e.preventDefault();
	});

	$(".campo-precio").mouseup(function(e){
		e.preventDefault();
	});

	$('#recibos_material_po').click(function(event){
		$('#form-recibos-material-po').submit();
    });

    $('#modificarSeleccionarOrdenesCompraBtn').click(function(event){
		$('#modificarSeleccionarOrdenesCompra').submit();
    });

    $('#seleccionarOrdenesCompraBtn').click(function(event){
		$('#seleccionarOrdenesCompra').submit();
    });

    $('#factura-importexportBtn').click(function(event){
		$('#form-import-export').submit();
    });

    $('#factura-partesRecibidas').click(function(event){
		$('#modificarPartidas').submit();
    });

    $('#modificar-datos-facturas').click(function(event){
		$('#cuentas-cor-cobrar-multiples').submit();
    });

    $('#crear-factura-remisiones').click(function(event){
		$('#remisiones-factura').submit();
    });

	$('#orden_compra_metodo_pago').change(function(){
	  if($(this).val() == 6){
		$('.orden_compra_fecha_pago_hidden').fadeIn();
	  } else {
		$('.orden_compra_fecha_pago_hidden').fadeOut();
	  }
	});

	$('.denegarEnter').bind("keyup keypress", function(e) {
		var code = e.keyCode || e.which; 
		if (code  == 13) {               
			e.preventDefault();
		return false;
		}
	});

	// Flecha abajo en import
	$('.import-export-flecha').bind("keypress", function(e) {
		var code = e.keyCode || e.which; 
		if (code  == 40) {
			var id = $(this).attr('id');
			var idNumero = id.replace(/\D/g, '');
			idNumero++;
			$('#cantidad-recibida'+idNumero).focus();
			
			return false;
		}

		if (code  == 38) {     
			var id = $(this).attr('id');
			var idNumero = id.replace(/\D/g, '');
			idNumero--;
			$('#cantidad-recibida'+idNumero).focus();
			
			return false;
		}
	});

	// Prevenir backspace->back
	$(document).on("keydown", function (e) {
	    if (e.which === 8 && !$(e.target).is("input, textarea")) {
	        e.preventDefault();
	    }
	});

	// Boton cancelar traspaso
	$('#reporte-global-partes').on("click", function(event){
        $.Zebra_Dialog('<p>Se generará un reporte global en Excel de todos los números de parte. Esto tomará alrededor de <strong>3-5 minutos</strong>.</p><p>¿Estás seguro realizar esta acción?</p>', {
		    'type':     'warning',
		    'title':    'Advertencia',
		    'buttons':  [
		                    {caption: 'Cancelar', callback: function() {}},
		                    {caption: 'Aceptar', callback: function() {
		                    	$('#form-global-partes').submit();
		                   	}}
		                ]
		});
	});

	// Boton activar notificaciones de chat
	$('#solicitud-reporte-global').on("click", function(event){
		var idUsuario = $(this).attr('data-usuario');
		var tipo = $(this).attr('data-tipo');
        $.Zebra_Dialog('¿Estás seguro de enviar una solicitud de autorización para ver el catálogo completo?<br /><br />En el momento en que tu solicitud sea revisada, recibirás un correo informándote si fué <strong>autorizada</strong> o <strong>denegada</strong>.', {
		    'type':     'question',
		    'title':    'Confirmación',
		    'width':	'500',
		    'buttons':  [
		                    {caption: 'Cancelar', callback: function() {}},
		                    {caption: 'Aceptar', callback: function() {
		                    	location.href = window.STASIS + '/catalogos/' + tipo + '/solicitud_global/' + idUsuario;
		                   	}}
		                ]
		});
	});

	// Boton activar notificaciones de chat
	$('#chat-activar-notificaciones').on("click", function(event){
		if (window.webkitNotifications) {
			var havePermission = window.webkitNotifications.checkPermission();
			if (havePermission != 0) {
				window.webkitNotifications.requestPermission();
			} else {
				$.Zebra_Dialog('Notificaciones de chat han sido activadas.<br /><br />Cuando estés ausente o no estés viendo directamente la ventana de IMS, aparecerá un pequeño recuadro del lado', {
	            'type':     'confirmation',
	            'title':    'Notificaciones de Chat'
	        	});
			}
		} else {
	        $.Zebra_Dialog('Para activar las notificaciones del chat es necesario utilizar <strong>Google Chrome</strong>, ya que por el momento este explorador no es compatible.', {
	            'type':     'information',
	            'title':    'Notificaciones de Chat'
	        });
		}
	});

	// Boton autorizar traspaso
	$('.traspaso-autorizar').on("click", function(event){
		var idActual = $(this).attr('id');
        $.Zebra_Dialog('¿Estás seguro que deseas <strong>autorizar</strong> este traspaso?', {
		    'type':     'question',
		    'title':    'Confirmación',
		    'buttons':  [
		                    {caption: 'Cancelar', callback: function() {}},
		                    {caption: 'Aceptar', callback: function() {
		                    	location.href = window.STASIS + '/movimientos/traspasos/autorizar/' + idActual;
		                   	}}
		                ]
		});
	});

	// Boton denegar traspaso
	$('.traspaso-denegar').on("click", function(event){
		var idActual = $(this).attr('id');
        $.Zebra_Dialog('¿Estás seguro que deseas <strong>denegar</strong> este traspaso?', {
		    'type':     'question',
		    'title':    'Confirmación',
		    'buttons':  [
		                    {caption: 'Cancelar', callback: function() {}},
		                    {caption: 'Aceptar', callback: function() {
		                    	location.href = window.STASIS + '/movimientos/traspasos/denegar/' + idActual;
		                   	}}
		                ]
		});
	});

	// Boton cancelar traspaso
	$('.traspaso-cancelar').on("click", function(event){
		var idActual = $(this).attr('id');
        $.Zebra_Dialog('¿Estás seguro que deseas <strong>cancelar</strong> este traspaso?', {
		    'type':     'question',
		    'title':    'Confirmación',
		    'buttons':  [
		                    {caption: 'Cancelar', callback: function() {}},
		                    {caption: 'Aceptar', callback: function() {
		                    	location.href = window.STASIS + '/movimientos/traspasos/cancelar/' + idActual;
		                   	}}
		                ]
		});
	});

	// Boton activar cliente
	$('.empleado-inactivar').on("click", function(event){
		var idActual = $(this).attr('id');
        $.Zebra_Dialog('¿Estás seguro que deseas inactivar a este empleado?', {
		    'type':     'question',
		    'title':    'Confirmación',
		    'buttons':  [
		                    {caption: 'Cancelar', callback: function() {}},
		                    {caption: 'Aceptar', callback: function() {
		                    	location.href = window.STASIS + '/empleados/administrar/inactivar/' + idActual;
		                   	}}
		                ]
		});
	});

	// Boton activar cliente
	$('.cc-inactivar').on("click", function(event){
		var idActual = $(this).attr('id');
        $.Zebra_Dialog('¿Estás seguro que deseas inactivar a este centro de costo?', {
		    'type':     'question',
		    'title':    'Confirmación',
		    'buttons':  [
		                    {caption: 'Cancelar', callback: function() {}},
		                    {caption: 'Aceptar', callback: function() {
		                    	location.href = window.STASIS + '/catalogos/centros_costo/inactivar/' + idActual;
		                   	}}
		                ]
		});
	});

	// Boton activar cliente
	$('.gasto-eliminar').on("click", function(event){
		var idActual = $(this).attr('id');
        $.Zebra_Dialog('¿Estás seguro que deseas eliminar este gasto?', {
		    'type':     'question',
		    'title':    'Confirmación',
		    'buttons':  [
		                    {caption: 'Cancelar', callback: function() {}},
		                    {caption: 'Aceptar', callback: function() {
		                    	location.href = window.STASIS + '/finanzas/gastos/eliminar/' + idActual;
		                   	}}
		                ]
		});
	});

	// Boton activar cliente
	$('.factura-inactivar').on("click", function(event){
		var idActual = $(this).attr('id');
		var slugIntegracion = $('#slugIntegracion').val();
        $.Zebra_Dialog('¿Estás seguro que deseas eliminar esta factura?', {
		    'type':     'question',
		    'title':    'Confirmación',
		    'buttons':  [
		                    {caption: 'Cancelar', callback: function() {}},
		                    {caption: 'Aceptar', callback: function() {
		                    	location.href = window.STASIS + '/movimientos/facturacion/inactivar/' + slugIntegracion + '/' + idActual;
		                   	}}
		                ]
		});
	});

	// Boton activar cliente
	$('.invoice-inactivar').on("click", function(event){
		var idActual = $(this).attr('id');
		var slugIntegracion = $('#slugIntegracion').val();
        $.Zebra_Dialog('¿Estás seguro que deseas eliminar esta invoice?', {
		    'type':     'question',
		    'title':    'Confirmación',
		    'buttons':  [
		                    {caption: 'Cancelar', callback: function() {}},
		                    {caption: 'Aceptar', callback: function() {
		                    	location.href = window.STASIS + '/movimientos/invoice/cancelar/' + idActual;
		                   	}}
		                ]
		});
	});

	// Boton activar cliente
	$('.cotizaciones-inactivar').on("click", function(event){
		var idActual = $(this).attr('id');
		var slugIntegracion = $('#slugIntegracion').val();
        $.Zebra_Dialog('¿Estás seguro que deseas eliminar esta cotización?', {
		    'type':     'question',
		    'title':    'Confirmación',
		    'buttons':  [
		                    {caption: 'Cancelar', callback: function() {}},
		                    {caption: 'Aceptar', callback: function() {
		                    	location.href = window.STASIS + '/movimientos/cotizaciones/cancelar/' + idActual;
		                   	}}
		                ]
		});
	});

	// Boton activar cliente
	$('.ocmx-inactivar').on("click", function(event){
		var idActual = $(this).attr('id');
		var slugIntegracion = $('#slugIntegracion').val();
        $.Zebra_Dialog('¿Estás seguro que deseas eliminar esta cotización?', {
		    'type':     'question',
		    'title':    'Confirmación',
		    'buttons':  [
		                    {caption: 'Cancelar', callback: function() {}},
		                    {caption: 'Aceptar', callback: function() {
		                    	location.href = window.STASIS + '/movimientos/ordenes_compra_mx/cancelar/' + idActual;
		                   	}}
		                ]
		});
	});

	// Boton activar cliente
	$('.ocusa-inactivar').on("click", function(event){
		var idActual = $(this).attr('id');
		var slugIntegracion = $('#slugIntegracion').val();
        $.Zebra_Dialog('¿Estás seguro que deseas eliminar esta cotización?', {
		    'type':     'question',
		    'title':    'Confirmación',
		    'buttons':  [
		                    {caption: 'Cancelar', callback: function() {}},
		                    {caption: 'Aceptar', callback: function() {
		                    	location.href = window.STASIS + '/movimientos/ordenes_compra_usa/cancelar/' + idActual;
		                   	}}
		                ]
		});
	});

	// Boton activar cliente
	$('.ocint-inactivar').on("click", function(event){
		var idActual = $(this).attr('id');
		var slugIntegracion = $('#slugIntegracion').val();
        $.Zebra_Dialog('¿Estás seguro que deseas eliminar esta cotización?', {
		    'type':     'question',
		    'title':    'Confirmación',
		    'buttons':  [
		                    {caption: 'Cancelar', callback: function() {}},
		                    {caption: 'Aceptar', callback: function() {
		                    	location.href = window.STASIS + '/movimientos/ordenes_compra_int/cancelar/' + idActual;
		                   	}}
		                ]
		});
	});

	// Boton activar cliente
	$('.empleado-reactivar').on("click", function(event){
		var idActual = $(this).attr('id');
        $.Zebra_Dialog('¿Estás seguro que deseas reactivar a este empleado?', {
		    'type':     'question',
		    'title':    'Confirmación',
		    'buttons':  [
		                    {caption: 'Cancelar', callback: function() {}},
		                    {caption: 'Aceptar', callback: function() {
		                    	location.href = window.STASIS + '/empleados/administrar/reactivar/' + idActual;
		                   	}}
		                ]
		});
	});

	// Boton activar cliente
	$('.cliente-inactivar').on("click", function(event){
		var idActual = $(this).attr('id');
        $.Zebra_Dialog('¿Estás seguro que deseas inactivar a este cliente?', {
		    'type':     'question',
		    'title':    'Confirmación',
		    'buttons':  [
		                    {caption: 'Cancelar', callback: function() {}},
		                    {caption: 'Aceptar', callback: function() {
		                    	location.href = window.STASIS + '/catalogos/clientes/inactivar/' + idActual;
		                   	}}
		                ]
		});
	});

	// Boton activar cliente
	$('.cliente-reactivar').on("click", function(event){
		var idActual = $(this).attr('id');
        $.Zebra_Dialog('¿Estás seguro que deseas reactivar a este cliente?', {
		    'type':     'question',
		    'title':    'Confirmación',
		    'buttons':  [
		                    {caption: 'Cancelar', callback: function() {}},
		                    {caption: 'Aceptar', callback: function() {
		                    	location.href = window.STASIS + '/catalogos/clientes/reactivar/' + idActual;
		                   	}}
		                ]
		});
	});

	// Boton activar cliente
	$('.enviar-pdf-xml').on("click", function(event){
		var idActual = $(this).attr('id');
		var slugIntegracion = $(this).attr('data-integracion');
		var email1 = $(this).attr('data-email1');
		var email2 = $(this).attr('data-email2');
		var email3 = $(this).attr('data-email3');

        $.Zebra_Dialog('<form id="enviarPdfXmlForm">¿Deseas enviar las facturas por correo?<br /><br /><input name="email1" type="checkbox" value="' + email1 + '" id="email1" /> ' + email1 + '<br /><input name="email2" type="checkbox" value="' + email2 + '" id="email2" /> ' + email2 + '<br /><input name="email3" type="checkbox" value="' + email3 + '" id="email3" /> ' + email3 + '<br /><input name="email7" class="form-control input-sm input-3" type="text" value="" id="email7" placeholder="Otro correo" /><input type="hidden" name="integracion" value="' + slugIntegracion + '" /></form>', {
		    'type':     'question',
		    'title':    'Confirmación',
		    'buttons':  [
		                    {caption: 'Cancelar', callback: function() {}},
		                    {caption: 'Aceptar', callback: function() {
							    var values = $('#enviarPdfXmlForm').serialize();

		                    	$.ajax({
							        url: window.STASIS + '/movimientos/facturacion/enviarPdfXml/' + slugIntegracion + '/' + idActual,
							        type: "post",
							        data: values,
							        success: function(){
							        	$('.c'+idActual).removeClass('columna-verde');
							            $('.c'+idActual+' td').animate({backgroundColor: '#b5dce8'});
							            $('#cs'+idActual).html("Enviada");
							        }
							    });
		                   	}}
		                ]
		});
	});

	// Boton activar cliente
	$('.almacen-inactivar').on("click", function(event){
		var idActual = $(this).attr('id');
        $.Zebra_Dialog('¿Estás seguro que deseas inactivar este almacen?', {
		    'type':     'question',
		    'title':    'Confirmación',
		    'buttons':  [
		                    {caption: 'Cancelar', callback: function() {}},
		                    {caption: 'Aceptar', callback: function() {
		                    	location.href = window.STASIS + '/catalogos/almacenes/inactivar/' + idActual;
		                   	}}
		                ]
		});
	});

	// Boton activar almacen
	$('.almacen-reactivar').on("click", function(event){
		var idActual = $(this).attr('id');
        $.Zebra_Dialog('¿Estás seguro que deseas reactivar este almacen?', {
		    'type':     'question',
		    'title':    'Confirmación',
		    'buttons':  [
		                    {caption: 'Cancelar', callback: function() {}},
		                    {caption: 'Aceptar', callback: function() {
		                    	location.href = window.STASIS + '/catalogos/almacenes/reactivar/' + idActual;
		                   	}}
		                ]
		});
	});

	$('.familia-inactivar').on("click", function(event){
		var idActual = $(this).attr('id');
        $.Zebra_Dialog('¿Estás seguro que deseas inactivar esta familia?', {
		    'type':     'question',
		    'title':    'Confirmación',
		    'buttons':  [
		                    {caption: 'Cancelar', callback: function() {}},
		                    {caption: 'Aceptar', callback: function() {
		                    	location.href = window.STASIS + '/catalogos/familias/inactivar/' + idActual;
		                   	}}
		                ]
		});
	});

	// Boton activar almacen
	$('.familia-reactivar').on("click", function(event){
		var idActual = $(this).attr('id');
        $.Zebra_Dialog('¿Estás seguro que deseas reactivar esta familia?', {
		    'type':     'question',
		    'title':    'Confirmación',
		    'buttons':  [
		                    {caption: 'Cancelar', callback: function() {}},
		                    {caption: 'Aceptar', callback: function() {
		                    	location.href = window.STASIS + '/catalogos/familias/reactivar/' + idActual;
		                   	}}
		                ]
		});
	});

	// Boton activar cliente
	$('.parte-inactivar').on("click", function(event){
		var idActual = $(this).attr('id');
        $.Zebra_Dialog('¿Estás seguro que deseas inactivar esta parte?', {
		    'type':     'question',
		    'title':    'Confirmación',
		    'buttons':  [
		                    {caption: 'Cancelar', callback: function() {}},
		                    {caption: 'Aceptar', callback: function() {
		                    	location.href = window.STASIS + '/catalogos/partes/inactivar/' + idActual;
		                   	}}
		                ]
		});
	});

	// Boton activar parte
	$('.parte-reactivar').on("click", function(event){
		var idActual = $(this).attr('id');
        $.Zebra_Dialog('¿Estás seguro que deseas reactivar esta parte?', {
		    'type':     'question',
		    'title':    'Confirmación',
		    'buttons':  [
		                    {caption: 'Cancelar', callback: function() {}},
		                    {caption: 'Aceptar', callback: function() {
		                    	location.href = window.STASIS + '/catalogos/partes/reactivar/' + idActual;
		                   	}}
		                ]
		});
	});

	// Boton activar proveedor
	$('.proveedor-inactivar').on("click", function(event){
		var idActual = $(this).attr('id');
        $.Zebra_Dialog('¿Estás seguro que deseas inactivar a este proveedor?', {
		    'type':     'question',
		    'title':    'Confirmación',
		    'buttons':  [
		                    {caption: 'Cancelar', callback: function() {}},
		                    {caption: 'Aceptar', callback: function() {
		                    	location.href = window.STASIS + '/catalogos/proveedores/inactivar/' + idActual;
		                   	}}
		                ]
		});
	});

	// Boton activar cliente
	$('.proveedor-reactivar').on("click", function(event){
		var idActual = $(this).attr('id');
        $.Zebra_Dialog('¿Estás seguro que deseas reactivar a este proveedor?', {
		    'type':     'question',
		    'title':    'Confirmación',
		    'buttons':  [
		                    {caption: 'Cancelar', callback: function() {}},
		                    {caption: 'Aceptar', callback: function() {
		                    	location.href = window.STASIS + '/catalogos/proveedores/reactivar/' + idActual;
		                   	}}
		                ]
		});
	});

	var unidadesMedidaSelect = '<option value=""><option value="111">PIEZA</option> <option value="112">ELEMENTO</option> <option value="113">UNIDAD DE SERVICIO</option> <option value="114">ACTIVIDAD</option> <option value="115">KILOGRAMO</option> <option value="116">TRABAJO</option> <option value="117">TARIFA</option> <option value="118">METRO</option> <option value="119">PAQUETE A GRANEL</option> <option value="120">CAJA BASE</option> <option value="121">KIT</option> <option value="122">CONJUNTO</option> <option value="123">LITRO</option> <option value="124">CAJA</option> <option value="125">MES</option> <option value="126">HORA</option> <option value="127">METRO CUADRADO</option> <option value="128">EQUIPOS</option> <option value="129">MILIGRAMO</option> <option value="130">PAQUETE</option> <option value="131">KIT (CONJUNTO DE PIEZAS)</option> <option value="132">VARIEDAD</option> <option value="133">GRAMO</option> <option value="134">PAR</option> <option value="135">DOCENAS DE PIEZAS</option> <option value="136">UNIDAD</option> <option value="137">DÍA</option> <option value="138">LOTE</option> <option value="139">GRUPOS</option> <option value="140">MILILITRO</option> <option value="141">VIAJE</option>';
	var unidadesMedidaSelectBaseline = '';

	//////////////////////////////////
	// Validaciones en movimientos //
	//////////////////////////////////
	
});