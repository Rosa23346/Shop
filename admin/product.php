<?php
/*
Action:
    0-Enable
    1-Disable
    2-Add new
    3-Update
    4-Delete
*/

$errorproduct = -1;
$errormsgproduct = "";

if(isset($_GET['p_action'])){
    $p_action = $_GET['p_action'];
    switch($p_action){
        case '0':
            $p_id = $_GET['p_id'];
            $sql = "UPDATE tbl_product set p_enable='1' where p_id='$p_id'";
            mysqli_query($conn,$sql);
            break;
        case '1':
            $p_id = $_GET['p_id'];
            $sql = "UPDATE tbl_product set p_enable='0' where p_id='$p_id'";
            mysqli_query($conn,$sql);
            break;
        case '2':
            $name = $_POST['txtName'];
            $description = $_POST['txtDescription'];
            $price = $_POST['txtPrice'];
            $category = $_POST['category'];
            $brand = $_POST['brand'];

            $enable = 0; // for checkbox if it's disabled is null so we should ues if for check it
            if(isset($_POST['chkEnableProduct'])){
                $enable = 1;
            }
            $img =  "noimg.jpg";
            if(file_exists($_FILES['fileImg']['tmp_name']) || is_uploaded_file($FILES['fieImg']['tmp_name'])){
                if(($_FILES['fileImg']['size'])/(1048576) <= 3	){ //file size must less than 3 MB
                    $ext = pathinfo($_FILES['fileImg']['name'], PATHINFO_EXTENSION); // file extension
                    // echo $ext = pathinfo($_FILES['fileImg']['name'], PATHINFO_EXTENSION);
                    if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'png'){
                        $img = floor(microtime(true)*1000) . "." . $ext; // generate nameImg

                        $tmp_name = $_FILES['fileImg']['tmp_name'];
                        $sourceProperties = getimagesize($tmp_name);
                        $width = $sourceProperties[0];
                        $height = $sourceProperties[1];
                        $imageType = $sourceProperties[2];
                        $destination = "img";
                        createThumbnail($imageType,$tmp_name,$width,$height,100,100,$destination."/thumbnail",$img);
                        createThumbnail($imageType,$tmp_name,$width,$height,500,500,$destination."/product",$img);

                        // move_uploaded_file($tmp_name,$destination . "/product/" . $img);

                        $sql = "INSERT INTO tbl_product (p_name, p_description, p_price, categoryid, brandid, p_enable, p_image) 
                        VALUES('$name', '$description', '$price', (SELECT c_id FROM tbl_category WHERE c_name='$category') , (SELECT b_id FROM tbl_brand WHERE b_name='$brand') , '$enable', '$img')";
                        $result = mysqli_query($conn, $sql);
                        if($result){
                        $errorproduct = 0;
                        $errormsgproduct ="A product has been added successfully";
                        }
                        else{
                        $errorproduct = 1 ;
                        $errormsgproduct = "Fail to add a new product";
                        }
                    }
                    else{
                        $errorproduct = 1 ;
                        $errormsgproduct ="Fail, only type file must is image";
                    }
                }
                else{
                    $errorproduct =1 ;
                    $errormsgproduct ="Fail, size of image must be less than 3MB.";
                }
            }
            else{
                $errorproduct =1 ;
                $errormsgproduct ="Fail, file don't exist please exist file before!";
            }
            break;
        case '3':
            $p_id = $_GET['p_id'];
            $name = $_POST['txtEditName'];
            $description = $_POST['txtEditDescription'];
            $price = $_POST['txtEditPrice'];
            $enable = 0;
            if(isset($_POST['chkEnableProduct'])){
                $enable = "1";
            }
            $category = $_POST['categoryEdit'];
            $brand = $_POST['brandEdit'];

            if (!empty($name) && !empty($description) && !empty($price)){
                if(file_exists($_FILES['fileImg']['tmp_name']) || is_uploaded_file($_FILES['fileImg']['tmp_name'])){
                    if(($_FILES['fileImg']['size'])/(1048576) <= 5	){ //file size must less than 5 MB
                        $ext = pathinfo($_FILES['fileImg']['name'], PATHINFO_EXTENSION); // file extension
                        // echo $ext = pathinfo($_FILES['fileImg']['name'], PATHINFO_EXTENSION);
                        if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'png'){
                            $img = floor(microtime(true)*1000) . "." . $ext; // generate nameImg
    
                            $tmp_name = $_FILES['fileImg']['tmp_name'];
                            $sourceProperties = getimagesize($tmp_name);
                            $width = $sourceProperties[0];
                            $height = $sourceProperties[1];
                            $imageType = $sourceProperties[2];
                            $destination = "img";
                            createThumbnail($imageType,$tmp_name,$width,$height,100,100,$destination . "/thumbnail",$img);
                            createThumbnail($imageType,$tmp_name,$width,$height,500,500,$destination . "/product",$img);
                            move_uploaded_file($tmp_name,$destination . "/product/" . $img);
                            ;
                            $sql = "UPDATE tbl_product 
                            SET 
                                p_name = '$name',
                                p_description = '$description',
                                p_price = '$price',
                                categoryid = (SELECT c_id FROM tbl_category WHERE c_name = '$category'),
                                brandid = (SELECT b_id FROM tbl_brand WHERE b_name = '$brand'),
                                p_enable = '$enable',
                                p_image = '$img'
                            WHERE 
                                p_id = $p_id";

                            $result = mysqli_query($conn,$sql);

                            if($result){
                                $oldImgName = $_POST['oldImgForDelete'];
                                $oldImg = $destination . "/product/" . $oldImgName;
                                $oldImgThumbnail = $destination . "/thumbnail/" . $oldImgName;
                                if(file_exists($oldImg)){
                                    unlink($oldImg);
                                }
                                if(file_exists($oldImgThumbnail)){
                                    unlink($oldImgThumbnail);
                                }
                                $errorproduct = 0;
                                $errormsgproduct ="A Product has been updated successfully";
                            }
                            else{
                                $errorproduct = 1 ;
                                $errormsgproduct = "Fail to update product";
                            }
                        }
                        else{
                            $errorproduct = 1 ;
                            $errormsgproduct ="Fail, only type file must is image";
                        }
                    }
                    else{
                        $errorproduct =1 ;
                        $errormsgproduct ="Fail, size of image must be less than 5MB.";
                    }
                }
                else{
                    $sql = "UPDATE tbl_product 
                    SET 
                        p_name = '$name',
                        p_description = '$description',
                        p_price = '$price',
                        categoryid = (SELECT c_id FROM tbl_category WHERE c_name = '$category'),
                        brandid = (SELECT b_id FROM tbl_brand WHERE b_name = '$brand'),
                        p_enable = '$enable'
                    WHERE 
                        p_id = $p_id";
                    $result = mysqli_query($conn,$sql);
                    $errorproduct = 0;
                    $errormsgproduct = "A product has been updated successfully.";
                }
            }




            break;
        case '4':
            $p_id = $_GET['p_id'];
            $p_image = $_GET['p_image'];
            $sql = "DELETE FROM tbl_product WHERE p_id=$p_id";
            $result = mysqli_query($conn,$sql);
            if($result){
                if(file_exists("img/product/".$p_image)){
                    unlink("img/product/".$p_image);
                }
                if(file_exists("img/thumbnail/".$p_image)){
                    unlink("img/thumbnail/".$p_image);
                }
                $errorproduct = 0;
                $errormsgproduct ="Success to delete this product!";
            }
            else{
                $errorproduct = 1;
                $errormsgproduct =" Fail to delete this product!";
            }
            break;
    }
}
$num_pages = 1;
// $sql = "SELECT * FROM tbl_product order by productid asc";

