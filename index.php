<?php 
	/**
	 * @author Rui Coelho
	 */
	include('navbar.php'); 
?>

<?php startblock('head') ?>
	<meta name="description" content="Currículos NEI">
	<title>NEI - AAUAv</title>
	<!-- page specific styles-->
	<style>
		@media(min-width: 992px){
			#mainNav:not(.navbar-shrink){
				background: transparent;
			}
		}
	</style>
<?php endblock() ?>

<?php startblock('body') ?>
    <!-- Header -->
    <header class="masthead">
        <div class="container">
            <div class="intro-text">
                <div class="intro-heading text-uppercase u-break-word" style="text-shadow: 1px 10px 25px rgb(0, 0, 0);">
                    Currículos - Núcleo de Estudantes de Informática
				</div>
				<div class="intro-lead-in" style="text-shadow: 1px 10px 25px rgb(0, 0, 0);">Programa para parceiros NEI-AAUAv</div>
            </div>
        </div>
    </header>
<?php endblock() ?>