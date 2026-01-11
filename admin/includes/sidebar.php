<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="index.php">
            <span class="align-middle">AdminKit</span>
        </a>

        <ul class="sidebar-nav"> 
            <li class="sidebar-header">
                Pages
            </li>
            <!-- <li class="sidebar-item ">
                <a class="sidebar-link" href="index.php?p=dashboard">
                    <i class="align-middle" data-feather="archive"></i> <span class="align-middle">Dashboard</span>
                </a>
            </li> -->
            <li class="sidebar-item <?= ($page=="slideshow.php"?"active":"") ?>">
                <a class="sidebar-link" href="index.php?p=slideshow">
                    <i class="align-middle" data-feather="monitor"></i> <span class="align-middle">Slide Show</span>
                </a>
            </li>
            <li class="sidebar-item <?= ($page=="category.php"?"active":"") ?>">
                <a class="sidebar-link" href="index.php?p=category">
                    <!-- <i class="align-middle" data-feather="box"></i> -->
                    <i class="fa-solid fa-layer-group"></i> <span class="align-middle">Category</span>
                </a>
            </li>
            <li class="sidebar-item <?= ($page=="brand.php"?"active":"") ?>">
                <a class="sidebar-link" href="index.php?p=brand">
                    <!-- <i class="align-middle" data-feather="box"></i>  -->
                    <i data-feather="briefcase"></i>
                    <span class="align-middle">Brand</span>
                </a>
            </li>
            <li class="sidebar-item <?= ($page=="product.php"?"active":"") ?>">
                <a class="sidebar-link" href="index.php?p=product">
                    <i class="align-middle" data-feather="box"></i> <span class="align-middle">Product</span>
                </a>
            </li>
            <li class="sidebar-item <?= $page=="page.php"?"active":"" ?>">
                <a class="sidebar-link" href="index.php?p=page">
                    <i class="align-middle" data-feather="file"></i> <span class="align-middle">Page</span>
                </a>
            </li>
            <li class="sidebar-item <?= $page=="user.php"?"active":"" ?>">
                <a class="sidebar-link" href="index.php?p=user">
                    <i class="align-middle" data-feather="user"></i> <span class="align-middle">User</span>
                </a>
            </li>

        </ul>

    </div>
</nav>