$sql = "SELECT * FROM tbl_product ORDER BY p_id ASC";

$result = mysqli_query($conn, $sql);
$numresult = mysqli_num_rows($result);
$num_pages = ceil($numresult/MAXPERPAGE);
$pg = 1;
$offset = 0;
if (isset($_GET['pg'])){
    $pg = $_GET['pg'];
    $offset = ($pg-1)*MAXPERPAGE;
}
$sql = "SELECT p.p_id, p.p_name, p.p_description, p.p_price, p.p_image , c.c_name ,b.b_name, p.p_enable
        FROM tbl_product p 
        JOIN tbl_brand b ON p.brandid = b.b_id 
        JOIN tbl_category c ON p.categoryid = c.c_id 
        ORDER BY p.p_id ASC limit ". MAXPERPAGE ." offset $offset";
$result = mysqli_query($conn, $sql);
?>


<main class="content">
    <div class="container-fluid p-0">

        <h1 class="h3 mb-3"><strong>Product</strong></h1>
        <a href="#" class="btn btn-primary float-end mb-3" data-bs-toggle="modal" data-bs-target="#addproductmodal">Add new product</a>
        <!-- alert message -->
        <?php
        if ($errorproduct == 0 || $errorproduct ==1){
        ?>
        <div id="autoCloseAlert" class="alert alert-<?=$errorproduct==1?"danger":"success"?> alert-dismissible fade show" role="alert" style="clear:both">
            <?=$errormsgproduct?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php
        }?>
        <!-- alert messsage -->
        
        <table class="table">
            <tr>
                <th>No</th>
                <th>Imgae</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Category</th>
                <th>Brand</th>
                <th>Action</th>
            </tr>

            <?php 
            $i = $offset+1;
            while($row=mysqli_fetch_array($result)){
            ?>
            <tr>
                <td id="p_id-<?=($row['p_id'])?>"          data-value="<?=($row['p_id'])?>"><?=($i)?></td>
                <td id="p_image-<?=($row['p_id'])?>"       data-value="<?=($row['p_image'])?>"><img src="img/thumbnail/<?=($row['p_image'])?>" alt="" style="width:50px"></td>
                <td id="p_name-<?=($row['p_id'])?>"        data-value="<?=($row['p_name'])?>"><?=($row['p_name'])?></td>
                <td id="p_description-<?=($row['p_id'])?>" data-value="<?=($row['p_description'])?>"><?=($row['p_description'])?></td>
                <td id="p_price-<?=($row['p_id'])?>"       data-value="<?=($row['p_price'])?>"><?=($row['p_price'])?>$</td>
                <td id="categoryname-<?=($row['p_id'])?>"    data-value="<?=($row['c_name'])?>"><?=($row['c_name'])?></td>
                <td id="brandname-<?=($row['p_id'])?>"       data-value="<?=($row['b_name'])?>"><?=($row['b_name'])?></td>
                <td>
                    <a id="p_enable-<?=($row['p_id'])?>" data-value="<?=$row['p_enable']?>" href="index.php?p=product&p_action=<?=$row['p_enable']=="0"?"0":"1"?>&p_id=<?=$row['p_id']?>"><i class="align-middle" data-feather="<?= $row['p_enable']=="1"?"eye":"eye-off" ?>"></i></a>
                    <a href="index,php?p=product" data-bs-toggle="modal" data-bs-target="#editproductmodal" onclick="loadDataForEdit(<?=($row['p_id'])?>)"><i class="align-middle" data-feather="edit"></i></a>
                    <a href="index.php" data-bs-toggle="modal" data-bs-target="#deleteproductmodal" onclick="deleteproduct('<?=($row['p_id'])?>','<?=($row['p_image'])?>')"><i class="align-middle" data-feather="trash"></i></a>
                </td>
            </tr>

            <?php
            $i++;
            }
            ?>
        </table>
        <?php 
        if($num_pages>=2){
        ?>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-end">
                <li class="page-item">
                    <a class="page-link" href="index.php?p=product&pg=<?=$pg>1?$pg-1:$pg?>" tabindex="-1" aria-disabled="true">Previous</a>   
                </li>
                <?php if($num_pages >2){?>
                <?php
                $i=1;
                $limit =1;
                if($pg>2){$i=$pg-1;}
                for($i;$i<=$num_pages;$i++){
                    if($limit>3){break;}
                ?>
                <li class="page-item <?=$i==$pg?"active":""?>"><a class="page-link" href="index.php?p=product&pg=<?=($i)?>"><?=($i)?></a></li>
                <?php
                $limit++;
                }
                ?>
                
                <?php }?>
                <li class="page-item">
                    <a class="page-link" href="index.php?p=product&pg=<?=$pg<$num_pages?$pg+1:$pg?>">Next</a>
                </li>
            </ul>
        </nav>
        <?php
        }
        ?>
    </div>
