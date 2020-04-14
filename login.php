<?php 
	/**
	 * @author Rui Coelho
	 */
	include('navbar.php'); 
?>

<?php startblock('head') ?>
<meta name="description" content="Acesso ao NEI para Empresas">
<title>NEI - Login</title>
<?php endblock() ?>

<?php if( !($_POST) ) : ?>
	<?php startblock('body') ?>
		<section id="lgin">
			<div class="container">
			<form class="form-signin col-8 offset-2 " role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
			method="post">
				<input type="text" class="form-control" name="username" placeholder="utilizador" required autofocus>
				
				<input type="password" class="form-control mt-2" name="password" placeholder="password" required>
				
				<button class="btn btn-lg btn-primary btn-block mt-3" type="submit" name="login">Login</button>
				
				<h5 align="center" style="color:red">
					<?php if(array_key_exists('login_msg',$_SESSION)){echo $_SESSION['login_msg']; unset($_SESSION['login_msg']);}?>
				</h5>
			</form>
			</div>
		</section>
	<?php endblock() ?>
	
<?php else: ?>
	<?php
		/**
	   * Comunicação do site com a base de dados do NEI
	   * bloqueia acessos indevidos aos apontamentos
	   * por parte de externos.
	   * 
	   * @author Rui Coelho
	   * 
	   */
		require_once('pHash.php');
		
		if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {
			// Create connection
			$conn = new mysqli($servername, $username, $password);
			$conn->select_db("aauav-nei"); 
			if ($stmt = $conn->prepare("SELECT e_login, e_name, e_pwd, e_access FROM empresas WHERE (e_login=? OR e_email=?) AND e_active=9")) {
				$stmt->bind_param("ss", $_POST['username'], $_POST['username']);
				$stmt->execute();
				$result = $stmt->get_result();
				if ($result->num_rows > 0) {
					$row = $result->fetch_assoc();
					$wp_hasher = new PasswordHash(8, TRUE);
					if($wp_hasher->CheckPassword($_POST['password'], $row["e_pwd"])) {
						$_SESSION['valid'] = true;
						$_SESSION['timeout'] = time();
						$_SESSION['uname'] = $row["e_name"];
						$_SESSION['ulogin'] = $row["e_login"];
						$_SESSION['uaccess'] = $row["e_access"];
						if (isset($_SESSION['after_login'])) {
							header("Location: " . $_SESSION['after_login']);
							unset($_SESSION['after_login']);
						}else{
							header('Location: index.php');
						};
						exit();
					} else {
						$_SESSION['login_msg'] = 'Password Inválida!';
					}
				}else{
					$_SESSION['login_msg'] = 'Utilizador Inválido!';
				}
			}
			$conn->close();
			header('Location: '.$_SERVER['PHP_SELF']);
			die();
		}
		?>
<?php endif; ?>
	