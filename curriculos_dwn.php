<?php
/**
 * Início da comunicação com o servidor.
 * Introdução dos dados fornecidos pelo utilizador
 * e validação dos mesmos.
 * 
 * @author Rui Coelho
 * 
 */

	ob_start();
	session_start();
?>

<?php
/**
 * Validação de acesso a ficheiros por parte dos utilizadores
 * quando os mesmos possuem sessão iniciada passam a ter
 * permissão para visualizar e baixar os mesmos.
 * 
 * @author Rui Coelho
 *  
 */
	if (array_key_exists('uname',$_SESSION)
		&& array_key_exists('url',$_GET)
		&& file_exists($_GET['url']) ){
		
			$url = $_GET['url'];
			$path = explode("/", $url);
			$filename = $path[count($path)-1];
			$file = explode(".", $filename);
			$filetype = $file[count($file)-1];
			unset($path[count($path)-1]);
			
			header('Content-type: application/'.$filetype.'.pdf');
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			readfile($url);
	}else{
		header("Location: /empresas/curriculos.php");
		exit();
	}
?> 