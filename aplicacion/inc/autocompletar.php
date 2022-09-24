<?php
// error_reporting(0);
$lista = array('localhost', '127.0.0.1');
if(in_array($_SERVER['HTTP_HOST'], $lista)) {
	$config['base_url']				= 'http://localhost/madre';
	define ('DBPATH','localhost');
	define ('DBUSER','root');
	define ('DBPASS','');
	define ('DBNAME','impergom_db');
} else {
	$config['base_url']				= 'http://madre.impergom.mx';
	define ('DBPATH','localhost');
	define ('DBUSER','impergom_admin');
	define ('DBPASS','?wGDwrjHLrBi');
	define ('DBNAME','impergom_db');
}
global $config;
define('STASIS', $config['base_url']);
setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
date_default_timezone_set('America/Tijuana');
$dbh = mysqli_connect(DBPATH,DBUSER,DBPASS);
mysqli_select_db($dbh, DBNAME);
function formatearFecha($original='', $format="%d/%m/%Y") {
	$format = ($format=='date' ? "%m-%d-%Y" : $format);
	$format = ($format=='datetime' ? "%m-%d-%Y %H:%M:%S" : $format);
	$format = ($format=='mysql-date' ? "%Y-%m-%d" : $format);
	$format = ($format=='mysql-datetime' ? "%Y-%m-%d %H:%M:%S" : $format);
	return (!empty($original) ? strftime($format, strtotime($original)) : "" );
}
if (isset($_GET['term']) && isset($_GET['tipo'])) {
	function array_to_json( $array ){
		if( !is_array( $array ) ){
			return false;
		}
		$associative = count( array_diff( array_keys($array), array_keys( array_keys( $array )) ));
		if( $associative ){
			$construct = array();
			foreach( $array as $key => $value ){
				if( is_numeric($key) ){
					$key = "key_$key";
				}
				$key = "\"".addslashes($key)."\"";
				if( is_array( $value )){
					$value = array_to_json( $value );
				} else if( !is_numeric( $value ) || is_string( $value ) ){
					$value = "\"".addslashes($value)."\"";
				}
				$construct[] = "$key: $value";
			}
			$result = "{ " . implode( ", ", $construct ) . " }";
		} else {
			$construct = array();
			foreach( $array as $value ){
				if( is_array( $value )){
					$value = array_to_json( $value );
				} else if( !is_numeric( $value ) || is_string( $value ) ){
					$value = "'".addslashes($value)."'";
				}
				$construct[] = $value;
			}
			$result = "[ " . implode( ", ", $construct ) . " ]";
		}
		return $result;
	}
	$q = strtolower($_GET["term"]);
	if (!$q) return;
	
	$autocompleteDatos = array();
	// Tipo de Autocomplete
	switch ($_GET['tipo']) {
		// Verificar si existe el numero de parte, en caso contrario quitarle el readonly a los campos
		case 'noparteverificar':
		$resultado = mysqli_query($dbh, "SELECT codigo FROM partes WHERE status = 1 AND codigo LIKE '$q%' ORDER BY codigo ASC");
		while ($datos = mysqli_fetch_array($resultado)) {
			$id = $datos['codigo'];
			$nombre = $datos['codigo'];
			$autocompleteDatos[$nombre] = $id;
		}
		break;
		// No Parte (Para vender)
		case 'noparte':
		$resultado = mysqli_query($dbh, "SELECT codigo FROM partes WHERE status = 1 AND codigo LIKE '$q%' ORDER BY codigo ASC");
		while ($datos = mysqli_fetch_array($resultado)) {
			$id = $datos['codigo'];
			$nombre = $datos['codigo'];
			$autocompleteDatos[$nombre] = $id;
		}
		break;

		// ID Factura Comprobante de Pago
		case 'idfactura':
		$resultado = mysqli_query($dbh, "SELECT f.id, c.razon_social, f.total, f.fecha
			FROM facturas f
			JOIN clientes c
			ON c.id = f.id_cliente
			WHERE f.status = 1 AND f.timbrado = 1 AND f.cancelado = 0 AND f.id LIKE '%$q%'");
		while ($datos = mysqli_fetch_array($resultado)) {
			$id = $datos['id'];
			$nombre = $datos['razon_social'];
			$total = number_format($datos['total'], 2, '.', ',');
			$autocompleteDatos["$id - $nombre ($ $total)"] = $id;
		}
		break;
	}
	
	$result = array();
	foreach ($autocompleteDatos as $key=>$value) {
		if (strpos(strtolower($key), $q) !== false) {
			array_push($result, array("id"=>$value, "label"=>$key, "value" => strip_tags($key)));
		}
		if (count($result) > 11)
			break;
	}
	echo array_to_json($result);
// Get Datos
} elseif (isset($_GET['gTipo']) && isset($_GET['valor'])) {
	switch ($_GET['gTipo']) {
		// Factura Comprobante de Pago Bonat
		case 'idfactura':
		$id = $_GET['valor'];
		$fila = $_GET['fila'];
		$resultado = mysqli_query($dbh, "SELECT f.id, c.razon_social, c.rfc, f.total, DATE(f.fecha) AS fecha, f.moneda
			FROM facturas f
			JOIN clientes c
			ON c.id = f.id_cliente
			WHERE f.status = 1 AND f.timbrado = 1 AND f.cancelado = 0 AND f.id = '$id' LIMIT 1");
		$datos = mysqli_fetch_array($resultado);
		$idCliente = $datos['id'];
		if ($datos['moneda'] == 1) {
			$moneda = 'MXN';
		} else {
			$moneda = 'USD';
		}

		// Revision de pagos anteriores
		$resultado2 = mysqli_query($dbh, "SELECT SUM(monto) AS pagos
			FROM pagos_facturas pf
			JOIN pagos p
			ON p.id = pf.id_pago
			WHERE pf.id_factura = '$id' AND cancelado = 0");
		$datos2 = mysqli_fetch_array($resultado2);
		$pagos = $datos2['pagos'];
		$saldoPendiente = number_format((float) abs(($datos['total']-$pagos)), 2, '.', '');
		?>
		
		<script>
		$('#idFactura<?php echo $fila; ?>').val('<?php echo $datos['id']; ?>');
		$('#fecha<?php echo $fila; ?>').val('<?php echo formatearFecha($datos['fecha']); ?>');
		$('#total<?php echo $fila; ?>').val('<?php echo number_format($datos['total'], 2, '.', ','); ?>');
		$('#saldo<?php echo $fila; ?>').val('<?php echo number_format($saldoPendiente, 2, '.', ','); ?>');
		$('#moneda<?php echo $fila; ?>').val('<?php echo $moneda; ?>');
		</script>
		<?php
		break;
		// Solicitante
		case 'solicitante':
		$id = $_GET['valor'];
		$resultado = mysqli_query($dbh, "SELECT id, razon_social, metodo_pago, email1 FROM clientes WHERE codigo = '$id' LIMIT 1");
		$datos = mysqli_fetch_array($resultado);
		$idCliente = $datos['id'];
		// Lista de nombres de solicitantes
		$resultado2 = mysqli_query($dbh, "SELECT id, nombre FROM clientes_solicitantes WHERE id_cliente = '$idCliente' ORDER BY nombre ASC");
		$conteo = mysqli_num_rows($resultado2);
		?>
		<script>
		    $('#cotizacion-nombre-solicitante').empty();
		    $('#cotizacion-telefono-solicitante').val('');
			$('#cotizacion-correo-solicitante').val('');
		</script>
		<?php
		if ($conteo == 0) {
			?>
			<script>
			     $('#cotizacion-nombre-solicitante').append("<option value=\"\">No hay solicitantes</option>");
			</script>
			<?php
		} else {
			?>
			<script>
			     $('#cotizacion-nombre-solicitante').append("<option value=\"\">Elegir solicitante...");
			</script>
			<?php
			while ($datos2 = mysqli_fetch_array($resultado2)) {
				$id = $datos2['id'];
				$nombre = $datos2['nombre'];
				?>
				<script>
				     $('#cotizacion-nombre-solicitante').append("<option value=\"<?php echo $id; ?>\"><?php echo $nombre; ?></option>");
				</script>
			<?php
			}
			?>
			<script>
			     $('#cotizacion-nombre-solicitante').removeAttr('disabled');
			</script>
			<?php
		}
		?>
		
		<script>
		$('#cotizacion-razon-social').val('<?php echo $datos['razon_social']; ?>');
		$('#cotizacion-id-cliente').val('<?php echo $datos['id']; ?>');
		$('#email').val('<?php echo $datos['email1']; ?>');
		</script>
		<?php
		break;
		// Solicitante Variante
		case 'solicitante2':
		$id = $_GET['valor'];
		$resultado = mysqli_query($dbh, "SELECT id, razon_social, metodo_pago FROM clientes WHERE codigo = '$id' LIMIT 1");
		$datos = mysqli_fetch_array($resultado);
		$idCliente = $datos['id'];
		?>
		<script>
		$('#cotizacion-id-cliente').val('<?php echo $datos['id']; ?>');
		$('#cotizacion-razon-social2').val('<?php echo $datos['razon_social']; ?>');
		</script>
		<?php
		break;
		// Proveedor
		case 'proveedor':
		$id = $_GET['valor'];
		$resultado = mysqli_query($dbh, "SELECT id, razon_social FROM clientes WHERE codigo = '$id' LIMIT 1");
		$datos = mysqli_fetch_array($resultado);
		$idCliente = $datos['id'];
		// Lista de nombres de contactos
		$resultado2 = mysqli_query($dbh, "SELECT id, nombre FROM clientes_solicitantes WHERE id_cliente = '$idCliente' ORDER BY nombre ASC");
		$conteo = mysqli_num_rows($resultado2);
		?>
		<script>
		    $('#cotizacion-nombre-solicitante').empty();
		    $('#cotizacion-telefono-solicitante').val('');
			$('#cotizacion-correo-solicitante').val('');
		</script>
		<?php
		if ($conteo == 0) {
			?>
			<script>
			     $('#cotizacion-nombre-solicitante').append("<option value=\"\">No hay contactos</option>");
			</script>
			<?php
		} else {
			?>
			<script>
			     $('#cotizacion-nombre-solicitante').append("<option value=\"\">Elegir contacto...");
			</script>
			<?php
			while ($datos2 = mysqli_fetch_array($resultado2)) {
				$id = $datos2['id'];
				$nombre = $datos2['nombre'];
				?>
				<script>
				     $('#cotizacion-nombre-solicitante').append("<option value=\"<?php echo $id; ?>\"><?php echo $nombre; ?></option>");
				</script>
			<?php
			}
			?>
			<script>
			     $('#cotizacion-nombre-solicitante').removeAttr('disabled');
			</script>
			<?php
		}
		?>
		
		<script>
		$('#cotizacion-razon-social').val('<?php echo addslashes($datos['razon_social']); ?>');
		$('#cotizacion-id-proveedor').val('<?php echo $datos['id']; ?>');
		</script>
		<?php
		break;
		// Datos de nombre de solicitante
		case 'datos-solicitante':
		$id = $_GET['valor'];
		$resultado = mysqli_query($dbh, "SELECT id, nombre, telefono, correo FROM clientes_solicitantes WHERE id = $id");
		$datos = mysqli_fetch_array($resultado);
		?>
		
		<script>
		$('#cotizacion-telefono-solicitante').val('<?php echo $datos['telefono']; ?>');
		$('#cotizacion-correo-solicitante').val('<?php echo $datos['correo']; ?>');
		</script>
		<?php
		break;
		
		// No Parte (Para vender)
		case 'noparte':
		$id = urldecode($_GET['valor']);
		$resultado = mysqli_query($dbh, "
			SELECT p.id, p.descripcion, m.nombre AS um, p.precio1, p.clave_prodserv, m.abreviacion AS clave_unidad
			FROM partes p
			LEFT JOIN partes_medidas m
			ON p.id_unidad_venta = m.id
			WHERE codigo = '$id' AND status = 1
			LIMIT 1
		");
		$datos = mysqli_fetch_array($resultado);
		?>
		
		<script>
		$('#idParte<?php echo $_GET['fila']; ?>').val('<?php echo $datos['id']; ?>');
		$('#descripcion<?php echo $_GET['fila']; ?>').val("<?php echo $datos['descripcion']; ?>");
		$('#um<?php echo $_GET['fila']; ?>').val('<?php echo $datos['um']; ?>');
		$('#precio<?php echo $_GET['fila']; ?>').val(<?php echo $datos['precio1']; ?>);
		$('#claveProdServ<?php echo $_GET['fila']; ?>').val('<?php echo $datos['clave_prodserv']; ?>');
		$('#claveUnidad<?php echo $_GET['fila']; ?>').val('<?php echo $datos['clave_unidad']; ?>');
		</script>
		<?php
		break;

		// No Parte (Para factura)
		case 'nopartefactura':
		$id = urldecode($_GET['valor']);
		$idAlmacen = urldecode($_GET['idAlmacen']);
		$envioDirecto = $_GET['enviodirecto'];

		if ($envioDirecto == 1) {
			$resultado = mysqli_query($dbh, "SELECT p.id, p.descripcion, p.moneda_venta AS moneda, m.nombre AS um, p.precio1, p.clave_prodserv, p.clave_unidad
				FROM partes p
				LEFT JOIN partes_medidas m
				ON p.id_unidad_venta = m.id
				WHERE codigo = '$id' AND status = 1");
		} else {
			$resultado = mysqli_query($dbh, "SELECT p.id, p.descripcion, p.moneda_venta AS moneda, m.nombre AS um, p.precio1, p.clave_prodserv, p.clave_unidad, SUM(ic.total) AS inventario
				FROM partes p
				LEFT JOIN partes_medidas m
				ON p.id_unidad_venta = m.id
				LEFT JOIN inventario_cedis ic
				ON ic.id_parte = p.id
				WHERE codigo = '$id' AND status = 1 AND ic.id_almacen = '$idAlmacen'");
		}
		$datos = mysqli_fetch_array($resultado);

		$descripcion = addslashes($datos['descripcion']);
		if($datos['inventario'] == '') {
			$inventario = 0;
		} else {
			$inventario = $datos['inventario'];
		}

		if($datos['moneda'] == '') {
			$moneda = 1;
		} else {
			$moneda = $datos['moneda'];
		}

		if(!$datos['precio1']) {
			$precio1 = 0;
		} else {
			$precio1 = $datos['precio1'];
		}
		?>
		
		<script>
		var checado = $('#envioDirecto').is(":checked");
		var inventario = <?php echo $inventario; ?>;
		var precio1 = <?php echo $precio1; ?>;

		if (inventario == 0 && checado == 0) {
			$('#noParte<?php echo $_GET['fila']; ?>').val('');
			new $.Zebra_Dialog('No hay existencias del número de parte <strong><?php echo $id; ?></strong> en el almacén.', {
			    	'buttons':  false,
				    'modal': false,
				    'auto_close': 2000
			});
		} else {
		$('#editarParte<?php echo $_GET['fila']; ?>').attr('target', '_blank');
		$('#editarParte<?php echo $_GET['fila']; ?>').attr('href', '<?php echo STASIS; ?>/catalogos/partes/modificar/<?php echo $datos['id']; ?>');
		$('#idParte<?php echo $_GET['fila']; ?>').val('<?php echo $datos['id']; ?>');
		// Se determina la moneda elegida y el tipo de cambio
		var monedaElegida = $('#cotizacion-moneda').val();
		if (!monedaElegida) {
			var monedaElegida = $('#prefactura-moneda').val();
		}
		var monedaParte = <?php echo $moneda; ?>;
		var tipoCambio = parseFloat($('#tipo-cambio').val());
		// Elegida = PESOS | Parte = PESOS
		if (monedaElegida == 1 && monedaParte == 1) {
			$('#precio-escondido<?php echo $_GET['fila']; ?>').val(<?php echo $precio1; ?>);
			$('#precio<?php echo $_GET['fila']; ?>').val(<?php echo $precio1; ?>);
		}
		// Elegida = DOLARES | Parte = PESOS
		if (monedaElegida == 2 && monedaParte == 1) {
			var diferencia = parseFloat(<?php echo $precio1; ?>/tipoCambio);
			var redondeado = diferencia.toFixed(2);
			$('#precio-escondido<?php echo $_GET['fila']; ?>').val(redondeado);
			$('#precio<?php echo $_GET['fila']; ?>').val(redondeado);
		}
		// Elegida = DOLARES | Parte = DOLARES
		if (monedaElegida == 2 && monedaParte == 2) {
			$('#precio-escondido<?php echo $_GET['fila']; ?>').val(<?php echo $precio1; ?>);
			$('#precio<?php echo $_GET['fila']; ?>').val(<?php echo $precio1; ?>);
		}
		// Elegida = PESOS | Parte = DOLARES
		if (monedaElegida == 1 && monedaParte == 2) {
			var diferencia = parseFloat(<?php echo $precio1; ?>*tipoCambio);
			var redondeado = diferencia.toFixed(2);
			$('#precio-escondido<?php echo $_GET['fila']; ?>').val(redondeado);
			$('#precio<?php echo $_GET['fila']; ?>').val(redondeado);
		}
		$('#descripcion<?php echo $_GET['fila']; ?>').val("<?php echo $descripcion; ?>");
		$('#um<?php echo $_GET['fila']; ?>').val('<?php echo $datos['um']; ?>');
		$('#inventario<?php echo $_GET['fila']; ?>').val('<?php echo $inventario; ?>');
		$('#claveProdServ<?php echo $_GET['fila']; ?>').val('<?php echo $datos['clave_prodserv']; ?>');
		$('#claveUnidad<?php echo $_GET['fila']; ?>').val('<?php echo $datos['clave_unidad']; ?>');
		}
		</script>
		<?php
		break;

		// No Parte Invoice
		case 'noparteinvoice':
		$id = urldecode($_GET['valor']);

		$resultado = mysqli_query($dbh, "SELECT p.id, p.descripcion, p.moneda_venta AS moneda, m.nombre AS um, p.precio1, p.clave_prodserv, p.clave_unidad, ic.total AS inventario
			FROM partes p
			LEFT JOIN partes_medidas m
			ON p.id_unidad_venta = m.id
			LEFT JOIN inventario_cedis ic
			ON ic.id_parte = p.id
			WHERE codigo = '$id' AND status = 1 AND ic.id_almacen = 1
			LIMIT 1");
		$datos = mysqli_fetch_array($resultado);

		if ($datos) {
			$descripcion = addslashes($datos['descripcion']);
			if($datos['inventario'] == '') {
				$inventario = 0;
			} else {
				$inventario = $datos['inventario'];
			}
			$moneda = $datos['moneda'];
			?>
			
			<script>
			var inventario = <?php echo $inventario; ?>;
			if (inventario == 0) {
				$('#noParte<?php echo $_GET['fila']; ?>').val('');
				new $.Zebra_Dialog('No hay existencias del número de parte <strong><?php echo $id; ?></strong> en el almacén.', {
				    	'buttons':  false,
					    'modal': false,
					    'auto_close': 2000
				});
			} else {
			$('#editarParte<?php echo $_GET['fila']; ?>').attr('target', '_blank');
			$('#editarParte<?php echo $_GET['fila']; ?>').attr('href', '<?php echo STASIS; ?>/catalogos/partes/modificar/<?php echo $datos['id']; ?>');
			$('#idParte<?php echo $_GET['fila']; ?>').val('<?php echo $datos['id']; ?>');
			// Se determina la moneda elegida y el tipo de cambio
			var monedaElegida = 2;
			var monedaParte = <?php echo $moneda; ?>;
			var tipoCambio = parseFloat($('#tipo-cambio').val());
			// Elegida = PESOS | Parte = PESOS
			if (monedaElegida == 1 && monedaParte == 1) {
				$('#precio-escondido<?php echo $_GET['fila']; ?>').val(<?php echo $datos['precio1']; ?>);
				$('#precio<?php echo $_GET['fila']; ?>').val(<?php echo $datos['precio1']; ?>);
			}
			// Elegida = DOLARES | Parte = PESOS
			if (monedaElegida == 2 && monedaParte == 1) {
				var diferencia = parseFloat(<?php echo $datos['precio1']; ?>/tipoCambio);
				var redondeado = diferencia.toFixed(2);
				$('#precio-escondido<?php echo $_GET['fila']; ?>').val(redondeado);
				$('#precio<?php echo $_GET['fila']; ?>').val(redondeado);
			}
			// Elegida = DOLARES | Parte = DOLARES
			if (monedaElegida == 2 && monedaParte == 2) {
				$('#precio-escondido<?php echo $_GET['fila']; ?>').val(<?php echo $datos['precio1']; ?>);
				$('#precio<?php echo $_GET['fila']; ?>').val(<?php echo $datos['precio1']; ?>);
			}
			// Elegida = PESOS | Parte = DOLARES
			if (monedaElegida == 1 && monedaParte == 2) {
				var diferencia = parseFloat(<?php echo $datos['precio1']; ?>*tipoCambio);
				var redondeado = diferencia.toFixed(2);
				$('#precio-escondido<?php echo $_GET['fila']; ?>').val(redondeado);
				$('#precio<?php echo $_GET['fila']; ?>').val(redondeado);
			}
			$('#descripcion<?php echo $_GET['fila']; ?>').val("<?php echo $descripcion; ?>");
			$('#um<?php echo $_GET['fila']; ?>').val('<?php echo $datos['um']; ?>');
			$('#inventario<?php echo $_GET['fila']; ?>').val('<?php echo $inventario; ?>');
			$('#claveProdServ<?php echo $_GET['fila']; ?>').val('<?php echo $datos['clave_prodserv']; ?>');
			$('#claveUnidad<?php echo $_GET['fila']; ?>').val('<?php echo $datos['clave_unidad']; ?>');
			}
			</script>
			<?php
		} else {
			?>
			<script>
			$('#noParte<?php echo $_GET['fila']; ?>').val('');
			new $.Zebra_Dialog('No hay existencias del número de parte <strong><?php echo $id; ?></strong> en el almacén.', {
			    	'buttons':  false,
				    'modal': false,
				    'auto_close': 2000
			});
			</script>
			<?php
		}
		break;

		// No Parte (Para vender)
		case 'nopartecotizacion':
		$id = urldecode($_GET['valor']);
		$resultado = mysqli_query($dbh, "SELECT p.id, p.descripcion, p.moneda_venta AS moneda, m.nombre AS um, p.precio1, p.clave_prodserv, p.clave_unidad, ic.total AS inventario
			FROM partes p
			LEFT JOIN partes_medidas m
			ON p.id_unidad_venta = m.id
			LEFT JOIN inventario_cedis ic
			ON ic.id_parte = p.id
			WHERE codigo = '$id' AND status = 1
			LIMIT 1");
		$datos = mysqli_fetch_array($resultado);
		$descripcion = addslashes($datos['descripcion']);
		if($datos['inventario'] == '') {
			$inventario = 0;
		} else {
			$inventario = $datos['inventario'];
		}
		$moneda = $datos['moneda'];
		?>
		
		<script>
		$('#editarParte<?php echo $_GET['fila']; ?>').attr('target', '_blank');
		$('#editarParte<?php echo $_GET['fila']; ?>').attr('href', '<?php echo STASIS; ?>/catalogos/partes/modificar/<?php echo $datos['id']; ?>');
		$('#idParte<?php echo $_GET['fila']; ?>').val('<?php echo $datos['id']; ?>');
		// Se determina la moneda elegida y el tipo de cambio
		var monedaElegida = $('#cotizacion-moneda').val();
		if (!monedaElegida) {
			var monedaElegida = $('#prefactura-moneda').val();
		}
		var monedaParte = <?php echo $moneda; ?>;
		var tipoCambio = parseFloat($('#tipo-cambio').val());
		// Elegida = PESOS | Parte = PESOS
		if (monedaElegida == 1 && monedaParte == 1) {
			$('#precio-escondido<?php echo $_GET['fila']; ?>').val(<?php echo $datos['precio1']; ?>);
			$('#precio<?php echo $_GET['fila']; ?>').val(<?php echo $datos['precio1']; ?>);
		}
		// Elegida = DOLARES | Parte = PESOS
		if (monedaElegida == 2 && monedaParte == 1) {
			var diferencia = parseFloat(<?php echo $datos['precio1']; ?>/tipoCambio);
			var redondeado = diferencia.toFixed(2);
			$('#precio-escondido<?php echo $_GET['fila']; ?>').val(redondeado);
			$('#precio<?php echo $_GET['fila']; ?>').val(redondeado);
		}
		// Elegida = DOLARES | Parte = DOLARES
		if (monedaElegida == 2 && monedaParte == 2) {
			$('#precio-escondido<?php echo $_GET['fila']; ?>').val(<?php echo $datos['precio1']; ?>);
			$('#precio<?php echo $_GET['fila']; ?>').val(<?php echo $datos['precio1']; ?>);
		}
		// Elegida = PESOS | Parte = DOLARES
		if (monedaElegida == 1 && monedaParte == 2) {
			var diferencia = parseFloat(<?php echo $datos['precio1']; ?>*tipoCambio);
			var redondeado = diferencia.toFixed(2);
			$('#precio-escondido<?php echo $_GET['fila']; ?>').val(redondeado);
			$('#precio<?php echo $_GET['fila']; ?>').val(redondeado);
		}
		$('#descripcion<?php echo $_GET['fila']; ?>').val("<?php echo $descripcion; ?>");
		$('#um<?php echo $_GET['fila']; ?>').val('<?php echo $datos['um']; ?>');
		$('#inventario<?php echo $_GET['fila']; ?>').val('<?php echo $inventario; ?>');
		$('#claveProdServ<?php echo $_GET['fila']; ?>').val('<?php echo $datos['clave_prodserv']; ?>');
		$('#claveUnidad<?php echo $_GET['fila']; ?>').val('<?php echo $datos['clave_unidad']; ?>');
		</script>
		<?php
		break;

		// No Parte (Para comprar)
		case 'nopartecompra':
		$id = $_GET['valor'];
		$resultado = mysqli_query($dbh, "
			SELECT p.id, p.descripcion_ingles AS descripcion, p.moneda, m.nombre AS um, ic.total AS inventario
			FROM partes p
			LEFT JOIN partes_medidas m
			ON p.id_unidad_compra = m.id
			LEFT JOIN inventario_cedis ic
			ON ic.id_parte = p.id
			WHERE codigo = '$id'
			AND status = 1
			LIMIT 1");
		$datos = mysqli_fetch_array($resultado);
		$descripcion = addslashes($datos['descripcion']);
		$moneda = $datos['moneda'];

		if($datos['inventario'] == '') {
			$inventario = 0;
		} else {
			$inventario = $datos['inventario'];
		}
		?>
		
		<script>
		$('#editarParte<?php echo $_GET['fila']; ?>').attr('target', '_blank');
		$('#editarParte<?php echo $_GET['fila']; ?>').attr('href', '<?php echo STASIS; ?>/catalogos/partes/modificar/<?php echo $datos['id']; ?>');
		$('#idParte<?php echo $_GET['fila']; ?>').val('<?php echo $datos['id']; ?>');
		$('#inventario<?php echo $_GET['fila']; ?>').val("<?php echo $inventario; ?>");
		$('#descripcion<?php echo $_GET['fila']; ?>').val("<?php echo $descripcion; ?>");
		$('#um<?php echo $_GET['fila']; ?>').val('<?php echo $datos['um']; ?>');
		</script>
		<?php
		$resultado = mysqli_query($dbh, "SELECT p.proveedor1, p.proveedor_costo1, p.proveedor2, p.proveedor_costo2, p.proveedor3, p.proveedor_costo3 FROM partes p WHERE codigo = '$id'");
		$datos = mysqli_fetch_array($resultado);
		$datos["proveedor1"] == ''? $proveedor[1] = 'NO ESPECIFICADO' : $proveedor[1] = $datos["proveedor1"];
		$datos["proveedor2"] == ''? $proveedor[2] = 'NO ESPECIFICADO' : $proveedor[2] = $datos["proveedor2"];
		$datos["proveedor3"] == ''? $proveedor[3] = 'NO ESPECIFICADO' : $proveedor[3] = $datos["proveedor3"];
		?>
		<script>
		// Se determina la moneda elegida y el tipo de cambio
		var monedaElegida = $('#cotizacion-moneda').val();
		var monedaParte = <?php echo $moneda; ?>;
		var tipoCambio = parseFloat($('#tipo-cambio').val());
		$('#proveedor<?php echo $_GET['fila']; ?>').empty();
		$('#proveedor<?php echo $_GET['fila']; ?>').removeAttr('disabled');
		// Elegida = PESOS | Parte = PESOS
		if (monedaElegida == 1 && monedaParte == 1) {
			<?php
			$proveedores = array();
			if ($datos['proveedor1'] != '') {
				$proveedores[] = array(
					$datos['proveedor_costo1'],
					$proveedor[1],
					'[' . $proveedor[1] . '] - [$' . $datos['proveedor_costo1'] . ' PESOS]',
					1
				);
			}
			if ($datos['proveedor2'] != '') {
				$proveedores[] = array(
					$datos['proveedor_costo2'],
					$proveedor[2],
					'[' . $proveedor[2] . '] - [$' . $datos['proveedor_costo2'] . ' PESOS]',
					2
				);
			}
			if ($datos['proveedor3'] != '') {
				$proveedores[] = array(
					$datos['proveedor_costo3'],
					$proveedor[3],
					'[' . $proveedor[3] . '] - [$' . $datos['proveedor_costo3'] . ' PESOS]',
					3
				);
			}
			sort($proveedores);
			$proveedoresOpciones = '';
			foreach($proveedores as $proveedorDatos) {
				$proveedoresOpciones .= '<option id="proveedor' . $_GET['fila'] . '-costo-' . $proveedorDatos[3] . '" value="' . $proveedorDatos[1] . '" data-costo="' . $proveedorDatos[0] . '" data-numero="' . $proveedorDatos[3] . '">' . $proveedorDatos[2] . '</option>';
			}
			?>
			$('#proveedor<?php echo $_GET['fila']; ?>').append('<option value="">Seleccionar proveedor...</option><?php echo $proveedoresOpciones; ?>');
		}
		// Elegida = DOLARES | Parte = PESOS
		if (monedaElegida == 2 && monedaParte == 1) {
			<?php
			$tipoCambio = $_GET['tipocambio'];
			$proveedores = array();
			if ($datos['proveedor1'] != '') {
				$costo1 = number_format((float)$datos['proveedor_costo1']/$tipoCambio, 6, '.', '');
				$proveedores[] = array(
					$costo1,
					$proveedor[1],
					'[' . $proveedor[1] . '] - [$' . $costo1 . ' DLLS]',
					1
				);
			}
			if ($datos['proveedor2'] != '') {
				$costo2 = number_format((float)$datos['proveedor_costo2']/$tipoCambio, 6, '.', '');
				$proveedores[] = array(
					$costo2,
					$proveedor[2],
					'[' . $proveedor[2] . '] - [$' . $costo2 . ' DLLS]',
					2
				);
			}
			if ($datos['proveedor3'] != '') {
				$costo3 = number_format((float)$datos['proveedor_costo3']/$tipoCambio, 6, '.', '');
				$proveedores[] = array(
					$costo3,
					$proveedor[3],
					'[' . $proveedor[3] . '] - [$' . $costo3 . ' DLLS]',
					3
				);
			}
			sort($proveedores);
			$proveedoresOpciones = '';
			foreach($proveedores as $proveedorDatos) {
				$proveedoresOpciones .= '<option id="proveedor' . $_GET['fila'] . '-costo-' . $proveedorDatos[3] . '" value="' . $proveedorDatos[1] . '" data-costo="' . $proveedorDatos[0] . '" data-numero="' . $proveedorDatos[3] . '">' . $proveedorDatos[2] . '</option>';
			}
			?>
			$('#proveedor<?php echo $_GET['fila']; ?>').append('<option value="">Seleccionar proveedor...</option><?php echo $proveedoresOpciones; ?>');
		}
		// Elegida = DOLARES | Parte = DOLARES
		if (monedaElegida == 2 && monedaParte == 2) {
			<?php
			$proveedores = array();
			if ($datos['proveedor1'] != '') {
				$proveedores[] = array(
					$datos['proveedor_costo1'],
					$proveedor[1],
					'[' . $proveedor[1] . '] - [$' . $datos['proveedor_costo1'] . ' DLLS]',
					1
				);
			}
			if ($datos['proveedor2'] != '') {
				$proveedores[] = array(
					$datos['proveedor_costo2'],
					$proveedor[2],
					'[' . $proveedor[2] . '] - [$' . $datos['proveedor_costo2'] . ' DLLS]',
					2
				);
			}
			if ($datos['proveedor3'] != '') {
				$proveedores[] = array(
					$datos['proveedor_costo3'],
					$proveedor[3],
					'[' . $proveedor[3] . '] - [$' . $datos['proveedor_costo3'] . ' DLLS]',
					3
				);
			}
			sort($proveedores);
			$proveedoresOpciones = '';
			foreach($proveedores as $proveedorDatos) {
				$proveedoresOpciones .= '<option id="proveedor' . $_GET['fila'] . '-costo-' . $proveedorDatos[3] . '" value="' . $proveedorDatos[1] . '" data-costo="' . $proveedorDatos[0] . '" data-numero="' . $proveedorDatos[3] . '">' . $proveedorDatos[2] . '</option>';
			}
			?>
			$('#proveedor<?php echo $_GET['fila']; ?>').append('<option value="">Seleccionar proveedor...</option><?php echo $proveedoresOpciones; ?>');
		}
		// Elegida = PESOS | Parte = DOLARES
		if (monedaElegida == 1 && monedaParte == 2) {
			<?php
			$tipoCambio = $_GET['tipocambio'];
			$proveedores = array();
			if ($datos['proveedor1'] != '') {
				$costo1 = number_format((float)$datos['proveedor_costo1']*$tipoCambio, 6, '.', '');
				$proveedores[] = array(
					$costo1,
					$proveedor[1],
					'[' . $proveedor[1] . '] - [$' . $costo1 . ' PESOS]',
					1
				);
			}
			if ($datos['proveedor2'] != '') {
				$costo2 = number_format((float)$datos['proveedor_costo2']*$tipoCambio, 6, '.', '');
				$proveedores[] = array(
					$costo2,
					$proveedor[2],
					'[' . $proveedor[2] . '] - [$' . $costo2 . ' PESOS]',
					2
				);
			}
			if ($datos['proveedor3'] != '') {
				$costo3 = number_format((float)$datos['proveedor_costo3']*$tipoCambio, 6, '.', '');
				$proveedores[] = array(
					$costo3,
					$proveedor[3],
					'[' . $proveedor[3] . '] - [$' . $costo3 . ' PESOS]',
					3
				);
			}
			sort($proveedores);
			$proveedoresOpciones = '';
			foreach($proveedores as $proveedorDatos) {
				$proveedoresOpciones .= '<option id="proveedor' . $_GET['fila'] . '-costo-' . $proveedorDatos[3] . '" value="' . $proveedorDatos[1] . '" data-costo="' . $proveedorDatos[0] . '" data-numero="' . $proveedorDatos[3] . '">' . $proveedorDatos[2] . '</option>';
			}
			?>
			$('#proveedor<?php echo $_GET['fila']; ?>').append('<option value="">Seleccionar proveedor...</option><?php echo $proveedoresOpciones; ?>');
		}
		</script>
		<?php
		break;
		// No Parte (Para comprar)
		case 'nopartecomprausa':
		$id = $_GET['valor'];
		$resultado = mysqli_query($dbh, "
			SELECT p.id, p.descripcion_ingles AS descripcion, p.moneda, m.nombre AS um, ic.total AS inventario
			FROM partes p
			LEFT JOIN partes_medidas m
			ON p.id_unidad_compra = m.id
			LEFT JOIN inventario_cedis ic
			ON ic.id_parte = p.id
			WHERE codigo = '$id'
			AND status = 1
			LIMIT 1");
		$datos = mysqli_fetch_array($resultado);
		$descripcion = addslashes($datos['descripcion']);
		$moneda = $datos['moneda'];

		if($datos['inventario'] == '') {
			$inventario = 0;
		} else {
			$inventario = $datos['inventario'];
		}

		?>
		
		<script>
		$('#editarParte<?php echo $_GET['fila']; ?>').attr('target', '_blank');
		$('#editarParte<?php echo $_GET['fila']; ?>').attr('href', '<?php echo STASIS; ?>/catalogos/partes/modificar/<?php echo $datos['id']; ?>');
		$('#idParte<?php echo $_GET['fila']; ?>').val('<?php echo $datos['id']; ?>');
		$('#descripcion<?php echo $_GET['fila']; ?>').val("<?php echo $descripcion; ?>");
		$('#inventario<?php echo $_GET['fila']; ?>').val("<?php echo $inventario; ?>");
		$('#um<?php echo $_GET['fila']; ?>').val('<?php echo $datos['um']; ?>');
		</script>
		<?php
		$resultado = mysqli_query($dbh, "SELECT p.proveedor1, p.proveedor_costo1, p.proveedor2, p.proveedor_costo2, p.proveedor3, p.proveedor_costo3 FROM partes p WHERE codigo = '$id'");
		$datos = mysqli_fetch_array($resultado);
		$datos["proveedor1"] == ''? $proveedor[1] = 'NO ESPECIFICADO' : $proveedor[1] = $datos["proveedor1"];
		$datos["proveedor2"] == ''? $proveedor[2] = 'NO ESPECIFICADO' : $proveedor[2] = $datos["proveedor2"];
		$datos["proveedor3"] == ''? $proveedor[3] = 'NO ESPECIFICADO' : $proveedor[3] = $datos["proveedor3"];
		?>
		<script>
		// Se determina la moneda elegida y el tipo de cambio
		var monedaElegida = $('#cotizacion-moneda').val();
		var monedaParte = <?php echo $moneda; ?>;
		var tipoCambio = parseFloat($('#tipo-cambio').val());
		$('#proveedor<?php echo $_GET['fila']; ?>').empty();
		$('#proveedor<?php echo $_GET['fila']; ?>').removeAttr('disabled');
		// Elegida = PESOS | Parte = PESOS
		if (monedaElegida == 1 && monedaParte == 1) {
			<?php
			$proveedores = array();
			if ($datos['proveedor1'] != '') {
				$proveedores[] = array(
					$datos['proveedor_costo1'],
					$proveedor[1],
					'[' . $proveedor[1] . '] - [$' . $datos['proveedor_costo1'] . ' PESOS]',
					1
				);
			}
			if ($datos['proveedor2'] != '') {
				$proveedores[] = array(
					$datos['proveedor_costo2'],
					$proveedor[2],
					'[' . $proveedor[2] . '] - [$' . $datos['proveedor_costo2'] . ' PESOS]',
					2
				);
			}
			if ($datos['proveedor3'] != '') {
				$proveedores[] = array(
					$datos['proveedor_costo3'],
					$proveedor[3],
					'[' . $proveedor[3] . '] - [$' . $datos['proveedor_costo3'] . ' PESOS]',
					3
				);
			}
			sort($proveedores);
			$proveedoresOpciones = '';
			foreach($proveedores as $proveedorDatos) {
				$proveedoresOpciones .= '<option id="proveedor' . $_GET['fila'] . '-costo-' . $proveedorDatos[3] . '" value="' . $proveedorDatos[1] . '" data-costo="' . $proveedorDatos[0] . '" data-numero="' . $proveedorDatos[3] . '">' . $proveedorDatos[2] . '</option>';
			}
			?>
			$('#proveedor<?php echo $_GET['fila']; ?>').append('<option value="">Seleccionar proveedor...</option><?php echo $proveedoresOpciones; ?>');
		}
		// Elegida = DOLARES | Parte = PESOS
		if (monedaElegida == 2 && monedaParte == 1) {
			<?php
			$tipoCambio = $_GET['tipocambio'];
			$proveedores = array();
			if ($datos['proveedor1'] != '') {
				$costo1 = number_format((float)$datos['proveedor_costo1']/$tipoCambio, 6, '.', '');
				$proveedores[] = array(
					$costo1,
					$proveedor[1],
					'[' . $proveedor[1] . '] - [$' . $costo1 . ' DLLS]',
					1
				);
			}
			if ($datos['proveedor2'] != '') {
				$costo2 = number_format((float)$datos['proveedor_costo2']/$tipoCambio, 6, '.', '');
				$proveedores[] = array(
					$costo2,
					$proveedor[2],
					'[' . $proveedor[2] . '] - [$' . $costo2 . ' DLLS]',
					2
				);
			}
			if ($datos['proveedor3'] != '') {
				$costo3 = number_format((float)$datos['proveedor_costo3']/$tipoCambio, 6, '.', '');
				$proveedores[] = array(
					$costo3,
					$proveedor[3],
					'[' . $proveedor[3] . '] - [$' . $costo3 . ' DLLS]',
					3
				);
			}
			sort($proveedores);
			$proveedoresOpciones = '';
			foreach($proveedores as $proveedorDatos) {
				$proveedoresOpciones .= '<option id="proveedor' . $_GET['fila'] . '-costo-' . $proveedorDatos[3] . '" value="' . $proveedorDatos[1] . '" data-costo="' . $proveedorDatos[0] . '" data-numero="' . $proveedorDatos[3] . '">' . $proveedorDatos[2] . '</option>';
			}
			?>
			$('#proveedor<?php echo $_GET['fila']; ?>').append('<option value="">Seleccionar proveedor...</option><?php echo $proveedoresOpciones; ?>');
		}
		// Elegida = DOLARES | Parte = DOLARES
		if (monedaElegida == 2 && monedaParte == 2) {
			<?php
			$proveedores = array();
			if ($datos['proveedor1'] != '') {
				$proveedores[] = array(
					$datos['proveedor_costo1'],
					$proveedor[1],
					'[' . $proveedor[1] . '] - [$' . $datos['proveedor_costo1'] . ' DLLS]',
					1
				);
			}
			if ($datos['proveedor2'] != '') {
				$proveedores[] = array(
					$datos['proveedor_costo2'],
					$proveedor[2],
					'[' . $proveedor[2] . '] - [$' . $datos['proveedor_costo2'] . ' DLLS]',
					2
				);
			}
			if ($datos['proveedor3'] != '') {
				$proveedores[] = array(
					$datos['proveedor_costo3'],
					$proveedor[3],
					'[' . $proveedor[3] . '] - [$' . $datos['proveedor_costo3'] . ' DLLS]',
					3
				);
			}
			sort($proveedores);
			$proveedoresOpciones = '';
			foreach($proveedores as $proveedorDatos) {
				$proveedoresOpciones .= '<option id="proveedor' . $_GET['fila'] . '-costo-' . $proveedorDatos[3] . '" value="' . $proveedorDatos[1] . '" data-costo="' . $proveedorDatos[0] . '" data-numero="' . $proveedorDatos[3] . '">' . $proveedorDatos[2] . '</option>';
			}
			?>
			$('#proveedor<?php echo $_GET['fila']; ?>').append('<option value="">Seleccionar proveedor...</option><?php echo $proveedoresOpciones; ?>');
		}
		// Elegida = PESOS | Parte = DOLARES
		if (monedaElegida == 1 && monedaParte == 2) {
			<?php
			$tipoCambio = $_GET['tipocambio'];
			$proveedores = array();
			if ($datos['proveedor1'] != '') {
				$costo1 = number_format((float)$datos['proveedor_costo1']*$tipoCambio, 6, '.', '');
				$proveedores[] = array(
					$costo1,
					$proveedor[1],
					'[' . $proveedor[1] . '] - [$' . $costo1 . ' PESOS]',
					1
				);
			}
			if ($datos['proveedor2'] != '') {
				$costo2 = number_format((float)$datos['proveedor_costo2']*$tipoCambio, 6, '.', '');
				$proveedores[] = array(
					$costo2,
					$proveedor[2],
					'[' . $proveedor[2] . '] - [$' . $costo2 . ' PESOS]',
					2
				);
			}
			if ($datos['proveedor3'] != '') {
				$costo3 = number_format((float)$datos['proveedor_costo3']*$tipoCambio, 6, '.', '');
				$proveedores[] = array(
					$costo3,
					$proveedor[3],
					'[' . $proveedor[3] . '] - [$' . $costo3 . ' PESOS]',
					3
				);
			}
			sort($proveedores);
			$proveedoresOpciones = '';
			foreach($proveedores as $proveedorDatos) {
				$proveedoresOpciones .= '<option id="proveedor' . $_GET['fila'] . '-costo-' . $proveedorDatos[3] . '" value="' . $proveedorDatos[1] . '" data-costo="' . $proveedorDatos[0] . '" data-numero="' . $proveedorDatos[3] . '">' . $proveedorDatos[2] . '</option>';
			}
			?>
			$('#proveedor<?php echo $_GET['fila']; ?>').append('<option value="">Seleccionar proveedor...</option><?php echo $proveedoresOpciones; ?>');
		}
		</script>
		<?php
		break;
		// No Parte (Para traspasar)
		case 'noParteTraspasoLocal':
		$idEjercicio = date('y');
		$mesActual = date('n');
		$id = $_GET['valor'];
		$idIntegracion = $_GET['idIntegracion'];
		// $infoParte = explode("|", $data);
		// $id = $infoParte[0];
		// $idIntegracion = $infoParte[1];
		if ($idIntegracion == 0) {
			$resultado = mysqli_query($dbh, "SELECT p.id, p.codigo, p.descripcion, m.nombre AS um, IFNULL(pe.entradas_p$mesActual, 0) - IFNULL(pe.salidas_p$mesActual, 0) AS existencia
			FROM partes p
			LEFT JOIN partes_medidas m
			ON p.id_unidad_compra = m.id
			JOIN partes_existencias pe
			ON pe.id_producto = p.id
			WHERE p.id = '$id' AND pe.id_almacen = 1 AND pe.id_ejercicio = $idEjercicio AND p.status = 1
			LIMIT 1");
			$datos = mysqli_fetch_array($resultado);
			$descripcion = addslashes($datos['descripcion']);
			// if ($datos['existencia'] != '') {
			?>
			<script>
			$('#idParte<?php echo $_GET['fila']; ?>').val('<?php echo $datos['id']; ?>');
			$('#idIntegracion<?php echo $_GET['fila']; ?>').val('0');
			$('#descripcion<?php echo $_GET['fila']; ?>').val("<?php echo $descripcion; ?>");
			$('#um<?php echo $_GET['fila']; ?>').val('<?php echo $datos['um']; ?>');
			$('#existencia<?php echo $_GET['fila']; ?>').val('<?php echo $datos['existencia']; ?>');
			</script>
			<?php
			// } else {
			// <script>
			// $('#noParte<?php echo $_GET['fila']; ').val('');
			// new $.Zebra_Dialog('No hay existencias del número de parte <strong><?php echo $id; </strong> en el almacen.', {
			// 	    'buttons':  false,
			// 	    'modal': false,
			// 	    'auto_close': 2000
			// 	});
			// </script>
			?>
			<?php
			// }
		} else {
			$resultado = mysqli_query($dbh, "SELECT p.id, p.id_parte_xpress, p.descripcion, p.uom_compra, p.id_integracion, IFNULL(pe.entradas_p$mesActual, 0) - IFNULL(pe.salidas_p$mesActual, 0) AS existencia
			FROM baselines p
			JOIN partes_existencias pe
			ON pe.id_producto = p.id
			WHERE p.id = '$id' AND pe.id_almacen = 1 AND pe.id_ejercicio = $idEjercicio AND p.status = 1 AND pe.id_integracion = $idIntegracion
			LIMIT 1");
			$datos = mysqli_fetch_array($resultado);
			$descripcion = addslashes($datos['descripcion']);
			$idIntegracion = $datos['id_integracion'];
			// if ($datos['existencia'] != '') {
			?>
			<script>
			$('#idParte<?php echo $_GET['fila']; ?>').val('<?php echo $datos['id']; ?>');
			$('#idIntegracion<?php echo $_GET['fila']; ?>').val('<?php echo $idIntegracion; ?>');
			$('#descripcion<?php echo $_GET['fila']; ?>').val("<?php echo $descripcion; ?>");
			$('#um<?php echo $_GET['fila']; ?>').val('<?php echo $datos['uom_compra']; ?>');
			$('#existencia<?php echo $_GET['fila']; ?>').val('<?php echo $datos['existencia']; ?>');
			</script>
			<?php
			// } else {
			// $('#noParte<?php echo $_GET['fila']; ').val('');
			// new $.Zebra_Dialog('No hay existencias del número de parte <strong><?php echo $id; </strong> en el almacen.', {
			// 	    'buttons':  false,
			// 	    'modal': false,
			// 	    'auto_close': 2000
			// 	});
			// </script>
			?>
			<script>
			<?php
			// }
		}
		break;
		// No Parte para ajustes locales de matriz
		case 'noParteAjusteMatriz':
		
		$id = urldecode($_GET['valor']);
		$idIntegracion = $_GET['idIntegracion'];
		$idAlmacen = $_GET['idAlmacen'];

		$resultado = mysqli_query($dbh, "SELECT p.id, p.descripcion, p.moneda_venta AS moneda, m.nombre AS um, p.precio1 FROM partes p LEFT JOIN partes_medidas m ON p.id_unidad_venta = m.id WHERE p.id = '$id' AND status = 1 LIMIT 1");
		$datos = mysqli_fetch_array($resultado);
		$descripcion = addslashes($datos['descripcion']);
		$resultado2 = mysqli_query($dbh, "SELECT SUM(total) AS conteo FROM inventario_cedis WHERE id_parte = '$id' AND id_almacen = '$idAlmacen' LIMIT 1");
		$datos2 = mysqli_fetch_array($resultado2);
		$totalInventario = $datos2['conteo'];
		?>
		
		<script>
		$('#idParte<?php echo $_GET['fila']; ?>').val('<?php echo $datos['id']; ?>');
		$('#descripcion<?php echo $_GET['fila']; ?>').val("<?php echo $descripcion; ?>");
		$('#um<?php echo $_GET['fila']; ?>').val('<?php echo $datos['um']; ?>');
		$('#existencia<?php echo $_GET['fila']; ?>').val('<?php echo $totalInventario; ?>');
		</script>
		<?php
		break;
		// Listado de maquinas
		case 'maquina':
		$id = urldecode($_GET['valor']);
		$resultado = mysqli_query($dbh, "SELECT codigo FROM centros_costo WHERE id = '$id' LIMIT 1");
		$dato = mysqli_fetch_array($resultado);
		$idCentroCosto = $dato['codigo'];
		$resultado = mysqli_query($dbh, "SELECT id, maquina FROM maquinas WHERE cc = '$idCentroCosto' ORDER BY maquina DESC");
		$html = '<option value="">Seleccionar...</option>';
		while ($datos = mysqli_fetch_array($resultado)) {
			$html .= '<option value="' . $datos['id'] . '">' . $datos['maquina'] . '</option>';
		}
		echo $html;
		break;
		// No Parte (Para traspasar entre almacenes de sitio)
		case 'noParteTraspasoSitio':
		$idEjercicio = date('y');
		$mesActual = date('n');
		$idParte = $_GET['valor'];
		$idAlmacenOrigen = $_GET['idAlmacenOrigen'];
		$idIntegracion = $_GET['idIntegracion'];
		$resultado = mysqli_query($dbh, "SELECT p.id, p.id_parte_xpress, p.descripcion, p.uom_venta, p.id_integracion, IFNULL(pe.entradas_p$mesActual, 0) - IFNULL(pe.salidas_p$mesActual, 0) AS existencia
		FROM baselines p
		JOIN partes_existencias pe
		ON pe.id_producto = p.id
		WHERE p.id = '$idParte' AND pe.id_almacen = $idAlmacenOrigen AND pe.id_ejercicio = $idEjercicio AND p.status = 1 AND pe.id_integracion = $idIntegracion
		LIMIT 1");
		$conteo = mysqli_num_rows($resultado);
		//Ver si el numero de parte existe en las existencias
		if ($conteo == 0) {
			// Agregar a la tabla de existencias
			mysqli_query($dbh, "INSERT INTO partes_existencias (id_almacen, id_producto, id_ejercicio, entradas_p$mesActual, id_integracion) VALUES ('$idAlmacenOrigen', '$idParte', '$idEjercicio', 0, $idIntegracion)");
			$resultado = mysqli_query($dbh, "SELECT p.id, p.id_parte_xpress, p.descripcion, p.uom_venta, p.id_integracion, IFNULL(pe.entradas_p$mesActual, 0) - IFNULL(pe.salidas_p$mesActual, 0) AS existencia
			FROM baselines p
			JOIN partes_existencias pe
			ON pe.id_producto = p.id
			WHERE p.id = '$idParte' AND pe.id_almacen = $idAlmacenOrigen AND pe.id_ejercicio = $idEjercicio AND p.status = 1 AND pe.id_integracion = $idIntegracion
			LIMIT 1");
		} else {
			$resultado = mysqli_query($dbh, "SELECT p.id, p.id_parte_xpress, p.descripcion, p.uom_venta, p.id_integracion, IFNULL(pe.entradas_p$mesActual, 0) - IFNULL(pe.salidas_p$mesActual, 0) AS existencia
			FROM baselines p
			JOIN partes_existencias pe
			ON pe.id_producto = p.id
			WHERE p.id = '$idParte' AND pe.id_almacen = $idAlmacenOrigen AND pe.id_ejercicio = $idEjercicio AND p.status = 1 AND pe.id_integracion = $idIntegracion
			LIMIT 1");
		}
		$datos = mysqli_fetch_array($resultado);
		$descripcion = addslashes($datos['descripcion']);
		if ($datos['existencia'] != 0) {
		?>
		<script>
		$('#idParte<?php echo $_GET['fila']; ?>').val('<?php echo $datos['id']; ?>');
		$('#descripcion<?php echo $_GET['fila']; ?>').val("<?php echo $descripcion; ?>");
		$('#um<?php echo $_GET['fila']; ?>').val('<?php echo $datos['uom_venta']; ?>');
		$('#existencia<?php echo $_GET['fila']; ?>').val('<?php echo $datos['existencia']; ?>');
		</script>
		<?php
		} else {
		?>
		<script>
		$('#idParte<?php echo $_GET['fila']; ?>').val('<?php echo $datos['id']; ?>');
		$('#descripcion<?php echo $_GET['fila']; ?>').val("<?php echo $descripcion; ?>");
		$('#um<?php echo $_GET['fila']; ?>').val('<?php echo $datos['uom_venta']; ?>');
		$('#existencia<?php echo $_GET['fila']; ?>').val('<?php echo $datos['existencia']; ?>');
		new $.Zebra_Dialog('No hay existencias del número de parte <strong><?php echo $id; ?></strong> en el almacen.', {
			    'buttons':  false,
			    'modal': false,
			    'auto_close': 2000
			});
		</script>
		<?php
		}
		break;
		// Conversion de cotizacion a orden de compra (para select de proveedores)
		case 'conversioncotizacion':
		$id = $_GET['valor'];
		$tipoCambio = $_GET['tipocambio'];
		?>
		
		<script>
		<?php
		$resultado = mysqli_query($dbh, "SELECT p.id AS idParte, p.moneda FROM cotizaciones_partes cp JOIN partes p ON p.id = cp.id_parte WHERE cp.id_cotizacion = '$id' ORDER BY cp.id ASC");
		$x=1;
		$selectProveedores = array();
		while ($datos = mysqli_fetch_array($resultado)) {
			$monedaParte = $datos['moneda'];
			$proveedor = array();
			$idParte = $datos['idParte'];
			$resultado2 = mysqli_query($dbh, "SELECT p.proveedor1, p.proveedor_costo1, p.proveedor2, p.proveedor_costo2, p.proveedor3, p.proveedor_costo3 FROM partes p WHERE id = '$idParte'");
			$datos2 = mysqli_fetch_array($resultado2);
			$datos2["proveedor1"] == ''? $proveedor[1] = 'NO ESPECIFICADO' : $proveedor[1] = $datos2["proveedor1"];
			$datos2["proveedor2"] == ''? $proveedor[2] = 'NO ESPECIFICADO' : $proveedor[2] = $datos2["proveedor2"];
			$datos2["proveedor3"] == ''? $proveedor[3] = 'NO ESPECIFICADO' : $proveedor[3] = $datos2["proveedor3"];
			?>
			// Se determina la moneda elegida y el tipo de cambio
			var monedaElegida = $('#cotizacion-moneda').val();
			var monedaParte = <?php echo $monedaParte; ?>;
			var tipoCambio = parseFloat($('#tipo-cambio').val());
			
			$('#proveedor<?php echo $x; ?>').empty();
			$('#proveedor<?php echo $x; ?>').removeAttr('disabled');
			// Elegida = PESOS | Parte = PESOS
			if (monedaElegida == 1 && monedaParte == 1) {
				<?php
				$proveedores = array();
				if ($datos2['proveedor1'] != '') {
					$proveedores[] = array(
						$datos2['proveedor_costo1'],
						$proveedor[1],
						'[' . $proveedor[1] . '] - [$' . $datos2['proveedor_costo1'] . ' PESOS]',
						1
					);
				}
				if ($datos2['proveedor2'] != '') {
					$proveedores[] = array(
						$datos2['proveedor_costo2'],
						$proveedor[2],
						'[' . $proveedor[2] . '] - [$' . $datos2['proveedor_costo2'] . ' PESOS]',
						2
					);
				}
				if ($datos2['proveedor3'] != '') {
					$proveedores[] = array(
						$datos2['proveedor_costo3'],
						$proveedor[3],
						'[' . $proveedor[3] . '] - [$' . $datos2['proveedor_costo3'] . ' PESOS]',
						3
					);
				}
				sort($proveedores);
				$proveedoresOpciones = '';
				foreach($proveedores as $proveedorDatos) {
					$proveedoresOpciones .= '<option id="proveedor' . $x . '-costo-' . $proveedorDatos[3] . '" value="' . $proveedorDatos[1] . '" data-costo="' . $proveedorDatos[0] . '" data-numero="' . $proveedorDatos[3] . '">' . $proveedorDatos[2] . '</option>';
				}
				?>
				$('#proveedor<?php echo $x; ?>').append('<option value="">Seleccionar proveedor...</option><?php echo $proveedoresOpciones; ?>');
			}
			// Elegida = DOLARES | Parte = PESOS
			if (monedaElegida == 2 && monedaParte == 1) {
				<?php
				$tipoCambio = $_GET['tipocambio'];
				$proveedores = array();
				if ($datos2['proveedor1'] != '') {
					$costo1 = number_format((float)$datos2['proveedor_costo1']/$tipoCambio, 6, '.', '');
					$proveedores[] = array(
						$costo1,
						$proveedor[1],
						'[' . $proveedor[1] . '] - [$' . $costo1 . ' DLLS]',
						1
					);
				}
				if ($datos2['proveedor2'] != '') {
					$costo2 = number_format((float)$datos2['proveedor_costo2']/$tipoCambio, 6, '.', '');
					$proveedores[] = array(
						$costo2,
						$proveedor[2],
						'[' . $proveedor[2] . '] - [$' . $costo2 . ' DLLS]',
						2
					);
				}
				if ($datos2['proveedor3'] != '') {
					$costo3 = number_format((float)$datos2['proveedor_costo3']/$tipoCambio, 6, '.', '');
					$proveedores[] = array(
						$costo3,
						$proveedor[3],
						'[' . $proveedor[3] . '] - [$' . $costo3 . ' DLLS]',
						3
					);
				}
				sort($proveedores);
				$proveedoresOpciones = '';
				foreach($proveedores as $proveedorDatos) {
					$proveedoresOpciones .= '<option id="proveedor' . $x . '-costo-' . $proveedorDatos[3] . '" value="' . $proveedorDatos[1] . '" data-costo="' . $proveedorDatos[0] . '" data-numero="' . $proveedorDatos[3] . '">' . $proveedorDatos[2] . '</option>';
				}
				?>
				$('#proveedor<?php echo $x; ?>').append('<option value="">Seleccionar proveedor...</option><?php echo $proveedoresOpciones; ?>');
			}
			// Elegida = DOLARES | Parte = DOLARES
			if (monedaElegida == 2 && monedaParte == 2) {
				<?php
				$proveedores = array();
				if ($datos2['proveedor1'] != '') {
					$proveedores[] = array(
						$datos2['proveedor_costo1'],
						$proveedor[1],
						'[' . $proveedor[1] . '] - [$' . $datos2['proveedor_costo1'] . ' DLLS]',
						1
					);
				}
				if ($datos2['proveedor2'] != '') {
					$proveedores[] = array(
						$datos2['proveedor_costo2'],
						$proveedor[2],
						'[' . $proveedor[2] . '] - [$' . $datos2['proveedor_costo2'] . ' DLLS]',
						2
					);
				}
				if ($datos2['proveedor3'] != '') {
					$proveedores[] = array(
						$datos2['proveedor_costo3'],
						$proveedor[3],
						'[' . $proveedor[3] . '] - [$' . $datos2['proveedor_costo3'] . ' DLLS]',
						3
					);
				}
				sort($proveedores);
				$proveedoresOpciones = '';
				foreach($proveedores as $proveedorDatos) {
					$proveedoresOpciones .= '<option id="proveedor' . $x . '-costo-' . $proveedorDatos[3] . '" value="' . $proveedorDatos[1] . '" data-costo="' . $proveedorDatos[0] . '" data-numero="' . $proveedorDatos[3] . '">' . $proveedorDatos[2] . '</option>';
				}
				?>
				$('#proveedor<?php echo $x; ?>').append('<option value="">Seleccionar proveedor...</option><?php echo $proveedoresOpciones; ?>');
			}
			// Elegida = PESOS | Parte = DOLARES
			if (monedaElegida == 1 && monedaParte == 2) {
				<?php
				$tipoCambio = $_GET['tipocambio'];
				$proveedores = array();
				if ($datos2['proveedor1'] != '') {
					$costo1 = number_format((float)$datos2['proveedor_costo1']*$tipoCambio, 6, '.', '');
					$proveedores[] = array(
						$costo1,
						$proveedor[1],
						'[' . $proveedor[1] . '] - [$' . $costo1 . ' PESOS]',
						1
					);
				}
				if ($datos2['proveedor2'] != '') {
					$costo2 = number_format((float)$datos2['proveedor_costo2']*$tipoCambio, 6, '.', '');
					$proveedores[] = array(
						$costo2,
						$proveedor[2],
						'[' . $proveedor[2] . '] - [$' . $costo2 . ' PESOS]',
						2
					);
				}
				if ($datos2['proveedor3'] != '') {
					$costo3 = number_format((float)$datos2['proveedor_costo3']*$tipoCambio, 6, '.', '');
					$proveedores[] = array(
						$costo3,
						$proveedor[3],
						'[' . $proveedor[3] . '] - [$' . $costo3 . ' PESOS]',
						3
					);
				}
				sort($proveedores);
				$proveedoresOpciones = '';
				foreach($proveedores as $proveedorDatos) {
					$proveedoresOpciones .= '<option id="proveedor' . $x . '-costo-' . $proveedorDatos[3] . '" value="' . $proveedorDatos[1] . '" data-costo="' . $proveedorDatos[0] . '" data-numero="' . $proveedorDatos[3] . '">' . $proveedorDatos[2] . '</option>';
				}
				?>
				$('#proveedor<?php echo $x; ?>').append('<option value="">Seleccionar proveedor...</option><?php echo $proveedoresOpciones; ?>');
			}
			
			<?php
			$x++;
		}
		?>
		</script>
		<?php
		break;
		// Conversion de cotizacion a orden de compra de integracion (para select de proveedores)
		case 'conversioncotizacionintegracion':
		$id = $_GET['valor'];
		$tipoCambio = $_GET['tipocambio'];
		?>
		
		<script>
		<?php
		$resultado = mysqli_query($dbh, "SELECT p.id AS idParte, p.moneda FROM cotizaciones_partes cp JOIN baselines p ON p.id = cp.id_parte WHERE cp.id_cotizacion = '$id' ORDER BY cp.id ASC");
		$x=1;
		$selectProveedores = array();
		while ($datos = mysqli_fetch_array($resultado)) {
			$monedaParte = $datos['moneda'];
			$proveedor = array();
			$idParte = $datos['idParte'];
			$resultado2 = mysqli_query($dbh, "SELECT p.vendedor1, p.vendedor_costo1, p.vendedor2, p.vendedor_costo2, p.vendedor3, p.vendedor_costo3 FROM baselines p WHERE id = '$idParte' AND p.status = 1");
			$datos2 = mysqli_fetch_array($resultado2);
			$datos2["vendedor1"] == ''? $proveedor[1] = 'NO ESPECIFICADO' : $proveedor[1] = $datos2["vendedor1"];
			$datos2["vendedor2"] == ''? $proveedor[2] = 'NO ESPECIFICADO' : $proveedor[2] = $datos2["vendedor2"];
			$datos2["vendedor3"] == ''? $proveedor[3] = 'NO ESPECIFICADO' : $proveedor[3] = $datos2["vendedor3"];
			?>
			// Se determina la moneda elegida y el tipo de cambio
			var monedaElegida = $('#cotizacion-moneda').val();
			var monedaParte = <?php echo $monedaParte; ?>;
			var tipoCambio = parseFloat($('#tipo-cambio').val());
			
			$('#vendedor<?php echo $x; ?>').empty();
			$('#vendedor<?php echo $x; ?>').removeAttr('disabled');
			// Elegida = PESOS | Parte = PESOS
			if (monedaElegida == 1 && monedaParte == 1) {
				<?php
				$proveedores = array();
				if ($datos2['vendedor1'] != '') {
					$proveedores[] = array(
						$datos2['vendedor_costo1'],
						$proveedor[1],
						'[' . $proveedor[1] . '] - [$' . $datos2['vendedor_costo1'] . ' PESOS]',
						1
					);
				}
				if ($datos2['vendedor2'] != '') {
					$proveedores[] = array(
						$datos2['vendedor_costo2'],
						$proveedor[2],
						'[' . $proveedor[2] . '] - [$' . $datos2['vendedor_costo2'] . ' PESOS]',
						2
					);
				}
				if ($datos2['vendedor3'] != '') {
					$proveedores[] = array(
						$datos2['vendedor_costo3'],
						$proveedor[3],
						'[' . $proveedor[3] . '] - [$' . $datos2['vendedor_costo3'] . ' PESOS]',
						3
					);
				}
				sort($proveedores);
				$proveedoresOpciones = '';
				foreach($proveedores as $proveedorDatos) {
					$proveedoresOpciones .= '<option id="proveedor' . $x . '-costo-' . $proveedorDatos[3] . '" value="' . $proveedorDatos[1] . '" data-costo="' . $proveedorDatos[0] . '" data-numero="' . $proveedorDatos[3] . '">' . $proveedorDatos[2] . '</option>';
				}
				?>
				$('#vendedor<?php echo $x; ?>').append('<option value="">Seleccionar proveedor...</option><?php echo $proveedoresOpciones; ?>');
			}
			// Elegida = DOLARES | Parte = PESOS
			if (monedaElegida == 2 && monedaParte == 1) {
				<?php
				$tipoCambio = $_GET['tipocambio'];
				$proveedores = array();
				if ($datos2['vendedor1'] != '') {
					$costo1 = number_format((float)$datos2['vendedor_costo1']/$tipoCambio, 6, '.', '');
					$proveedores[] = array(
						$costo1,
						$proveedor[1],
						'[' . $proveedor[1] . '] - [$' . $costo1 . ' DLLS]',
						1
					);
				}
				if ($datos2['vendedor2'] != '') {
					$costo2 = number_format((float)$datos2['vendedor_costo2']/$tipoCambio, 6, '.', '');
					$proveedores[] = array(
						$costo2,
						$proveedor[2],
						'[' . $proveedor[2] . '] - [$' . $costo2 . ' DLLS]',
						2
					);
				}
				if ($datos2['vendedor3'] != '') {
					$costo3 = number_format((float)$datos2['vendedor_costo3']/$tipoCambio, 6, '.', '');
					$proveedores[] = array(
						$costo3,
						$proveedor[3],
						'[' . $proveedor[3] . '] - [$' . $costo3 . ' DLLS]',
						3
					);
				}
				sort($proveedores);
				$proveedoresOpciones = '';
				foreach($proveedores as $proveedorDatos) {
					$proveedoresOpciones .= '<option id="proveedor' . $x . '-costo-' . $proveedorDatos[3] . '" value="' . $proveedorDatos[1] . '" data-costo="' . $proveedorDatos[0] . '" data-numero="' . $proveedorDatos[3] . '">' . $proveedorDatos[2] . '</option>';
				}
				?>
				$('#vendedor<?php echo $x; ?>').append('<option value="">Seleccionar proveedor...</option><?php echo $proveedoresOpciones; ?>');
			}
			// Elegida = DOLARES | Parte = DOLARES
			if (monedaElegida == 2 && monedaParte == 2) {
				<?php
				$proveedores = array();
				if ($datos2['vendedor1'] != '') {
					$proveedores[] = array(
						$datos2['vendedor_costo1'],
						$proveedor[1],
						'[' . $proveedor[1] . '] - [$' . $datos2['vendedor_costo1'] . ' DLLS]',
						1
					);
				}
				if ($datos2['vendedor2'] != '') {
					$proveedores[] = array(
						$datos2['vendedor_costo2'],
						$proveedor[2],
						'[' . $proveedor[2] . '] - [$' . $datos2['vendedor_costo2'] . ' DLLS]',
						2
					);
				}
				if ($datos2['vendedor3'] != '') {
					$proveedores[] = array(
						$datos2['vendedor_costo3'],
						$proveedor[3],
						'[' . $proveedor[3] . '] - [$' . $datos2['vendedor_costo3'] . ' DLLS]',
						3
					);
				}
				sort($proveedores);
				$proveedoresOpciones = '';
				foreach($proveedores as $proveedorDatos) {
					$proveedoresOpciones .= '<option id="proveedor' . $x . '-costo-' . $proveedorDatos[3] . '" value="' . $proveedorDatos[1] . '" data-costo="' . $proveedorDatos[0] . '" data-numero="' . $proveedorDatos[3] . '">' . $proveedorDatos[2] . '</option>';
				}
				?>
				$('#vendedor<?php echo $x; ?>').append('<option value="">Seleccionar proveedor...</option><?php echo $proveedoresOpciones; ?>');
			}
			// Elegida = PESOS | Parte = DOLARES
			if (monedaElegida == 1 && monedaParte == 2) {
				<?php
				$tipoCambio = $_GET['tipocambio'];
				$proveedores = array();
				if ($datos2['vendedor1'] != '') {
					$costo1 = number_format((float)$datos2['vendedor_costo1']*$tipoCambio, 6, '.', '');
					$proveedores[] = array(
						$costo1,
						$proveedor[1],
						'[' . $proveedor[1] . '] - [$' . $costo1 . ' PESOS]',
						1
					);
				}
				if ($datos2['vendedor2'] != '') {
					$costo2 = number_format((float)$datos2['vendedor_costo2']*$tipoCambio, 6, '.', '');
					$proveedores[] = array(
						$costo2,
						$proveedor[2],
						'[' . $proveedor[2] . '] - [$' . $costo2 . ' PESOS]',
						2
					);
				}
				if ($datos2['vendedor3'] != '') {
					$costo3 = number_format((float)$datos2['vendedor_costo3']*$tipoCambio, 6, '.', '');
					$proveedores[] = array(
						$costo3,
						$proveedor[3],
						'[' . $proveedor[3] . '] - [$' . $costo3 . ' PESOS]',
						3
					);
				}
				sort($proveedores);
				$proveedoresOpciones = '';
				foreach($proveedores as $proveedorDatos) {
					$proveedoresOpciones .= '<option id="proveedor' . $x . '-costo-' . $proveedorDatos[3] . '" value="' . $proveedorDatos[1] . '" data-costo="' . $proveedorDatos[0] . '" data-numero="' . $proveedorDatos[3] . '">' . $proveedorDatos[2] . '</option>';
				}
				?>
				$('#vendedor<?php echo $x; ?>').append('<option value="">Seleccionar proveedor...</option><?php echo $proveedoresOpciones; ?>');
			}
			
			<?php
			$x++;
		}
		?>
		</script>
		<?php
		break;
		// No Parte (Para baseline)
		case 'nopartebaseline':
		$id = urldecode($_GET['valor']);
		$resultado = mysqli_query($dbh, "SELECT p.id, p.descripcion, p.moneda_venta AS moneda, m.nombre AS um, p.precio1 FROM partes p LEFT JOIN partes_medidas m ON p.id_unidad_venta = m.id WHERE p.id = '$id' AND status = 1 LIMIT 1");
		$datos = mysqli_fetch_array($resultado);
		$descripcion = addslashes($datos['descripcion']);
		$moneda = $datos['moneda'];
		$resultado2 = mysqli_query($dbh, "SELECT SUM(total) AS conteo FROM inventario_cedis WHERE id_parte = '$id' LIMIT 1");
		$datos2 = mysqli_fetch_array($resultado2);
		$totalInventario = $datos2['conteo'];
		?>
		
		<script>
		$('#idParte<?php echo $_GET['fila']; ?>').val('<?php echo $datos['id']; ?>');
		$('#descripcion<?php echo $_GET['fila']; ?>').val("<?php echo $descripcion; ?>");
		$('#um<?php echo $_GET['fila']; ?>').val('<?php echo $datos['um']; ?>');
		$('#existencia<?php echo $_GET['fila']; ?>').val('<?php echo $totalInventario; ?>');
		</script>
		<?php
		break;
		// No Parte Compra Spot
		case 'nopartecompraspot':
		$id = urldecode($_GET['valor']);
		$idIntegracion = $_GET['idIntegracion'];
		$resultado = mysqli_query($dbh, "SELECT id FROM baselines WHERE id_parte = '$id' AND id_integracion = '$idIntegracion' AND status = 1 LIMIT 1");
		$datos = mysqli_fetch_array($resultado);
		if (!empty($datos['id'])) {
		?>
		<script>
		new $.Zebra_Dialog('El número de parte que estás requiriendo ya existe.<br /><br /><form method="post" action="<?php echo STASIS; ?>/integraciones/sitio/MediMexico/usuario_requisicion_spot"><input type="hidden" value="<?php echo $datos['id']; ?>" name="idParte" /><input type="hidden" value="1" name="compraSpot" /><button type="submit" class="btn btn-primary"><i class="fa fa-cog"></i> Generar requisición MRO con este número de parte</button></form>', {
		    'buttons':  false,
		    'modal': false,
		    'title': 'Advertencia',
		    'type': 'warning'
		});
		</script>
		<?php
		}
		break;
		// No Parte (Para facturar)
		case 'nopartebaselinefactura':
		$id = urldecode($_GET['valor']);
		$idIntegracion = $_GET['idIntegracion'];
		$resultado = mysqli_query($dbh, "SELECT id, id_parte_xpress, descripcion, uom_venta, precio, moneda, baseline FROM baselines WHERE id_parte_xpress = '$id' AND id_integracion = '$idIntegracion' AND status = 1 LIMIT 1");
		$datos = mysqli_fetch_array($resultado);
		$descripcion = addslashes($datos['descripcion']);
		$moneda = $datos['moneda'];
		?>
		
		<script>
		$('#editarParte<?php echo $_GET['fila']; ?>').attr('target', '_blank');
		$('#editarParte<?php echo $_GET['fila']; ?>').attr('href', '<?php echo STASIS; ?>/catalogos/baseline/smk/modificar/<?php echo $datos['id']; ?>');
		$('#idParte<?php echo $_GET['fila']; ?>').val('<?php echo $datos['id']; ?>');
		// Se determina la moneda elegida y el tipo de cambio
		var monedaElegida = $('#cotizacion-moneda').val();
		var monedaParte = <?php echo $moneda; ?>;
		var tipoCambio = parseFloat($('#tipo-cambio-change').val());
		// Elegida = PESOS | Parte = PESOS
		if (monedaElegida == 1 && monedaParte == 1) {
			$('#baseline-escondido<?php echo $_GET['fila']; ?>').val(<?php echo $datos['baseline']; ?>);
			$('#precio-escondido<?php echo $_GET['fila']; ?>').val(<?php echo $datos['precio']; ?>);
			$('#precio<?php echo $_GET['fila']; ?>').val(<?php echo $datos['precio']; ?>);
		}
		// Elegida = DOLARES | Parte = PESOS
		if (monedaElegida == 2 && monedaParte == 1) {
			var diferencia = parseFloat(<?php echo $datos['precio']; ?>/tipoCambio);
			var diferenciaBaseline = parseFloat(<?php echo $datos['baseline']; ?>/tipoCambio);
			var redondeado = diferencia.toFixed(2);
			$('#baseline-escondido<?php echo $_GET['fila']; ?>').val(diferenciaBaseline);
			$('#precio-escondido<?php echo $_GET['fila']; ?>').val(redondeado);
			$('#precio<?php echo $_GET['fila']; ?>').val(redondeado);
		}
		// Elegida = DOLARES | Parte = DOLARES
		if (monedaElegida == 2 && monedaParte == 2) {
			$('#baseline-escondido<?php echo $_GET['fila']; ?>').val(<?php echo $datos['baseline']; ?>);
			$('#precio-escondido<?php echo $_GET['fila']; ?>').val(<?php echo $datos['precio']; ?>);
			$('#precio<?php echo $_GET['fila']; ?>').val(<?php echo $datos['precio']; ?>);
		}
		// Elegida = PESOS | Parte = DOLARES
		if (monedaElegida == 1 && monedaParte == 2) {
			var diferencia = parseFloat(<?php echo $datos['precio']; ?>*tipoCambio);
			var diferenciaBaseline = parseFloat(<?php echo $datos['baseline']; ?>*tipoCambio);
			var redondeado = diferencia.toFixed(2);
			$('#baseline-escondido<?php echo $_GET['fila']; ?>').val(diferenciaBaseline);
			$('#precio-escondido<?php echo $_GET['fila']; ?>').val(redondeado);
			$('#precio<?php echo $_GET['fila']; ?>').val(redondeado);
		}
		$('#descripcion<?php echo $_GET['fila']; ?>').val("<?php echo $descripcion; ?>");
		$('#um<?php echo $_GET['fila']; ?>').val('<?php echo $datos['uom_venta']; ?>');
		</script>
		<?php
		break;
		// No Parte (Para Requisicion)
		case 'noParteBaselineRequisicion':
		$id = urldecode($_GET['valor']);
		$idEjercicio = date('y');
		$mesActual = date('n');
		$idIntegracion = $_GET['idIntegracion'];
		switch($idIntegracion) {
			case 2: $almacen1 = 3; $almacen2 = 4; $almacen3 = 5; break;
			case 3: $almacen1 = 10; $almacen2 = 11; $almacen3 = 12; break;
			case 4: $almacen1 = 7; $almacen2 = 8; $almacen3 = 9; break;
			case 5: $almacen1 = 17; $almacen2 = 18; $almacen3 = 19; break;
		}
		$resultado = mysqli_query($dbh, "SELECT IFNULL(pe.entradas_p$mesActual, 0) - IFNULL(pe.salidas_p$mesActual, 0) AS existencia, pe.id_almacen
			FROM baselines b
			JOIN partes_existencias pe
			ON pe.id_producto = b.id
			WHERE b.id_parte_xpress = '$id' AND pe.id_integracion = '$idIntegracion' AND (pe.id_almacen = '$almacen1' OR pe.id_almacen = '$almacen2' OR pe.id_almacen = '$almacen3') AND pe.id_ejercicio = '$idEjercicio' AND b.status = 1");
		while ($datos = mysqli_fetch_array($resultado)) {
			if ($datos['id_almacen'] == $almacen1) {
				$existencia01 = $datos['existencia'];
			}
			if ($datos['id_almacen'] == $almacen2) {
				$existencia02 = $datos['existencia'];
			}
			if ($datos['id_almacen'] == $almacen3) {
				$existencia03 = $datos['existencia'];
			}
		}
		$resultado = mysqli_query($dbh, "SELECT b.id, b.id_parte_xpress, b.descripcion, b.uom_venta, b.min, b.max, b.precio, locacion_a, locacion_b, locacion_c, locacion_d, locacion_unificada
			FROM baselines b
			WHERE b.id_parte_xpress = '$id' AND b.id_integracion = '$idIntegracion' AND b.status = 1
			LIMIT 1");
		$datos = mysqli_fetch_array($resultado);
		$descripcion = addslashes($datos['descripcion']);
		$precio = number_format((float)$datos['precio'], 6, '.', '');
		if ($datos['locacion_unificada'] == '') {
			$locacion = $datos['locacion_a'] . $datos['locacion_b'] . $datos['locacion_c'] . $datos['locacion_d'];
		} else {
			$locacion = $datos['locacion_unificada'];
		}
		if ($existencia01 >= 1 || $existencia02 >= 1 || $existencia03 >= 1) {
			?>
			<script>
			<?php
			if ($precio == 0 && ($existencia02 >= 1 || $existencia03 >= 1)) {
			?>
			new $.Zebra_Dialog('¡Advertencia! Número de parte con <strong>precio 0</strong>.', {
			    'buttons':  false,
			    'modal': false,
			    'type': 'warning',
			    'auto_close': 2000
			});
			<?php
			}
			?>
			$('#sinExistencias<?php echo $_GET['fila']; ?>').val(0);
			$('#noParteSitio<?php echo $_GET['fila']; ?>').attr('readonly', 'readonly');
			$('#cantidad<?php echo $_GET['fila']; ?>').removeAttr('readonly');
			$('#existencia1<?php echo $_GET['fila']; ?>').attr('readonly', 'readonly');
			$('#existencia2<?php echo $_GET['fila']; ?>').attr('readonly', 'readonly');
			$('#existencia3<?php echo $_GET['fila']; ?>').attr('readonly', 'readonly');
			$('#cantidadSurtida<?php echo $_GET['fila']; ?>').removeAttr('readonly');
			$('#idParte<?php echo $_GET['fila']; ?>').val('<?php echo $datos['id']; ?>');
			$('#um<?php echo $_GET['fila']; ?>').val("<?php echo $datos['uom_venta']; ?>");
			$('#descripcion<?php echo $_GET['fila']; ?>').val("<?php echo $descripcion; ?>");
			$('#precio<?php echo $_GET['fila']; ?>').val("<?php echo $precio; ?>");
			$('#min<?php echo $_GET['fila']; ?>').val('<?php echo $datos['min']; ?>');
			$('#max<?php echo $_GET['fila']; ?>').val('<?php echo $datos['max']; ?>');
			$('#existencia1<?php echo $_GET['fila']; ?>').val('<?php echo $existencia01; ?>');
			$('#existencia2<?php echo $_GET['fila']; ?>').val('<?php echo $existencia02; ?>');
			$('#existencia3<?php echo $_GET['fila']; ?>').val('<?php echo $existencia03; ?>');
			$('#existenciaTotal<?php echo $_GET['fila']; ?>').val('<?php echo $existenciaTotal; ?>');
			$('#locacion<?php echo $_GET['fila']; ?>').val('<?php echo $locacion; ?>');
			</script>
			<?php
		} else {
			?>
			<script>
			$('#noParteSitio<?php echo $_GET['fila']; ?>').attr('readonly', 'readonly');
			$('#idParte<?php echo $_GET['fila']; ?>').val('<?php echo $datos['id']; ?>');
			$('#um<?php echo $_GET['fila']; ?>').val("<?php echo $datos['uom_venta']; ?>");
			$('#descripcion<?php echo $_GET['fila']; ?>').val("<?php echo $descripcion; ?>");
			$('#precio<?php echo $_GET['fila']; ?>').val("<?php echo $precio; ?>");
			$('#min<?php echo $_GET['fila']; ?>').val('<?php echo $datos['min']; ?>');
			$('#max<?php echo $_GET['fila']; ?>').val('<?php echo $datos['max']; ?>');
			new $.Zebra_Dialog('No hay existencias del número de parte <strong><?php echo $id; ?></strong> en los almacenes.', {
			    'buttons':  false,
			    'modal': false,
			    'auto_close': 2000
			});
			$('#sinExistencias<?php echo $_GET['fila']; ?>').val(1);
			$('#cantidad<?php echo $_GET['fila']; ?>').attr('readonly', 'readonly');
			$('#cantidadSurtida<?php echo $_GET['fila']; ?>').attr('readonly', 'readonly');
			$('#existencia1<?php echo $_GET['fila']; ?>').removeAttr('readonly');
			$('#existencia2<?php echo $_GET['fila']; ?>').removeAttr('readonly');
			$('#existencia3<?php echo $_GET['fila']; ?>').removeAttr('readonly');
			$('#existencia1<?php echo $_GET['fila']; ?>').val('0');
			$('#existencia2<?php echo $_GET['fila']; ?>').val('0');
			$('#existencia3<?php echo $_GET['fila']; ?>').val('0');
			$('#existenciaTotal<?php echo $_GET['fila']; ?>').val('0');
			$('#locacion<?php echo $_GET['fila']; ?>').val('<?php echo $locacion; ?>');
			</script>
			<?php
		}
		break;
		// No Parte de Sitio (Para Requisicion)
		case 'noParteBaselineRequisicionSitio':
		$id = urldecode($_GET['valor']);
		$idEjercicio = date('y');
		$mesActual = date('n');
		$idIntegracion = $_GET['idIntegracion'];
		switch($idIntegracion) {
			case 2: $almacen1 = 3; $almacen2 = 4; $almacen3 = 5; break;
			case 3: $almacen1 = 10; $almacen2 = 11; $almacen3 = 12; break;
			case 4: $almacen1 = 7; $almacen2 = 8; $almacen3 = 9; break;
			case 5: $almacen1 = 17; $almacen2 = 18; $almacen3 = 19; break;
		}
		$resultado = mysqli_query($dbh, "SELECT IFNULL(pe.entradas_p$mesActual, 0) - IFNULL(pe.salidas_p$mesActual, 0) AS existencia, pe.id_almacen
			FROM baselines b
			JOIN partes_existencias pe
			ON pe.id_producto = b.id
			WHERE b.id_parte = '$id' AND pe.id_integracion = '$idIntegracion' AND (pe.id_almacen = '$almacen1' OR pe.id_almacen = '$almacen2' OR pe.id_almacen = '$almacen3') AND pe.id_ejercicio = '$idEjercicio' AND b.status = 1");
		while ($datos = mysqli_fetch_array($resultado)) {
			if ($datos['id_almacen'] == $almacen1) {
				$existencia01 = $datos['existencia'];
			}
			if ($datos['id_almacen'] == $almacen2) {
				$existencia02 = $datos['existencia'];
			}
			if ($datos['id_almacen'] == $almacen3) {
				$existencia03 = $datos['existencia'];
			}
		}
		$resultado = mysqli_query($dbh, "SELECT b.id, b.id_parte, b.descripcion, b.uom_venta, uom_compra_qty, b.min, b.max, b.precio, locacion_a, locacion_b, locacion_c, locacion_d, locacion_unificada
			FROM baselines b
			WHERE b.id_parte = '$id' AND b.id_integracion = '$idIntegracion' AND b.status = 1
			LIMIT 1");
		$datos = mysqli_fetch_array($resultado);
		$descripcion = addslashes($datos['descripcion']);
		$precio = number_format((float)$datos['precio'], 6, '.', '');
		if ($datos['locacion_unificada'] == '') {
			$locacion = $datos['locacion_a'] . $datos['locacion_b'] . $datos['locacion_c'] . $datos['locacion_d'];
		} else {
			$locacion = $datos['locacion_unificada'];
		}
		// Si la integracion es de SMK, hacer la conversion
		// $existencia01 = $existencia01 
		if ($existencia01 >= 1 || $existencia02 >= 1 || $existencia03 >= 1) {
			$existenciaTotal = $existencia01 + $existencia02 + $existencia03;
			?>
			<script>
			<?php
			if ($precio == 0 && ($existencia02 >= 1 || $existencia03 >= 1)) {
			?>
			new $.Zebra_Dialog('¡Advertencia! Número de parte con <strong>precio 0</strong>.', {
			    'buttons':  false,
			    'modal': false,
			    'type': 'warning',
			    'auto_close': 2000
			});
			<?php
			}
			?>
			$('#decision2<?php echo $_GET['fila']; ?>').removeAttr('disabled');
			$('#decision3<?php echo $_GET['fila']; ?>').removeAttr('disabled');
			$('#decision2<?php echo $_GET['fila']; ?>').removeAttr('checked');
			$('#decision3<?php echo $_GET['fila']; ?>').removeAttr('checked');
			$('#idParte<?php echo $_GET['fila']; ?>').val('<?php echo $datos['id']; ?>');
			$('#um<?php echo $_GET['fila']; ?>').val("<?php echo $datos['uom_venta']; ?>");
			$('#descripcion<?php echo $_GET['fila']; ?>').val("<?php echo $descripcion; ?>");
			$('#precio<?php echo $_GET['fila']; ?>').val("<?php echo $precio; ?>");
			$('#min<?php echo $_GET['fila']; ?>').val('<?php echo $datos['min']; ?>');
			$('#max<?php echo $_GET['fila']; ?>').val('<?php echo $datos['max']; ?>');
			$('#existencia1<?php echo $_GET['fila']; ?>').val('<?php echo $existencia01; ?>');
			$('#existencia2<?php echo $_GET['fila']; ?>').val('<?php echo $existencia02; ?>');
			$('#existencia3<?php echo $_GET['fila']; ?>').val('<?php echo $existencia03; ?>');
			$('#existenciaTotal<?php echo $_GET['fila']; ?>').val('<?php echo $existenciaTotal; ?>');
			$('#locacion<?php echo $_GET['fila']; ?>').val('<?php echo $locacion; ?>');
			</script>
			<?php
		} else {
			?>
			<script>
			$('#noParte<?php echo $_GET['fila']; ?>').attr('readonly', 'readonly');
			$('#idParte<?php echo $_GET['fila']; ?>').val('<?php echo $datos['id']; ?>');
			$('#um<?php echo $_GET['fila']; ?>').val("<?php echo $datos['uom_venta']; ?>");
			$('#descripcion<?php echo $_GET['fila']; ?>').val("<?php echo $descripcion; ?>");
			$('#precio<?php echo $_GET['fila']; ?>').val("<?php echo $precio; ?>");
			$('#min<?php echo $_GET['fila']; ?>').val('<?php echo $datos['min']; ?>');
			$('#max<?php echo $_GET['fila']; ?>').val('<?php echo $datos['max']; ?>');
			$('#decision2<?php echo $_GET['fila']; ?>').attr('disabled', 'disabled');
			$('#decision3<?php echo $_GET['fila']; ?>').attr('disabled', 'disabled');
			$('#decision2<?php echo $_GET['fila']; ?>').removeAttr('checked');
			$('#decision3<?php echo $_GET['fila']; ?>').removeAttr('checked');
			new $.Zebra_Dialog('No hay existencias del número de parte <strong><?php echo $id; ?></strong> en los almacenes.', {
			    'buttons':  false,
			    'modal': false,
			    'auto_close': 2000
			});
			$('#sinExistencias<?php echo $_GET['fila']; ?>').val(1);
			$('#cantidad<?php echo $_GET['fila']; ?>').attr('readonly', 'readonly');
			$('#cantidadSurtida<?php echo $_GET['fila']; ?>').attr('readonly', 'readonly');
			$('#existencia1<?php echo $_GET['fila']; ?>').removeAttr('readonly');
			$('#existencia2<?php echo $_GET['fila']; ?>').removeAttr('readonly');
			$('#existencia3<?php echo $_GET['fila']; ?>').removeAttr('readonly');
			$('#existencia1<?php echo $_GET['fila']; ?>').val('0');
			$('#existencia2<?php echo $_GET['fila']; ?>').val('0');
			$('#existencia3<?php echo $_GET['fila']; ?>').val('0');
			$('#existenciaTotal<?php echo $_GET['fila']; ?>').val('0');
			$('#locacion<?php echo $_GET['fila']; ?>').val('<?php echo $locacion; ?>');
			</script>
			<?php
		}
		break;
		// No Parte de Sitio (Para Requisicion)
		case 'noParteBaselineRequisicionSitioStock':
		$id = urldecode($_GET['valor']);
		$idEjercicio = date('y');
		$mesActual = date('n');
		$idIntegracion = $_GET['idIntegracion'];
		switch($idIntegracion) {
			case 2: $almacen1 = 3; $almacen2 = 4; $almacen3 = 5; break;
			case 3: $almacen1 = 10; $almacen2 = 11; $almacen3 = 12; break;
			case 4: $almacen1 = 7; $almacen2 = 8; $almacen3 = 9; break;
			case 5: $almacen1 = 17; $almacen2 = 18; $almacen3 = 19; break;
		}
		$resultado = mysqli_query($dbh, "SELECT IFNULL(pe.entradas_p$mesActual, 0) - IFNULL(pe.salidas_p$mesActual, 0) AS existencia, pe.id_almacen
			FROM baselines b
			JOIN partes_existencias pe
			ON pe.id_producto = b.id
			WHERE b.id_parte = '$id' AND pe.id_integracion = '$idIntegracion' AND (pe.id_almacen = '$almacen1' OR pe.id_almacen = '$almacen2' OR pe.id_almacen = '$almacen3') AND pe.id_ejercicio = '$idEjercicio' AND b.status = 1");
		while ($datos = mysqli_fetch_array($resultado)) {
			if ($datos['id_almacen'] == $almacen1) {
				$existencia01 = $datos['existencia'];
			}
			if ($datos['id_almacen'] == $almacen2) {
				$existencia02 = $datos['existencia'];
			}
			if ($datos['id_almacen'] == $almacen3) {
				$existencia03 = $datos['existencia'];
			}
		}
		$resultado = mysqli_query($dbh, "SELECT b.id, b.id_parte, b.descripcion, b.uom_venta, uom_compra_qty, b.min, b.max, b.precio, locacion_a, locacion_b, locacion_c, locacion_d, locacion_unificada
			FROM baselines b
			WHERE b.id_parte = '$id' AND b.id_integracion = '$idIntegracion' AND b.status = 1
			LIMIT 1");
		$datos = mysqli_fetch_array($resultado);
		$descripcion = addslashes($datos['descripcion']);
		$precio = number_format((float)$datos['precio'], 6, '.', '');
		if ($datos['locacion_unificada'] == '') {
			$locacion = $datos['locacion_a'] . $datos['locacion_b'] . $datos['locacion_c'] . $datos['locacion_d'];
		} else {
			$locacion = $datos['locacion_unificada'];
		}
		?>
		<script>
		$('#idParte<?php echo $_GET['fila']; ?>').val('<?php echo $datos['id']; ?>');
		$('#um<?php echo $_GET['fila']; ?>').val("<?php echo $datos['uom_venta']; ?>");
		$('#descripcion<?php echo $_GET['fila']; ?>').val("<?php echo $descripcion; ?>");
		$('#precio<?php echo $_GET['fila']; ?>').val("<?php echo $precio; ?>");
		$('#min<?php echo $_GET['fila']; ?>').val('<?php echo $datos['min']; ?>');
		$('#max<?php echo $_GET['fila']; ?>').val('<?php echo $datos['max']; ?>');
		$('#existencia1<?php echo $_GET['fila']; ?>').val('<?php echo $existencia01; ?>');
		$('#existencia2<?php echo $_GET['fila']; ?>').val('<?php echo $existencia02; ?>');
		$('#existencia3<?php echo $_GET['fila']; ?>').val('<?php echo $existencia03; ?>');
		$('#existenciaTotal<?php echo $_GET['fila']; ?>').val('<?php echo $existenciaTotal; ?>');
		$('#locacion<?php echo $_GET['fila']; ?>').val('<?php echo $locacion; ?>');
		</script>
		<?php
		break;
		// No Parte (Para Requisicion de entrada)
		case 'noParteBaselineRequisicionEntrada':
		$id = urldecode($_GET['valor']);
		$idEjercicio = date('y');
		$mesActual = date('n');
		$idIntegracion = $_GET['idIntegracion'];
		$resultado = mysqli_query($dbh, "SELECT b.id, b.id_parte_xpress, b.descripcion, b.uom_venta, b.min, b.max, locacion_a, locacion_b, locacion_c, locacion_d, locacion_unificada
			FROM baselines b
			WHERE b.id_parte_xpress = '$id' AND b.id_integracion = '$idIntegracion' AND b.status = 1
			LIMIT 1");
		$datos = mysqli_fetch_array($resultado);
		$descripcion = addslashes($datos['descripcion']);
		if ($datos['locacion_unificada'] == '') {
			$locacion = $datos['locacion_a'] . $datos['locacion_b'] . $datos['locacion_c'] . $datos['locacion_d'];
		} else {
			$locacion = $datos['locacion_unificada'];
		}
		?>
		<script>
		$('#noParteSitio<?php echo $_GET['fila']; ?>').attr('readonly', 'readonly');
		$('#cantidad<?php echo $_GET['fila']; ?>').removeAttr('readonly');
		$('#idParte<?php echo $_GET['fila']; ?>').val('<?php echo $datos['id']; ?>');
		$('#um<?php echo $_GET['fila']; ?>').val("<?php echo $datos['uom_venta']; ?>");
		$('#descripcion<?php echo $_GET['fila']; ?>').val("<?php echo $descripcion; ?>");
		$('#min<?php echo $_GET['fila']; ?>').val('<?php echo $datos['min']; ?>');
		$('#max<?php echo $_GET['fila']; ?>').val('<?php echo $datos['max']; ?>');
		$('#locacion<?php echo $_GET['fila']; ?>').val('<?php echo $locacion; ?>');
		</script>
		<?php
		break;
		// No Parte de Sitio (Para Requisicion de Entrada)
		case 'noParteBaselineRequisicionEntradaSitio':
		$id = urldecode($_GET['valor']);
		$idEjercicio = date('y');
		$mesActual = date('n');
		$idIntegracion = $_GET['idIntegracion'];
		$resultado = mysqli_query($dbh, "SELECT b.id, b.id_parte, b.descripcion, b.uom_venta, b.min, b.max, b.precio, locacion_a, locacion_b, locacion_c, locacion_d, locacion_unificada
			FROM baselines b
			WHERE b.id_parte = '$id' AND b.id_integracion = '$idIntegracion' AND b.status = 1
			LIMIT 1");
		$datos = mysqli_fetch_array($resultado);
		$descripcion = addslashes($datos['descripcion']);
		if ($datos['locacion_unificada'] == '') {
			$locacion = $datos['locacion_a'] . $datos['locacion_b'] . $datos['locacion_c'] . $datos['locacion_d'];
		} else {
			$locacion = $datos['locacion_unificada'];
		}
		?>
		<script>
		$('#noParte<?php echo $_GET['fila']; ?>').attr('readonly', 'readonly');
		$('#cantidad<?php echo $_GET['fila']; ?>').removeAttr('readonly');
		$('#idParte<?php echo $_GET['fila']; ?>').val('<?php echo $datos['id']; ?>');
		$('#um<?php echo $_GET['fila']; ?>').val("<?php echo $datos['uom_venta']; ?>");
		$('#descripcion<?php echo $_GET['fila']; ?>').val("<?php echo $descripcion; ?>");
		$('#min<?php echo $_GET['fila']; ?>').val('<?php echo 