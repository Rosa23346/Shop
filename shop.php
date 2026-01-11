<?php
$sql = "SELECT * FROM tbl_category order by c_id asc";
$resultcategory = mysqli_query($conn,$sql);

$sql = "SELECT * FROM tbl_brand order by b_id asc";
$resultbrand = mysqli_query($conn,$sql);
// $sql = "select * from tbl_slideshow order by ssorder asc limit ". MAXPERPAGE ." offset $offset";

$option = "";
if(isset($_GET['s_category'])){
	$category = ($_GET['s_category']);
	$option = "AND categoryid= (SELECT c_id FROM tbl_category WHERE c_name = '$category')";
}
if(isset($_GET['s_brand'])){
	$brand = ($_GET['s_brand']);
	$option = "AND brandid= (SELECT b_id FROM tbl_brand WHERE b_name = '$brand')";
}
// echo $option;
$sqlAllProduct = "SELECT * FROM tbl_product WHERE p_enable = '1' ".$option." order by p_id asc";
$resultproduct = mysqli_query($conn,$sqlAllProduct);
$numrow = mysqli_num_rows($resultproduct);
$num_pages = ceil($numrow/MAXPERPAGE);

$pg =1;
$offset = 0;
if(isset($_GET['pg'])){
	$pg = $_GET['pg'];
	$offset = MAXPERPAGE*($pg-1);
}

$sqlProPerPage = "SELECT * FROM tbl_product WHERE p_enable = '1' " . $option . " order by p_id asc limit " . MAXPERPAGE . " OFFSET $offset";
$resultproduct = mysqli_query($conn,$sqlProPerPage);
// $numrow = mysqli_num_rows($resultproduct);
// echo $numrow;

?>
<section>
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
				<div class="left-sidebar">
					<h2>Category</h2>
					<div class="panel-group category-products" id="accordian"><!--category-productsr-->
							
						<?php
						while($row = mysqli_fetch_array($resultcategory)){
						?>
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4 class="panel-title"><a href="index.php?p=shop&s_category=<?=($row['c_name'])?>"><?=($row['c_name'])?></a></h4>
							</div>
						</div>
						<?php
						}
						?>

					</div><!--/category-productsr-->
				
					<div class="brands_products"><!--brands_products-->
						<h2>Brands</h2>
							
						<?php
						while($row = mysqli_fetch_array($resultbrand)){
						?>
						<div class="brands-name">
							<ul class="nav nav-pills nav-stacked">
								<li><a href="index.php?p=shop&s_brand=<?=($row['b_name'])?>"><?=($row['b_name'])?></a></li>

							</ul>
						</div>

						<?php
						}
						?>

					</div><!--/brands_products-->
					
					<div class="price-range"><!--price-range-->
						<h2>Price Range</h2>
						<div class="well">
								<input type="text" class="span2" value="" data-slider-min="0" data-slider-max="600" data-slider-step="5" data-slider-value="[250,450]" id="sl2" ><br />
								<b>$ 0</b> <b class="pull-right">$ 600</b>
						</div>
					</div><!--/price-range-->
					
					<div class="shipping text-center"><!--shipping-->
						<img src="images/home/shipping.jpg" alt="" />
					</div><!--/shipping-->
					
				</div>
			</div>
			
			<div class="col-sm-9 padding-right">
				<div class="features_items"><!--features_items-->
					<h2 class="title text-center">Features Items</h2>
						
					<?php
					while($row = mysqli_fetch_array($resultproduct)){
					?>
					<div class="col-sm-4">
						<div class="product-image-wrapper">
							<div class="single-products">

								<div class="productinfo text-center">
									<img src="admin/img/product/<?=($row['p_image'])?>" alt="" />
									<h2><?=($row['p_price'])?>$</h2>
									<p><?=($row['p_name'])?></p>
									<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
								</div>
								<div class="product-overlay">
									<div class="overlay-content">
										<h2>$56</h2>
										<p>Easy Polo Black Edition</p>
										<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
									</div>
								</div>
							</div>
							<div class="choose">
								<ul class="nav nav-pills nav-justified">
									<li><a href=""><i class="fa fa-plus-square"></i>Add to wishlist</a></li>
									<li><a href=""><i class="fa fa-plus-square"></i>Add to compare</a></li>
								</ul>
							</div>
						</div>
					</div>
					<?php
					}
					?>




				</div><!--features_items-->
				<!-- pagination -->
					<?php 
					if($num_pages>1){
					?>
					<nav aria-label="Page navigation example">
						<ul class="pagination justify-content-center" style="display: flex; justify-content: center;">
							<li class="page-item">
								<a class="page-link" href="index.php?p=shop&pg=<?=$pg>1?$pg-1:$pg?>" tabindex="-1" aria-disabled="true">Previous</a>   
							</li>
							<?php
							for($i=1;$i<=$num_pages;$i++) {
							?>
							<li class="page-item <?=$i==$pg?"active":""?>"><a class="page-link" href="index.php?p=shop&pg=<?=$i?>"><?=$i?></a></li>
							<?php
							}
							?>
							<li class="page-item">
								<a class="page-link" href="index.php?p=shop&pg=<?=$pg<$num_pages?$pg+1:$pg?>">Next</a>
							</li>
						</ul>
					</nav>
					<?php } ?>
				<!-- pagination -->
			</div>
		</div>
	</div>
</section>