</main>

<!--Add Modal -->
    <div class="modal fade" id="addproductmodal" tabindex="-1" role="dialog" aria-labelledby="addproductModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addproductModalLabel">Add new product</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="index.php?p=product&p_action=2" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-2">
                            <label for="txtName" class="form-label">Name</label>
                            <input type="text" id="txtName" class="form-control" required name="txtName">
                        </div>
                        <div class="mb-2">
                            <label for="txtDescription" class="form-label">Description</label>
                            <input type="text" id="txtDescription" class="form-control" required name="txtDescription" >
                        </div>
                        <div class="mb-2">
                            <label for="txtPrice" class="form-label">Price</label>
                            <input type="text" class="form-control" id="txtPrice" required name="txtPrice"></input>
                        </div>


                        <?php 
                        $sql = "SELECT * FROM tbl_category order by c_id asc";
                        $result = mysqli_query($conn,$sql);
                        $row = mysqli_fetch_array($result);
                        ?>
                        <input type="hidden" name="category" id="category" value="<?=$row['c_name']?>">
                        <div class="mb-1">
                            <label class="form-label">Category</label><br>
                            <div class="btn-group w-25">
                                <button type="button" class="btn btn-secondary w-75" id="txtCategory"><?=$row['c_name']?></button>
                                <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <?php
                                    $sql = "SELECT * FROM tbl_category order by c_id asc";
                                    $result = mysqli_query($conn,$sql);
                                    while($row= mysqli_fetch_array($result))
                                    {
                                    ?>
                                    <li><a class="dropdown-item" href="#" onclick="changedropdowncategory(this)"><?=$row['c_name']?></a></li>
                                    <?php
                                    }?>
                                </ul>
                            </div>
                        </div>

                        <?php 
                        $sql = "SELECT * FROM tbl_brand order by b_id asc";
                        $result = mysqli_query($conn,$sql);
                        $row = mysqli_fetch_array($result);
                        ?>
                        <input type="hidden" name="brand" id="brand" value="<?=$row['b_name']?>">
                        <div class="mb-1">
                            <label class="form-label">Brand</label><br>
                            <div class="btn-group w-25">
                                <button type="button" class="btn btn-secondary w-75" id="txtBrand"><?=$row['b_name']?></button>
                                <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <?php
                                    $sql = "SELECT * FROM tbl_brand order by b_id asc";
                                    $result = mysqli_query($conn,$sql);
                                    while($row= mysqli_fetch_array($result))
                                    {
                                    ?>
                                    <li><a class="dropdown-item" href="#" onclick="changedropdownbrand(this)"><?=$row['b_name']?></a></li>
                                    <?php
                                    }?>
                                </ul>
                            </div>
                        </div>
                        
                        


        	            <div class="form-check form-switch mb-1">
                            <input class="form-check-input" type="checkbox" role="switch" id="chkEnableProduct" checked name="chkEnableProduct">
                            <label class="form-check-label" for="chkEnableProduct">Enable</label>
                        </div>

                        <div class="mb-2">
                            <label for="fileImg" class="form-label">Select a product image</label>
                            <input class="form-control" type="file" id="fileImg" required name="fileImg">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add new product</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!--end add model-->
