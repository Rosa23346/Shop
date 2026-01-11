<?php
include 'config.php';
include ("../libraries/img.php");
include ("../libraries/auth.php");

isLogin();


$page = "slideshow.php";
if(isset($_GET['p'])){
	$p = $_GET['p'];
	switch($p){
		// case "dashboard":
		// 	$page = "dashboard.php";
		// 	break;
		case "slideshow":
			$page = "slideshow.php";
			break;
		case "category":
			$page = "category.php";
			break;
		case "brand":
			$page = "brand.php";
			break;
		
		case "product":
			$page = "product.php";
			break;
		case "user":
			$page = "user.php";
			break;
		case "page":
			$page = "page.php";
			break;
		case "editpage":
			$page = "editpage.php";
			break;

	}
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include "includes/head.php" ?>
<body>
	<div class="wrapper">
		<?php include "includes/sidebar.php" ?>
		<div class="main">
			<?php include "includes/header.php" ?>
			<?php require $page ?>
			<?php include "includes/footer.php"; ?>
		</div>
	</div>
	<script src="js/app.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php mysqli_close($conn); ?>