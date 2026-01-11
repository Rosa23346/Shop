<?php
$sql = "SELECT * FROM tbl_page where pageid=$pageid";
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_array($result);

?>

<div id="contact-page" class="container">
	<div class="bg">
		<div class="row">    		
			<div class="col-sm-12">    			   			
				<h2 class="title text-center"><?=($row['pagetitle'])?></h2>    			    				    				
				<div class="col-sm-12"><?=($row['pagecontent'])?></div>
			</div>			 		
		</div>    	 
	</div>	
</div><!--/#contact-page-->
<!-- 
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript" src="js/gmaps.js"></script>
<script src="js/contact.js"></script>
<script src="js/price-range.js"></script>
<script src="js/jquery.scrollUp.min.js"></script>
<script src="js/jquery.prettyPhoto.js"></script>
<script src="js/main.js"></script> -->
