<?php
final class Modelos_Constructora_Presupuestos extends Modelo {
	protected $_db = null;
	public $mensajes = array();

	public function iniciarDb($db) {
    	if (!$this->_db) {
			$this->_db = $db;
        }
    }

    public function listado() {
		try {
			$datosVista = array();

			// Activos
			$sth = $this->_db->query("
				SELECT cc.id, cc.tipo, cc.elector, cc.rfc, cc.descripcion, cc.m2, cc.contrato, cc.contrato_fin, cc.costo, cc.pago1, cc.pago2, cc.pago3, cc.pago_entrega, p.seccion, p.manzana, p.lote, p.nombre, p.telefono1, p.email
				FROM constructora_contratos cc
				JOIN propietarios p
				ON p.id = cc.id_contratante
				ORDER BY cc.id DESC");
			if(!$sth->execute()) throw New Exception();
			
			$activos = array();
			while ($datos = $sth->fetch()) {
				switch ($datos['seccion']) {
	    			case 'HACIENDA DEL REY (RGR)': $prefijo = 'SR'; break;
	    			case 'HACIENDA DEL REY': $prefijo = 'SR'; break;
	    			case 'LOMAS (RGR)': $prefijo = 'SL'; break;
	    			case 'LOMAS': $prefijo = 'SL'; break;
	    			case 'HACIENDA VALLE DE LOS ENCINOS (RGR)': $prefijo = 'SV'; break;
	    			case 'HACIENDA VALLE DE LOS ENCINOS': $prefijo = 'SV'; break;
	    			case 'CAÑADA DEL ENCINO': $prefijo = 'SC'; break;
	    			case 'VISTA DEL REY (RGR)': $prefijo = 'VR'; break;
	    			case 'VISTA DEL REY': $prefijo = 'VR'; break;
	    		}

				$arreglo = array(
					'id' => $datos['id'],
					'nombre' => mb_strtoupper($datos['nombre'], 'UTF-8'),
					'lote' => $prefijo . '-' . $datos['lote'] . '-' . $datos['manzana'],
					'telefono' => mb_strtoupper($datos['telefono1'], 'UTF-8'),
					'email' => strtolower($datos['email']),
					'm2' => $datos['m2'] . ' M2',
					'costo' => '$ ' . number_format($datos['costo'], 2, '.', ','),
				);
				$activos[] = $arreglo;
			}

	  		$datosVista['activos'] = $activos;
			
	  		return $datosVista;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

    public function nuevo() {
		try {
			$id_contratante = $_POST['id_contratante'];
			$tipo = $_POST['tipo'];
			$elector = $_POST['elector'];
			$rfc = $_POST['rfc'];
			$descripcion = $_POST['descripcion'];
			$m2 = $_POST['m2'];

			$contrato_dia = $_POST['contrato_dia'];
			$contrato_mes = $_POST['contrato_mes'];
			$contrato_ano = $_POST['contrato_ano'];
			$contratoInicio = $contrato_ano . '-' . $contrato_mes . '-' . $contrato_dia;

			$contrato_fin_dia = $_POST['contrato_fin_dia'];
			$contrato_fin_mes = $_POST['contrato_fin_mes'];
			$contrato_fin_ano = $_POST['contrato_fin_ano'];
			$contratoFin = $contrato_fin_ano . '-' . $contrato_fin_mes . '-' . $contrato_fin_dia;

			$costo = $_POST['costo'];

			$contrato_1er_dia = $_POST['contrato_1er_dia'];
			$contrato_1er_mes = $_POST['contrato_1er_mes'];
			$contrato_1er_ano = $_POST['contrato_1er_ano'];
			$pago1 = $contrato_1er_ano . '-' . $contrato_1er_mes . '-' . $contrato_1er_dia;

			$contrato_2do_dia = $_POST['contrato_2do_dia'];
			$contrato_2do_mes = $_POST['contrato_2do_mes'];
			$contrato_2do_ano = $_POST['contrato_2do_ano'];
			$pago2 = $contrato_2do_ano . '-' . $contrato_2do_mes . '-' . $contrato_2do_dia;

			$contrato_3er_dia = $_POST['contrato_3er_dia'];
			$contrato_3er_mes = $_POST['contrato_3er_mes'];
			$contrato_3er_ano = $_POST['contrato_3er_ano'];
			$pago3 = $contrato_3er_ano . '-' . $contrato_3er_mes . '-' . $contrato_3er_dia;

			$contrato_entrega_dia = $_POST['contrato_entrega_dia'];
			$contrato_entrega_mes = $_POST['contrato_entrega_mes'];
			$contrato_entrega_ano = $_POST['contrato_entrega_ano'];
			$pagoEntrega = $contrato_entrega_ano . '-' . $contrato_entrega_mes . '-' . $contrato_entrega_dia;

			$arregloDatos = array($id_contratante, $tipo, $elector, $rfc, $descripcion, $m2, $contratoInicio, $contratoFin, $costo, $pago1, $pago2, $pago3, $pagoEntrega);

			$sth = $this->_db->prepare("INSERT INTO constructora_contratos (id_contratante, tipo, elector, rfc, descripcion, m2, contrato, contrato_fin, costo, pago1, pago2, pago3, pago_entrega) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			if($sth->execute($arregloDatos)) {
				$this->mensajes[] = Modelos_Sistema::status(2, 'Contrato agregado exitosamente.');
			} else {
				throw New Exception();
			}
		} catch (Exception $e) {
			var_dump($e->getMessage());die;
		}
	}

	public function visualizar($id) {
		$this->pdf($id,0,1);
	}

	public function descargar($id) {
		$this->pdf($id,1,0);
	}

	public function pdf($id, $descargar = null, $visualizar = null) {
		// PDF
		require_once(APP . 'plugins/tcpdf/tcpdf.php');
		$pdf = new RTPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetTitle('Contrato');
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->SetPrintHeader(false);
		$pdf->SetMargins(20, 20, 20, 0);
		$pdf->SetFont('Helvetica', '', 10);
		$pdf->AddPage();

		$sth = $this->_db->prepare("
			SELECT cc.id, cc.tipo, cc.elector, cc.rfc, cc.descripcion, cc.m2, cc.contrato, cc.contrato_fin, cc.costo, cc.pago1, cc.pago2, cc.pago3, cc.pago_entrega, p.seccion, p.manzana, p.lote, p.nombre, p.telefono1, p.email
			FROM constructora_contratos cc
			JOIN propietarios p
			ON p.id = cc.id_contratante
			WHERE cc.id = ?
		");
		$sth->bindParam(1, $id);
		if(!$sth->execute()) throw New Exception();
		$datos = $sth->fetch();

		switch ($datos['seccion']) {
			case 'HACIENDA DEL REY (RGR)': $prefijo = 'SR'; break;
			case 'HACIENDA DEL REY': $prefijo = 'SR'; break;
			case 'LOMAS (RGR)': $prefijo = 'SL'; break;
			case 'LOMAS': $prefijo = 'SL'; break;
			case 'HACIENDA VALLE DE LOS ENCINOS (RGR)': $prefijo = 'SV'; break;
			case 'HACIENDA VALLE DE LOS ENCINOS': $prefijo = 'SV'; break;
			case 'CAÑADA DEL ENCINO': $prefijo = 'SC'; break;
			case 'VISTA DEL REY (RGR)': $prefijo = 'VR'; break;
			case 'VISTA DEL REY': $prefijo = 'VR'; break;
		}

		$nombre = '<span style="color: blue; font-weight: bold;">' . mb_strtoupper($datos['nombre'], 'UTF-8') . '</span>';
		$tipo = '<span style="color: blue; font-weight: bold;">' . mb_strtoupper($datos['tipo'], 'UTF-8') . '</span>';
		$seccion = '<span style="color: blue; font-weight: bold;">' . mb_strtoupper($datos['seccion'], 'UTF-8') . '</span>';
		$elector = '<span style="color: blue; font-weight: bold;">' . mb_strtoupper($datos['elector'], 'UTF-8') . '</span>';
		$rfc = '<span style="color: blue; font-weight: bold;">' . mb_strtoupper($datos['rfc'], 'UTF-8') . '</span>';
		$descripcion = '<span style="color: blue; font-weight: bold;">' . mb_strtoupper($datos['descripcion'], 'UTF-8') . '</span>';
		$m2 = '<span style="color: blue; font-weight: bold;">' . mb_strtoupper($datos['m2'], 'UTF-8') . ' m2</span>';
		
		$date = new DateTime($datos['contrato']);
		$mes = strftime("%B", $date->getTimestamp());
		$contrato = '<span style="color: blue; font-weight: bold;">' . $date->format('d') . ' de ' . $mes . ' del ' . $date->format('Y') . '</span>';

		$date = new DateTime($datos['contrato_fin']);
		$mes = strftime("%B", $date->getTimestamp());
		$contratoFin = '<span style="color: blue; font-weight: bold;">' . $date->format('d') . ' de ' . $mes . ' del ' . $date->format('Y') . '</span>';

		$pago40Porciento = $datos['costo']*.04;

		$costo = '<span style="color: blue; font-weight: bold;">' . number_format($datos['costo'], 2, '.', ',') . '</span>';
		$costoDividido = '<span style="color: blue; font-weight: bold;">' . number_format($pago40Porciento/4, 2, '.', ',') . '</span>';
		$pago1 = '<span style="color: blue; font-weight: bold;">' . Modelos_Fecha::formatearFecha($datos['pago1']) . '</span>';
		$pago2 = '<span style="color: blue; font-weight: bold;">' . Modelos_Fecha::formatearFecha($datos['pago2']) . '</span>';
		$pago3 = '<span style="color: blue; font-weight: bold;">' . Modelos_Fecha::formatearFecha($datos['pago3']) . '</span>';
		$pago_entrega = '<span style="color: blue; font-weight: bold;">' . Modelos_Fecha::formatearFecha($datos['pago_entrega']) . '</span>';
		$lote = '<span style="color: blue; font-weight: bold;">' . $prefijo . '-' . $datos['lote'] . '-' . $datos['manzana'] . '</span>';
		$loteNum = '<span style="color: blue; font-weight: bold;">' . $datos['lote'] . '</span>';
		$manzanaNum = '<span style="color: blue; font-weight: bold;">' . $datos['manzana'] . '</span>';

		$stasis = STASIS;

		$html = <<<EOF
		<br /><br />
		<div style="text-align: justify; font-size: 9px;">
			<span style="text-align: center; font-size: 14px;"><strong><span>CONTRATO DE OBRA A PRECIO ALZADO</span></strong></span><br />

			<p><span>CONTRATO DE OBRA A PRECIO ALZADO PARA LA CONSTRUCCI&Oacute;N DE CASA HABITACI&Oacute;N QUE CELEBRAN EN CALIDAD DE CONTRATISTA <strong>LAS OLAS CONSTRUCCION Y TURISMO SA DE CV</strong> ACTUANDO A TRAV&Eacute;S DE SU REPRESENTANTE <strong>ING. MARTHA ALEJANDRA AVILES SALCEDO</strong> Y EN CALIDAD DE CONTRATANTE EL C. $nombre QUIENES SE RECONOCEN EXPRESA Y REC&Iacute;PROCAMENTE CON CAPACIDAD PLENA PARA OBLIGARSE, Y PARA TAL EFECTO, LA PRIMERA DE ELLAS SER&Aacute; IDENTIFICADA COMO LA <strong>&quot;CONTRATISTA&ldquo;</strong> Y LA SEGUNDA DE ELLAS SER&Aacute; IDENTIFICADA COMO LA <strong>&quot;CONTRATANTE&quot;;</strong> ADEM&Aacute;S SE HAR&Aacute; REFERENCIA A ELLAS DE MANERA CONJUNTA COMO &ldquo;LAS PARTES&quot;; EN ESTE SENTIDO, LAS PARTES MANIFIESTAN EN PRIMER LUGAR LAS SIGUIENTES:</span></p>
			
			<p style="text-align: center;"><strong>DECLARACIONES</strong></p>

			<ol style="list-style-type: upper-roman;">
			    <li><span>Declaran &ldquo;LAS PARTES&rdquo;, que LA CASA HABITACION por construir, objeto del presente contrato &nbsp; se localizara en el lote &nbsp;<strong>$lote &nbsp;Lote $loteNum Manzana $manzanaNum Seccion $seccion</strong></span></li>
			    <li><span>Declara LA &ldquo;CONTRATANTE&rdquo;, tener la posesi&oacute;n leg&iacute;tima del predio de referencia en el que se edificar&aacute; LA CASA HABITACION, as&iacute; como con la capacidad financiera suficiente y material para el cumplimiento de las obligaciones que se contratan en este instrumento, y las que se deriven ante cualquier instituci&oacute;n, empresa y/o gobierno.</span></li>
			    <li><span>Declara la &ldquo;CONTRATISTA&rdquo; que conoce las caracter&iacute;sticas de la obra a edificar que se encuentra capacitado para llevar a cabo su ejecuci&oacute;n, y tiene inter&eacute;s en realizarla.</span></li>
			    <li><span>Declaran &ldquo;LAS PARTES&rdquo;, que previo a la celebraci&oacute;n del presente contrato han establecido las caracter&iacute;sticas, especificaciones y aceptado un presupuesto espec&iacute;fico de la obra objeto del contrato, y el mismo constituir&aacute; parte del presente contrato.</span></li>
			    <li><span>Declara la &ldquo;CONTRATISTA&rdquo; que la empresa se encuentra debidamente constituida ante el titular de la notar&iacute;a No. 17 de la Cuidad de Tijuana Baja California, bajo la escritura &mdash; de fecha &mdash;; inscrita en el registro publico de la propiedad y de comercio con el folio&hellip;.., y con registro federal de contribuyentes: ----; se&ntilde;alando como domicilio &hellip;&hellip;</span></li>
			    <li><span>Que la &ldquo;CONTRATISTA&rdquo;, es representada por conducto de &hellip;&hellip;.. apoderado que cuenta con la capacidad legal y con amplias facultades, necesarias para celebrar el presente contrato en su representaci&oacute;n, personalidad brindada conforme al poder que le fue otorgado debidamente formalizado en la escritura p&uacute;blica numero 40,914 &nbsp;volumen 714 de fecha 22 de junio de 2021 protocolizada por la notaria publica numero &nbsp;17, en Tijuana, Baja California, debidamente inscrito &nbsp;ante el Registro P&uacute;blico de la Propiedad y de Comercio de la ciudad de Tijuana, Baja California, bajo el folio mercantil numero 059097 &nbsp;de fecha 30 de mayo 2022</span></li>
			    <li><span>Que para los efectos de facturaci&oacute;n y cobro de los trabajos motivo de este contrato, su Registro Federal de Contribuyentes es: OCT-830325-RRA</span></li>
			    <li><span style='background:yellow;'>La &ldquo;CONTRATANTE&rdquo;, de nacionalidad mexicana, que se identifica con credencial expedida por el Instituto Nacional de Electores (INE o IFE) con numero: $elector;</span><span style='font-size:10.0pt;'>&nbsp;quien cuenta con el registro federal de contribuyentes: <span style="background:;;">$rfc;</span> se&ntilde;alando como domicilio para los efectos del presente instrumento <span style="background:;;">&hellip;&hellip; y que acredita la legal posesi&oacute;n/propiedad, del lote asignado para la edificaci&oacute;n que se contrata, y que consta en &hellip;.&rdquo;contrato de compraventa&hellip; / (escritura) &hellip;datos completos... (nota: SI COINCIDE EL nombre, ok, en caso de que nos sea as&iacute;, entonces revisar y redactar la declaraci&oacute;n y observar la legitimidad que le permita contratar y comprometerse a pagar&hellip; ),&nbsp;</span></span></li>
			</ol>
			<p><span>Estando &ldquo;LAS PARTES&rdquo; de acuerdo en el contenido de las declaraciones, las cuales reconocen mutua y rec&iacute;proca, la capacidad jur&iacute;dica y la personalidad de quienes intervienen para ser ratificadas y por contener la verdad, sin existir error, dolo, violencia, mala fe o vicio alguno en el consentimiento que pudieran invalidar al mismo, no mediando incapacidad legal entre &ldquo;LAS PARTES&rdquo;, es que convienen en celebrar el presente contrato y sirven en someterse a las siguientes:</span></p>
			
			<p style="text-align: center;"><strong><span>CLÁUSULAS</span></strong></p>

			<p><strong><span>CLÁUSULA PRIMERA. OBJETO DEL CONTRATO</span></strong></p>
			<p><span>El objeto del presente contrato de obra a precio alzado, ser&aacute; la realizaci&oacute;n de la obra que se identifica como la edificaci&oacute;n de $descripcion una superficie de $m2.</span></p>
			<p><span>Lo anterior, sobre EL INMUEBLE precisado en las caracter&iacute;sticas, especificaciones, vol&uacute;menes y costos, que constan en el cat&aacute;logo de conceptos y planos que se adjunta como <span style="background:yellow;">anexos A, B, C&hellip;etc.,.</span>&nbsp;</span></p>
			<p><span>La &ldquo;CONTRATISTA&rdquo; podr&aacute; subcontratar a terceros para realizar la obra de conformidad con lo establecido en la normatividad local. Sin embargo, no podr&aacute; trasladar a ning&uacute;n tercero su obligaci&oacute;n de responder por las obligaciones adquiridas en el presente contrato.</span></p>
			<p><strong><span>CLÁUSULA SEGUNDA. PLAZO PARA LA EJECUCI&Oacute;N DE LA OBRA</span></strong></p>
			<p><span>La &ldquo;CONTRATISTA&rdquo; se obliga a llevar a cabo la obra a partir del d&iacute;a $contrato y a terminar su ejecuci&oacute;n antes del d&iacute;a $contratoFin.</span></p>
			<p><span>En caso de que La &ldquo;CONTRATISTA&rdquo; no est&eacute; en condiciones de realizar la entrega de la obra de acuerdo a lo establecido, deber&aacute; dar aviso a LA CONTRATANTE de manera inmediata. &nbsp; Definir, establecer e incluir en este instrumento, las CAUSAS que se establece que lo justifica&hellip; y acordar el plazo y nueva fecha de conclusi&oacute;n y entrega.</span></p>
			<p><strong><span>CLÁUSULA TERCERA. CONTRAPRESTACI&Oacute;N POR LA REALIZACI&Oacute;N DE LA OBRA</span></strong></p>
			<p><span>La &ldquo;CONTRATANTE&rdquo; se compromete a pagar a La &ldquo;CONTRATISTA&rdquo; la cantidad de $ $costo ( &mdash;- mil dolares 00/100 en Moneda Americana), por concepto de contraprestaci&oacute;n por la obra a realizar, este precio incluye el costo de todos los materiales y trabajos que la obra requiera, por lo cual, la parte CONTRATISTA se compromete a adquirir los materiales necesarios, incluyendo el Impuesto al Valor Agregado y contratar y pagar todos los servicios personales que le permitan ejecutar la obra, sin ning&uacute;n costo adicional para la La &ldquo;CONTRATANTE&rdquo;.</span></p>
			<p><span>En caso de que La &rdquo;CONTRATANTE&rdquo; incumpla alguno de los compromisos establecidos en este contrato debera cubrir una penalidad del 10% por concepto de mora sobre el adeudo vencido hasta la fecha del pago., &nbsp;este correr&aacute; a partir del dia inmediato posterior a la fecha de vencimiento.</span></p>
			<p><span>La &ldquo;CONTRATISTA&rdquo; podr&aacute; aumentar el precio establecido en este contrato si las obras realizadas resultan mayores a lo contratado inicialmente, siempre y cuando exista un presupuesto autorizado por la parte &ldquo; CONTRATANTE &ldquo;por lo que se deber&aacute; de convenir previamente a su ejecuci&oacute;n obteni&eacute;ndose la autorizaci&oacute;n expresa y por escrito entre &ldquo;LAS PARTES&rdquo;.</span></p>
			<p><span>En caso de que haya afectaci&oacute;n por inflaci&oacute;n o fluctuaci&oacute;n econ&oacute;mica del precio de los materiales las partes se someter&aacute;n a ajustes de precio sin perjuicio de La &ldquo;CONTRATISTA&rdquo;. eso es &nbsp;en caso de que el tipo de cambio del peso en relaci&oacute;n al dolar tenga una variaci&oacute;n mayor al 10%, tomando como base el valor correspondiente al vigente en la fecha de firma del contrato pactado en dolares.&nbsp;</span></p>
			<p><span>En caso &nbsp;de que el lote objeto del cual se est&eacute; efectuando la obra, tenga un gravamen y/o financiamiento &nbsp;pendiente de liberar con el desarrollador, La &ldquo;CONTRATISTA&rdquo; quedar&aacute; libre de cualquier responsabilidad civil y/o moral.</span></p>
			<p><strong><span>CLÁUSULA CUARTA. FORMA DE PAGO</span></strong></p>
			<p><span>Todos los pagos se realizar&aacute;n mediante nuestra gestora de cobranza, &ldquo;Cobroplan S. C.&rdquo; mediante el sitio web:&nbsp;</span><a href="http://www.cobroplan.mx"><span>www.cobroplan.mx</span></a><span>, o bien descargando la aplicaci&oacute;n en Tiendas Google apps, y Apple Store, pudiendo ser &nbsp;pagos con Tarjeta de cr&eacute;dito, Tarjeta de d&eacute;bito, o transferencia directa desde su banco en linea, con el numero de referencia que le sera proporcionado por La &ldquo;CONTRATISTA&rdquo;.</span></p>
			<p><span>En todo caso La &ldquo;CONTRATISTA&rdquo; se encuentra obligada a emitir los recibos por los pagos efectivamente realizados por LA &ldquo;CONTRATANTE&rdquo;, no obstante, ser&aacute; obligaci&oacute;n de La &ldquo;CONTRATANTE&rdquo; recabar el recibo correspondiente.</span></p>
			<p><strong><span>La cantidad establecida en la clÁusula anterior, se efectuar&aacute; de la siguiente manera:</span></strong></p>
			<ul class="decimal_type" style="list-style-type: disc;margin-left:0in;">
			    <li><span>-El 40% (cuarenta por ciento) a la firma del contrato.</span></li>
			    <li><span>-El 40% (cuarenta por ciento) en 4 mensualidades consecutivas&nbsp;</span></li>
			    <li><span>Fecha de pago de &nbsp; la primera parcialidad el d&iacute;a &ndash; de &ndash; de 20&ndash;, &nbsp;la cantidad de $ $costoDividido ( 00/100 M.A.)</span></li>
			    <li><span>Fecha de pago de la segunda parcialidad el d&iacute;a &mdash; de &ndash; de 20&ndash;, la cantidad de $ $costoDividido ( 00/100 M.A.)</span></li>
			    <li><span>Fecha de pago de la tercera parcialidad el d&iacute;a &mdash; de &ndash; de 20&ndash;, la cantidad de $ $costoDividido ( 00/100 M.A.)</span></li>
			    <li><span>Fecha de pago de la cuarta parcialidad el d&iacute;a &mdash; de &ndash; de 20&ndash;, la cantidad de $ $costoDividido ( 00/100 M.A)</span></li>
			    <li><span>-El 20% la fecha pago deber&aacute; efectuarse a la entrega la obra de edificaci&oacute;n a m&aacute;s tardar el d&iacute;a &ndash; de &ndash; de 20&ndash;.</span></li>
			</ul>
			<p><span>En el caso de que el La &ldquo;CONTRATANTE&rdquo; no cumpla con su obligaci&oacute;n de pago tanto con la financiera, y/o desarrolladora La &ldquo;CONTRATISTA&rdquo;, tendr&aacute; el derecho de exigir el cumplimiento de las obligaciones, as&iacute; como demandar por da&ntilde;os y perjuicios ante los Tribunales competentes, asi mismo tendr&aacute; el derecho de retirar cualquier material, herramienta u objeto que sea de su propiedad o que se haya adquirido para la construcci&oacute;n, esto para garantizar el pago pendiente por parte del presente contrato.</span></p>
			<p><strong><span>CLÁUSULA QUINTA. &Uacute;NICO PAGO</span></strong></p>
			<p><span>&ldquo;LAS PARTES&rdquo; convienen que el pago establecido en las clÁusulas anteriores ser&aacute; la &uacute;nica remuneraci&oacute;n que La &ldquo;CONTRATISTA&rdquo; recibir&aacute; como pago por la realizaci&oacute;n de la obra, no obstante podra exigir pagos adicionales seg&uacute;n lo requiera la obra y/o procesos, lo que deber&aacute; de acordarse, y autorizarse por &ldquo;LAS PARTES&rdquo; de manera formal, por escrito y previamente.</span></p>
			<p><strong><span>CLÁUSULA SEXTA. OBLIGACIONES DE LA CONTRATISTA</span></strong></p>
			<p><span>La &ldquo;CONTRATISTA&rdquo; se obliga a aplicar su capacidad y conocimientos para cumplir satisfactoriamente con la entrega de la obra solicitada, y a garantizar el debido cumplimiento de todas y cada una de las obligaciones establecidas a su cargo, en este instrumento contractual, por lo que para esos fines otorgar&aacute; las siguientes Fianzas a favor del contratante:</span></p>
			<ul style="list-style-type: disc;margin-left:0in;">
			    <li><strong><span>FIANZA DE CUMPLIMIENTO -</span></strong><span>&nbsp;es una fianza de garant&iacute;a que ampare el cumplimiento fiel y exacto de todas y cada una de las estipulaciones contenidas en el presente contrato.</span></li>
			    <li><strong><span>FIANZA DE VICIOS OCULTOS</span></strong><span>&nbsp;- para responder por la reparaci&oacute;n de los defectos, vicios ocultos y cualquier otra responsabilidad por resulten en el plazo legal, la cual ser&aacute; emitida una compa&ntilde;&iacute;a afianzadora autorizada en los t&eacute;rminos de Ley aplicable.&nbsp;</span></li>
			</ul>
			<p><span>Dichas fianzas seran entregadas en un lapso no mayor a los 10 dias habiles posteriores a la firma del presente contrato.</span></p>
			<p><strong><span>&nbsp;CLÁUSULA S&Eacute;PTIMA. OBLIGACIONES DE LA CONTRATANTE</span></strong></p>
			<p><span>Constituir&aacute;n obligaciones de La &ldquo;CONTRATANTE&rdquo;, las siguientes:</span></p>
			<p style='margin-top:12.0pt;margin-right:0in;margin-left:22><span>a) Cumplir los compromisos de pago con La &ldquo;CONTRATISTA&rdquo;, y respetar el conjunto de obligaciones que se establecen en este Contrato, para lo cual se firmaran pagares de cada obligacion de pago adquirida en este documento.</span></p>
			<p style='margin-top:12.0pt;margin-right:0in;margin-left:22><span>b) Proporcionar a La &ldquo;CONTRATISTA&rdquo; toda informaci&oacute;n &uacute;til para la correcta ejecuci&oacute;n de la obra, en particular sobre sus necesidades en relaci&oacute;n a la misma y la utilidad que esta debe presentar;</span></p>
			<p style='margin-top:12.0pt;margin-right:0in;margin-left:22><span>c) Colaborar plenamente con La &ldquo;CONTRATISTA&rdquo; con el fin de conseguir una correcta ejecuci&oacute;n de la obra de edificaci&oacute;n.</span></p>
			<p><span>En caso de no proporcionar la informaci&oacute;n indicada, o de no colaborar, La &ldquo;CONTRATISTA&rdquo; se reserva el derecho de informar por escrito a La &ldquo;CONTRATANTE&rdquo; de la imposibilidad de respetar los plazos previstos para la ejecuci&oacute;n de la obra.</span></p>
			<p><strong><span>CLÁUSULA OCTAVA. RELACI&Oacute;N NO LABORAL</span></strong></p>
			<p><span>&ldquo;LAS PARTES&rdquo; declaran que no hay relaci&oacute;n laboral alguna entre ellas, por lo que no se crear&aacute; subordinaci&oacute;n de ninguna especie, y en ning&uacute;n supuesto operar&aacute; la figura jur&iacute;dica de patr&oacute;n, patr&oacute;n solidario o sustituto.</span></p>
			<p><span>Especialmente La &ldquo;CONTRATISTA&rdquo; asume la responsabilidad legal con relaci&oacute;n a terceros que esta contrate., Por lo anterior, si La &ldquo;CONTRATISTA&rdquo; realiza la contrataci&oacute;n de uno o varios trabajadores, libera de toda responsabilidad a La &ldquo;CONTRATANTE&rdquo; en caso de conflictos laborales.&nbsp;</span></p>
			<p><span>La &ldquo;CONTRATANTE&rdquo; no adquiere ni reconoce obligaci&oacute;n alguna de car&aacute;cter laboral a favor de La &ldquo;CONTRATISTA&rdquo;, en virtud de no ser aplicables a la relaci&oacute;n contractual que consta en este instrumento los art&iacute;culos 1 y 8 de la Ley Federal del Trabajo, por lo que La &ldquo;CONTRATISTA&rdquo; no ser&aacute; considerada como trabajador para ning&uacute;n efecto legal.</span></p>
			<p><strong><span>CLÁUSULA NOVENA. RESCISI&Oacute;N DEL CONTRATO</span></strong></p>
			<p><span>La &ldquo;CONTRATANTE&rdquo; podr&aacute; rescindir el presente contrato sin necesidad de juicio, por cualquiera de las siguientes causas imputables a La &ldquo;CONTRATISTA&rdquo;:</span></p>
			<p style='margin-top:12.0pt;margin-right:0in;margin-left:22><span>a) Por realizar la obra de manera deficiente, o por no apegarse a lo estipulado en el presente contrato;</span></p>
			<p style='margin-top:12.0pt;margin-right:0in;margin-left:22><span>b) Por no observar la discreci&oacute;n debida respecto de la informaci&oacute;n a la que tenga acceso como consecuencia de este contrato;</span></p>
			<p style='margin-top:12.0pt;margin-right:0in;margin-left:22><span>c) Por suspender injustificadamente la realizaci&oacute;n de la obra.</span></p>
			<p><span>Para los efectos a que se refiere esta clÁusula, La &ldquo;CONTRATANTE&rdquo; comunicar&aacute; por escrito a La &nbsp;&ldquo;CONTRATISTA&rdquo; el incumplimiento en que esta haya incurrido, para que en un t&eacute;rmino de diez d&iacute;as h&aacute;biles exponga lo que a su derecho convenga y aporte, en su caso, las pruebas correspondientes.</span></p>
			<p><span>Transcurrido el t&eacute;rmino se&ntilde;alado en el p&aacute;rrafo anterior La &ldquo;CONTRATANTE&rdquo;, tomando en cuenta los argumentos y pruebas ofrecidos por La &ldquo;CONTRATISTA&rdquo;, determinar&aacute; de manera fundada y motivada si resulta procedente o no rescindir el contrato, y comunicar&aacute; por escrito a La &ldquo;CONTRATISTA&rdquo; dicha determinaci&oacute;n.</span></p>
			<p><strong><span>CLÁUSULA D&Eacute;CIMA. DA&Ntilde;OS Y PERJUICIOS</span></strong></p>
			<p><span>Queda expresamente convenido que la falta de cumplimiento a cualquiera de las obligaciones que aqu&iacute; se contraen, y aquellas otras que dimanan de las leyes vigentes, ser&aacute; motivo de rescisi&oacute;n del presente contrato, con el pago de da&ntilde;os y perjuicios que el incumplimiento cause a la contraparte que cumple.</span></p>
			<p><strong><span>CLÁUSULA DECIMOPRIMERA. FINALIZACI&Oacute;N Y RECEPCI&Oacute;N</span></strong></p>
			<p><span>Al finalizar la ejecuci&oacute;n de la obra, La &ldquo;CONTRATANTE&rdquo; se compromete a recibirla as&iacute; como a aceptarla, si la misma cumple con todos los requerimientos solicitados. Podr&aacute; sin embargo, emitir las reservas que considere necesarias en un periodo de 15 d&iacute;as despu&eacute;s de recibida la obra, cuando estime que un aspecto de la obra no cumple con los requerimientos solicitados, o bien, presente alg&uacute;n defecto.</span></p>
			<p><span>La obra se tendr&aacute; por recibida y aceptada, si al finalizar la ejecuci&oacute;n, La &ldquo;CONTRATANTE&rdquo; toma posesi&oacute;n del bien sin emitir reservas en el periodo se&ntilde;alado.</span></p>
			<p><span>La transmisi&oacute;n de los riesgos se producir&aacute; a partir de la aceptaci&oacute;n de la obra. Si fueron emitidas reservas, a partir de que estas hayan sido atendidas por La &ldquo;CONTRATISTA&rdquo; y finalmente aceptadas por La &ldquo;CONTRATANTE&rdquo;, salvo que exista retraso en la recepci&oacute;n de la obra, en tal caso, la obra se tendr&aacute; por entregada dando aviso por escrito a La &ldquo;CONTRATANTE&rdquo;.</span></p>
			<p><span>La aceptaci&oacute;n de la obra no supondr&aacute; eximir a La &ldquo;CONTRATISTA&rdquo; de las responsabilidades derivadas por vicios ocultos de la obra, de conformidad con la legislaci&oacute;n civil vigente.</span></p>
			<p><strong><span>CLÁUSULA DECIMOSEGUNDA. CONFIDENCIALIDAD</span></strong></p>
			<p><span>Toda la informaci&oacute;n que suministren &ldquo;LAS PARTES&rdquo;, ya sea durante la fase precontractual, de negociaci&oacute;n o durante la ejecuci&oacute;n de la obra, ser&aacute; proporcionada en t&eacute;rminos de estricta reserva y confidencialidad.</span></p>
			<p><strong><span>CLÁUSULA DECIMOTERCERA. MODIFICACIONES DEL CONTRATO.</span></strong></p>
			<p><span>El presente Contrato s&oacute;lo puede ser modificado mediante convenio escrito firmado por &ldquo;LAS PARTES&rdquo; contratantes.</span></p>
			<ol start="1" style="list-style-type: lower-alpha;margin-left:0in;">
			    <li><span>El contrato podr&aacute; modificarse por ampliaci&oacute;n a los requerimientos, de com&uacute;n acuerdo, pact&aacute;ndose de manera formal, los conceptos, volumen y montos que se modificar&aacute;n y adicionar&aacute;n a lo contratado originalmente.</span></li>
			    <li><span>Por detalles de forma que desvirt&uacute;en el contenido esencial o de fondo de la obra de edificaci&oacute;n motivo del presente contrato.</span></li>
			    <li><span>Por diferimiento en la fecha de pago que motive a su vez el retraso del cumplimiento por falta de materiales o bien de insatisfacci&oacute;n de las remuneraciones del personal que intervenga.</span></li>
			</ol>
			<p><strong><span>CLÁUSULA DECIMOCUARTA. COMUNICACI&Oacute;N ENTRE &ldquo;LAS PARTES&rdquo;.</span></strong></p>
			<p><span>Todo aviso, notificaci&oacute;n, requerimiento o comunicaci&oacute;n entre &ldquo;LAS PARTES&rdquo; respecto al objeto del presente contrato deber&aacute; realizarse por escrito en el domicilio se&ntilde;alado en la clÁusula siguiente por cada una de ellas.</span></p>
			<p><span>En caso de que alguna de &ldquo;LAS PARTES&rdquo; cambie su domicilio deber&aacute; comunicarlo a la otra, a m&aacute;s tardar cinco d&iacute;as antes de que tenga lugar el cambio de residencia, de lo contrario las notificaciones hechas al anterior domicilio surtir&aacute;n todos sus efectos. As&iacute; mismo, deber&aacute; de indemnizar a la otra por los gastos extraordinarios que se lleguen a realizar con motivo de los incumplimientos de las obligaciones de este contrato generados a partir de la falta de comunicaci&oacute;n.</span></p>
			<p><span>No obstante lo anterior, y siempre y cuando sea posible garantizar la autenticidad del emisor, del destinatario, y del contenido del mensaje, y con el objetivo de mantener una comunicaci&oacute;n eficaz entre LAS PARTES, se facilitan las siguientes direcciones de correo electr&oacute;nico y domicilios para oir y recibir todo tipo de notificaciones, el siguiente:</span></p>
			<p><strong><span>DE La &ldquo;CONTRATANTE&rdquo;:</span></strong></p>
			<p><strong><span style='font-size:12px;'>&nbsp;</span></strong></p>
			<p><strong><span style='font-size:12px;'>&nbsp;</span></strong></p>
			<p><strong><span>DE La &ldquo;CONTRATISTA&rdquo;:</span></strong></p>
			<p><span>&nbsp;</span></p>
			<p><span><br> <strong>CLÁUSULA DECIMOSEXTA. ABROGACI&Oacute;N DE ACUERDOS ANTERIORES.</strong></span></p>
			<p><span>&ldquo;LAS PARTES&rdquo; reconocen y aceptan que este Contrato, el presupuesto, sus anexos y sus adiciones constituyen un acuerdo total entre ellas, por lo que desde el momento de su firma dejan sin efecto cualquier acuerdo o negociaci&oacute;n previa, prevaleciendo lo dispuesto en este instrumento respecto de cualquier otro contrato o convenio.</span></p>
			<p><span>&ldquo;LAS PARTES&rdquo; podr&aacute;n dar por terminado anticipadamente el contrato cuando concurran razones de inter&eacute;s general, o bien, cuando por causas justificadas se extinga la necesidad contratada y se demuestre que de continuar con las obligaciones pactadas, se ocasionar&aacute; alg&uacute;n da&ntilde;o o perjuicio, o se determine la nulidad total o parcial de los actos que dieron origen al contrato, con motivo de la resoluci&oacute;n derivada de procesos judiciales, por lo que se reembolsar&aacute; &uacute;nicamente los recursos no aplicados, siempre que haya la demostraci&oacute;n debida y comprobada. La &ldquo;CONTRATISTA&rdquo; dar&aacute; aviso por escrito a La &ldquo;CONTRATANTE&rdquo;, de dicha circunstancia cuando menos con diez d&iacute;as naturales de anticipaci&oacute;n. &nbsp;&nbsp;</span></p>
			<p><strong><span>CLÁUSULA DECIMOSEPTIMA. RESCISI&Oacute;N DEL CONTRATO.</span></strong></p>
			<p><span>Proceder&aacute; la rescisi&oacute;n del contrato al d&iacute;a siguiente de vencida la aplicaci&oacute;n del monto l&iacute;mite de la garant&iacute;a de cumplimiento del mismo.&nbsp;</span></p>
			<p><span>As&iacute; tambi&eacute;n, cuando se ubique en alguno de los supuestos siguientes:</span></p>
			<p><span>Que no se cumpla con las obligaciones contractuales por causas que como consecuencia, causen da&ntilde;os o perjuicios graves a La &ldquo;CONTRATISTA&rdquo; o al predio y o las inmediaciones en que se ubique, as&iacute; como por no corresponder las exigencias de La &ldquo;CONTRATANTE&rdquo; a las especificaciones convenidas.</span></p>
			<p><span>Que La &ldquo;CONTRATANTE&rdquo; proporciones informaci&oacute;n falsa y que act&uacute;e con dolo o mala f&eacute; durante el procedimiento de contrataci&oacute;n, en la celebraci&oacute;n o durante su vigencia, o bien, en la presentaci&oacute;n o desahogo de alguna queja en una audiencia de conciliaci&oacute;n o de una inconformidad.&nbsp;</span></p>
			<p><span>Por no apegarse al contrato.</span></p>
			<p><span>Por impedir La &ldquo;CONTRATANTE&rdquo; el desempe&ntilde;o normal de labores durante la prestaci&oacute;n del servicio contratado.&nbsp;</span></p>
			<p><span>&nbsp;La &ldquo;CONTRATISTA&rdquo; podr&aacute; suspender temporalmente, en todo o en parte el servicio contratado, en cualquier momento por causas justificadas o por razones de inter&eacute;s general, sin que ello implique su terminaci&oacute;n definitiva, el presente contrato podr&aacute; continuar produciendo todos sus efectos legales, una vez que hayan desaparecido las causas que motivaron la suspensi&oacute;n. &nbsp;</span></p>
			<p><strong><span>CLÁUSULA DECIMOCTAVA. RETRASO O INCUMPLIMIENTO</span></strong></p>
			<p><span>&ldquo;LAS PARTES&rdquo; convienen que podra haber un retraso de 15 dias sin que eso signifique una accion por incumplimiento del &ldquo;CONTRATISTA&rdquo;, ninguna ser&aacute; responsable de cualquier retraso o incumplimiento de este contrato que resulte por causas de fuerza mayor o caso fortuito.</span></p>
			<p><strong><span>CLÁUSULA DECIMANOVENA. BUENA FE.</span></strong></p>
			<p><span>&ldquo;LAS PARTES&rdquo; manifiestan que en la celebraci&oacute;n del presente contrato no existe error, dolo, violencia o mala fe que pudiera invalidarlo.</span></p>
			<p><strong><span>CLÁUSULA VIGESIMA. ENCABEZADOS</span></strong></p>
			<p><span>Los encabezados de las clÁusulas utilizados en el presente contrato son meramente demostrativos, sirven para guiar sobre el contenido de las mismas, bajo ninguna circunstancia se tomar&aacute;n dichos encabezados como un texto explicativo, y mucho menos limitativo.</span></p>
			<p><strong><span>CLÁUSULA VIGESIMA PRIMERA. SOLUCI&Oacute;N DE CONFLICTOS Y JURISDICCI&Oacute;N</span></strong></p>
			<p><span>&ldquo;LAS PARTES&rdquo; acuerdan que en caso de presentarse diferencias o disputas por virtud de la interpretaci&oacute;n, cumplimiento y ejecuci&oacute;n del presente Contrato, tratar&aacute;n razonablemente de resolverlas en forma amistosa, a trav&eacute;s de un proceso de mediaci&oacute;n y/o conciliaci&oacute;n que ser&aacute; voluntario y tendr&aacute; un car&aacute;cter previo a cualquier otro. De continuar la controversia, &ldquo;LAS PARTES&rdquo; se someten voluntaria y expresamente a la jurisdicci&oacute;n y competencia de los Juzgados y Tribunales del Poder Judicial del Estado de Baja California con residencia en la ciudad de Tijuana, que conforme a derecho deban conocer el asunto en raz&oacute;n del lugar en el que es firmado el contrato, con renuncia a su propio fuero en caso que este les aplique y sea procedente por raz&oacute;n de domicilio, vecindad, o por cualquier otra naturaleza.</span></p>
			<p><span>Le&iacute;do que fue el presente contrato y enteradas las partes del contenido y alcances de todas y cada una de las clÁusulas del mismo, lo firman en duplicado de com&uacute;n acuerdo en Tijuana, Baja California a 15 de agosto de 2022.</span></p>
			<p style="text-align: center;"><span><br>&nbsp;<br>&nbsp;<br> <strong>______________________________</strong></span></p>
			<p style="text-align: center;"><strong><span>DE LA CONTRATISTA:</span></strong></p>
			<p style="text-align: center;"><strong><span style='font-size:11px;'>&nbsp;</span></strong></p>
			<p style="text-align: center;"><strong><span><br>&nbsp;<br>&nbsp;______________________________</span></strong></p>
			<p style="text-align: center;"><strong><span style='font-size:11px;'><br>&nbsp;</span></strong><strong><span>DE LA CONTRATANTE</span></strong><strong><span style='font-size:11px;'><br>&nbsp;</span></strong></p>
			<p style="text-align: center;"><strong><span style='font-size:11px;'>&nbsp;</span></strong></p>
			<p style="text-align: center;"><strong><span style='font-size:11px;'>&nbsp;</span></strong></p>
			<p style="text-align: center;"><strong><span style='font-size:11px;'>TESTIGOS :</span></strong></p>
			<p style="text-align: center;"><strong><span style='font-size:11px;'>&nbsp;</span></strong></p>
			<p style="text-align: center;"><strong><span style='font-size:11px;'>____________________________________</span></strong></p>
			<p style="text-align: center;"><strong><span style='font-size:11px;'>&ndash;</span></strong></p>
			<p style="text-align: center;"><strong><span style='font-size:11px;'>&nbsp;</span></strong></p>
			<p style="text-align: center;"><strong><span style='font-size:11px;'>____________________________________</span></strong></p>
		</div>
EOF;
		$fechaPdf = date('d-m-Y');

		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->lastPage();

		if ($visualizar == 1) {
			$pdf->Output("Contrato.pdf", 'I');
		} elseif ($descargar == 1) {
			$nombrePdf = "Contrato.pdf";
			$archivo = $pdf->Output(ROOT_DIR . "/data/tmp/$nombrePdf", 'F');
			return $nombrePdf;
		}
	}

}