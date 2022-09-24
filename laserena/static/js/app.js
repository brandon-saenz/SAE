$(function(){
	$(document).on("keydown", function (e) {
	    if (e.which === 8 && !$(e.target).is("input, textarea")) {
	        e.preventDefault();
	    }
	});

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
		$('.kt_datatable-0').DataTable().search( this.value ).draw();
		$('.kt_datatable-1').DataTable().search( this.value ).draw();
		$('.kt_datatable-2').DataTable().search( this.value ).draw();
	} );

	// Cambiar de tipo
	$("#solicitud-tipo").on("change", function(event){
        var t = $(this).val();
        $('#contenedor-otro').hide();
        $("#solicitud-servicios").val('');
        $("#solicitud-servicios option").hide();
        $("#solicitud-servicios option[data-t='" + t + "']").show();
	});

	// Otro
	$("#solicitud-servicios").on("change", function(event){
        var o = $(this).val();

        if (o == -1) {
        	$('#contenedor-otro').slideDown();
        	$('#input-otro').attr('required', 'required');
        } else {
        	$('#input-otro').removeAttr('required');
        	$('#contenedor-otro').slideUp();
        }
	});

	// Otro
	$("#evaluacion-tipo").on("change", function(event){
        var o = $(this).val();

        if (o != 5) {
        	$('.evaluacion-preguntas').show();
        } else {
        	$('.evaluacion-preguntas').hide();
        }
	});

	$(document).on('click', '.id-solicitud', function() {
        var id = $(this).attr('data-id');

		$.ajax({
	        url: window.STASIS + "/movimientos/solicitudes/info",
	        type: "POST",
	        data: { id: id },
	        success: function(output){
	            $('#info-solicitud').html(output);
	        }
	    });

	    $.ajax({
	        url: window.STASIS + "/movimientos/solicitudes/info_comentarios",
	        type: "POST",
	        data: { id: id },
	        success: function(output){
	            $('#info-comentarios').html(output);
	        }
	    });
	});

	$('#form-nueva-solicitud').submit(function(e) {
	    e.preventDefault(); 
	    $('#btn-enviar-solicitud').attr('disabled', true);
	    this.submit();
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

});