<?php
	/**
	 * @author Rui Coelho
	 */
	include('navbar.php'); 
?>

<?php startblock('head') ?>
<meta name="description" content="Acesso à base de dados (currículos)">
<title>NEI - Currículos</title>
<?php endblock() ?>

<?php startblock('custom_js') ?>
<script src="static/js/curriculos.js"></script>
<?php endblock() ?>

<?php if( !(isset($_SESSION['valid'])) OR !($_SESSION['valid']) ) : ?>
	<?php
		$_SESSION['after_login'] = $_SERVER['PHP_SELF'];
		header("Location: login.php");
		exit();
	?>

<?php else : ?>
	<?php startblock('body') ?>
	<!-- Curriculos -->
	<section class="container-fluid apt">
		<!-- Anos-->
		<div id="anos" class="row">

			<button class="btn apont-button col-lg-2 offset-lg-2 col-md-4 offset-md-1 col-sm-4 offset-sm-1 col-10 offset-1"
			 data-year="1">
				<h4 data-year="1"> 1º Ano</h4>
			</button>

			<button class="btn apont-button col-lg-2 offset-lg-1 col-md-4 offset-md-2 col-sm-4 offset-sm-2 col-10 offset-1"
			 data-year="2">
				<h4 data-year="2">2º Ano</h4>
			</button>

			<button class="btn apont-button col-lg-2 offset-lg-1 col-md-4 offset-md-1 col-sm-4 offset-sm-1 col-10 offset-1"
			 data-year="3">
				<h4 data-year="3">3º Ano</h4>
			</button>

			<button class="btn apont-button col-lg-2 offset-lg-3 col-md-4 offset-md-2 col-sm-4 offset-sm-2 col-10 offset-1"
			 data-year="4">
				<h4 data-year="4">4º Ano</h4>
			</button>

			<button class="btn apont-button col-lg-2 offset-lg-2 col-md-4 offset-md-4 col-sm-4 offset-sm-4 col-10 offset-1"
			 data-year="5">
				<h4 data-year="5">5º Ano</h4>
			</button>
		</div>
		<!-- Container que receberá as disciplinas e os documentos dinâmicamente-->
		<div class="container col-lg-12 hide" id="displayer">
		</div>
	</section>
	<?php endblock() ?>
<?php endif; ?>