<!--edit Modal -->
    <div class="modal fade" id="editproductmodal" tabindex="-1" role="dialog" aria-labelledby="editproductModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addproductModalLabel">Edit product</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="index.php?p=product&p_action=3" method="post" enctype="multipart/form-data" id="formeditproduct">
                    <div class="modal-body">
                        <div class="mb-2">
                            <label for="txtEditName" class="form-label">Name</label>
                            <input type="text" id="txtEditName" class="form-control" required name="txtEditName">
                        </div>
                        <div class="mb-2">
                            <label for="txtEditDescription" class="form-label">Description</label>
                            <input type="text" id="txtEditDescription" class="form-control" required name="txtEditDescription" >
                        </div>
                        <div class="mb-2">
                            <label for="txtEditPrice" class="form-label">Price</label>
                            <input type="text" class="form-control" id="txtEditPrice" required name="txtEditPrice"></input>
                        </div>


                        <input type="hidden" name="categoryEdit" id="categoryEdit" >
                        <div class="mb-1">
                            <label class="form-label">Category</label><br>
                            <div class="btn-group w-25">
                                <button type="button" class="btn btn-secondary w-75" id="txtEditCategory"></button>
                                <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <?php
                                    $sql = "SELECT * FROM tbl_category order by c_id asc";
                                    $result = mysqli_query($conn,$sql);
                                    while($row= mysqli_fetch_array($result))
                                    {
                                    ?>
                                    <li><a class="dropdown-item" href="#" onclick="changedropdowncategory(this)"><?=$row['c_name']?></a></li>
                                    <?php
                                    }?>
                                </ul>
                            </div>
                        </div>
                        

                        <input type="hidden" name="brandEdit" id="brandEdit" >
                        <div class="mb-1">
                            <label class="form-label">Brand</label><br>
                            <div class="btn-group w-25">
                                <button type="button" class="btn btn-secondary w-75" id="txtEditBrand"></button>
                                <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <?php
                                    $sql = "SELECT * FROM tbl_brand order by b_id asc";
                                    $result = mysqli_query($conn,$sql);
                                    while($row= mysqli_fetch_array($result))
                                    {
                                    ?>
                                    <li><a class="dropdown-item" href="#" onclick="changedropdownbrand(this)"><?=$row['b_name']?></a></li>
                                    <?php
                                    }?>
                                </ul>
                            </div>
                        </div>


        	            <div class="form-check form-switch mb-1">
                            <input class="form-check-input" type="checkbox" role="switch" id="chkEnableProduct" name="chkEnableProduct">
                            <label class="form-check-label" for="chkEnableProduct">Enable</label>
                        </div>

                        <div class="mb-1">
                            <label for="fileImg" class="form-label">Select a product image</label>
                            <input class="form-control" type="file" id="fileImg" name="fileImg">
                        </div>
                        <div class="mb-1">
                            <img src="#" id="oldImg">
                            <label for="oldImg" class="form-label" id="oldImgName"></label>
                            <input type="hidden" value="" id= "oldImgForDelete" name="oldImgForDelete">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Edit product</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!--end edit model-->
