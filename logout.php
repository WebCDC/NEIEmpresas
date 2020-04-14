<?php
	/**
	 * Termina a sessão e deixa o servidor 
	 * à espera que seja feita uma nova conexão 
	 * enviando o user para o index.
	 * 
	 * @author Rui Coelho
	 */
	ob_start();
	session_start();
	
	if (isset($_SESSION)) {
		session_unset();
		session_destroy();
	}
	
	header("Location: index.php");
	exit();
?>