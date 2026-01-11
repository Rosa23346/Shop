<?php
include 'config.php';
// $conn = mysqli_connect(HOST,USER,PASSWORD,DB) or die("Couldn't connect to database");
// include ("libraries/img.php");

$page = "home.php";
$pageid = 0;
if(isset($_GET['p'])){
	$p = $_GET['p'];
	switch($p){
		case "shop":
			$page = "shop.php";
			break;
		case "contact":
			$page = "page.php";
			$pageid = 1;
			break;
		case "about":
			$page = "page.php";
			$pageid = 2;
			break;
		
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include "include/head.php" ?>

<body>
	<header id="header">
		<?php include "include/header.php" ?>
		<?php include "include/midleheader.php" ?>
	</header>
	<?php include "include/nav.php" ?>
	<?php
	if($page=="shop.php"){
		include "include/advertisement.php";
	}
	?>
	<?php include $page ?>
	<?php include "include/footer.php" ?>

  
    <script src="js/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.scrollUp.min.js"></script>
	<script src="js/price-range.js"></script>
    <script src="js/jquery.prettyPhoto.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
<?php
mysqli_close($conn);
?>