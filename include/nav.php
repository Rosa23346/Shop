<!--header-bottom-->
<div class="header-bottom">
    <div class="container">
        <div class="row">
            <div class="col-sm-9">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="mainmenu pull-left">
                    <ul class="nav navbar-nav collapse navbar-collapse">
                        <li><a href="index.php" class="<?=($page=="home.php"?"active":"")?>">Home</a></li>
                        <li><a href="index.php?p=shop" class="<?=($page=="shop.php"?"active":"")?>">Shop</a></li> 
                        <li><a href="index.php?p=contact" class="<?=($p=="contact"?"active":"")?>">Contact Us</a></li>
                        <li><a href="index.php?p=about" class="<?=($p=="about"?"active":"")?>">About Us</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="search_box pull-right">
                    <input type="text" placeholder="Search"/>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /header-bottom -->