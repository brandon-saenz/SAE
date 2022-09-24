<?php
final class Modelos_Descarga extends Modelo {
	protected $_db = null;

	public function iniciarDb($db) {
    	if (!$this->_db) {
			$this->_db = $db;
        }
    }
	
	public function descargarArchivo($ubicacion, $nombre) {
		if( headers_sent() )
		die('Headers Sent');

		if(ini_get('zlib.output_compression'))
		ini_set('zlib.output_compression', 'Off');

		if( file_exists($ubicacion) ) {
			$fsize = filesize($ubicacion);
			$path_parts = pathinfo($ubicacion);
			$ext = strtolower($path_parts["extension"]);

			switch ($ext) {
				case "pdf": $ctype="application/pdf"; break;
				case "exe": $ctype="application/octet-stream"; break;
				case "zip": $ctype="application/zip"; break;
				case "doc": $ctype="application/msword"; break;
				case "xls": $ctype="application/vnd.ms-excel"; break;
				case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
				case "gif": $ctype="image/gif"; break;
				case "png": $ctype="image/png"; break;
				case "jpeg":
				case "jpg": $ctype="image/jpg"; break;
				// case "json": $ctype="application/json; charset=windows-1250"; break;
				default: $ctype="application/force-download";
			}

			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private",false);
			header("Content-Type: $ctype");
			header("Content-Disposition: attachment; filename=\"" . $nombre . "\";" );
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: ".$fsize);
			ob_clean();
			flush();
			readfile( $ubicacion );
		} else die('Archivo no encontrado');
	}
}