<!-- delete modal -->
<div class="modal" tabindex="-1" id="deleteproductmodal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Do you want to delete this product?</p>
      </div>
      <div class="modal-footer">
        <a id="linkdeleteproduct" href="#" type="button" class="btn btn-primary" >Confirm</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

      </div>
    </div>
  </div>
</div>
<!-- delete modal -->
<script>
    function _(obj){
        return document.getElementById(obj);
    }


    setTimeout(function () {
        var alert = document.getElementById('autoCloseAlert');
        if (alert) {
            var bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }
    }, 1500);
    function changedropdowncategory(element){
        document.getElementById("txtCategory").textContent = element.textContent;
        document.getElementById("txtEditCategory").textContent = element.textContent;
        document.getElementById("category").value = element.textContent;
        document.getElementById("categoryEdit").value = element.textContent; // Store the category in hidden input
         // Store the category in hidden input
    }

    
    function changedropdownbrand(element){
        document.getElementById("txtBrand").textContent = element.textContent;
        document.getElementById("txtEditBrand").textContent = element.textContent;
        document.getElementById("brand").value = element.textContent;
        document.getElementById("brandEdit").value = element.textContent; // Store the category in hidden input

    }
    function loadDataForEdit(p_id){
            // alert(p_id);
            var  name = _("p_name-"+ p_id).getAttribute("data-value");
            _("txtEditName").value = name;
            var description = _("p_description-"+ p_id).getAttribute("data-value");
            _("txtEditDescription").value = description;
            var price = _("p_price-"+ p_id).getAttribute("data-value");
            _("txtEditPrice").value = price;

            var category = _("categoryname-"+p_id).getAttribute("data-value");
            // alert(category);    
            _("txtEditCategory").textContent = category;
            _("categoryEdit").value = category;

            var brand = _("brandname-"+p_id).getAttribute("data-value");
            _("txtEditBrand").textContent = brand;
            _("brandEdit").value = brand;

            var enable = _("p_enable-"+p_id).getAttribute("data-value");
            if(enable=="1"){
                _("chkEnableProduct").checked = true;
            }
            else{
                _("chkEnableProduct").checked = false;
            }
            var img = _("p_image-"+p_id).getAttribute("data-value");
            _("oldImg").src = "img/thumbnail/"+img;
            _("oldImgName").innerHTML = img;

            _("oldImgForDelete").value = img;
            _("formeditproduct").action = _("formeditproduct").action + "&p_id=" + p_id;
            // alert(_("sseditform").action);
        }
        function deleteproduct(p_id,p_image){
            _("linkdeleteproduct").href = "index.php?p=product&p_action=4&p_id="+ p_id + "&p_image=" + p_image;
        }
</script>