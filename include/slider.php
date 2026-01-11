<?php 
$sql ="select * from tbl_slideshow where enable='1' order by ssorder asc";
$result = mysqli_query($conn,$sql);
$num = mysqli_num_rows($result);
?>
<section id="slider"><!--slider-->
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div id="slider-carousel" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <?php 
                        for($i=0; $i <$num; $i++){
                        ?>
                        <li data-target="#slider-carousel" data-slide-to="<?=$i?>" class="<?= $i==0?"active":"" ?>"></li>
                        <?php 
                        }
                        ?>
                    </ol>
                    
                    <div class="carousel-inner">
                        <?php
                        $i=1;
                        while($row=mysqli_fetch_array($result)){

                        ?>
                        <div class="item <?=$i==1?"active":""?>">
                            <div class="col-sm-6">
                                <h1 class="black"><?=$row['title']?></h1>
                                <h2><?=$row['subtitle']?></h2>
                                <p><?=$row['text']?></p>
                                <a href="<?=$row['link']?>" class="btn btn-default get">Get it now</a>
                            </div>
                            <div class="col-sm-6">
                                <img src="./admin/img/slideshow/<?=$row['img']?>" class="girl img-responsive" alt="" />
                                <img src="images/home/pricing.png"  class="pricing" alt="" />
                            </div>
                        </div>
                        <?php
                        $i=0;
                        }?>
                    </div>
                    
                    <a href="#slider-carousel" class="left control-carousel hidden-xs" data-slide="prev">
                        <i class="fa fa-angle-left"></i>
                    </a>
                    <a href="#slider-carousel" class="right control-carousel hidden-xs" data-slide="next">
                        <i class="fa fa-angle-right"></i>
                    </a>
                </div>
                
            </div>
        </div>
    </div>
</section><!--/slider-->