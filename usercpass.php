<?php 
	/**
	 * @author Rui Coelho
	 */
	include('navbar.php'); 
?>

<?php startblock('head') ?>
<meta name="description" content="Mudança de password de utilizador">
<title>NEI - Mudar Password</title>
<?php endblock() ?>

<?php if (isset($_SESSION['ok_msg'])) : ?>
	<?php startblock('body') ?>
		<section id="cdpasswd">
			<div class="container">
				<h5 align="center" style="color:green">
					<?php echo $_SESSION['ok_msg']; unset($_SESSION['ok_msg']); ?>
				</h5>
			</div>
		</section>
		<script>
		setTimeout(function () {
			window.location.href = "logout.php";
		}, 2000);
		</Script>
	<?php endblock() ?>
		<?php
			ob_end_flush();
			exit();
		?>
<?php endif; ?>

<?php startblock('body') ?>
	<?php
		require_once('pHash.php');
		if ($_POST && isset($_POST['chng_passwd']) && !empty($_POST['curr_password']) 
			&& !empty($_POST['new_password']) && isset($_SESSION['ulogin'])) {
			// Create connection
			$conn = new mysqli($servername, $username, $password);
			$conn->select_db("aauav-nei"); 
			if ($stmt = $conn->prepare("SELECT e_pwd FROM empresas WHERE e_login=?")) {
				$stmt->bind_param("s", $_SESSION['ulogin']);
				$stmt->execute();
				$result = $stmt->get_result();
				if ($result->num_rows > 0) {
					$row = $result->fetch_assoc();
					$wp_hasher = new PasswordHash(8, TRUE);
					if($wp_hasher->CheckPassword($_POST['curr_password'], $row["e_pwd"])) {
						if ($stmt2 = $conn->prepare("UPDATE empresas SET e_pwd=? WHERE e_login=?")) {
							$stmt2->bind_param("ss", $wp_hasher->HashPassword($_POST['new_password']), $_SESSION['ulogin']);
							$stmt2->execute();
							$_SESSION['ok_msg'] = 'Password alterada com sucesso!';
						}
					} else {
						$_SESSION['change_msg'] = 'Password atual Inválida!';
					}
				}else{
					$_SESSION['change_msg'] = 'Utilizador Inválido!';
				}
			}
			$conn->close();
			header("Location: " . $_SERVER['REQUEST_URI']);
			exit();
		}
	?>
	
	<?php if(isset($_SESSION['valid']) AND $_SESSION['valid']) : ?>
		<section id="cpasswd">
			<div class="container">
			<form class="form-signin col-8 offset-2 " role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
			method="post">
				<input type="text" class="form-control" name="username" placeholder="utilizador" disabled autofocus
				value="<?php echo $_SESSION['ulogin'];?>">
				
				<input type="password" class="form-control mt-2" name="curr_password" placeholder="password atual" required>
				
				<input type="password" class="form-control mt-2" name="new_password" placeholder="nova password" required>
				
				<button class="btn btn-lg btn-primary btn-block mt-3" type="submit" name="chng_passwd">Alterar</button>
				
				<h5 align="center" style="color:red">
					<?php if(array_key_exists('change_msg',$_SESSION)){echo $_SESSION['change_msg']; unset($_SESSION['change_msg']);}?>
				</h5>
			</form>
			</div>
		</section>
	<?php else : ?>
		<?php
			header("Location: login.php");
			exit();
		?>
	<?php endif; ?>
<?php endblock() ?>