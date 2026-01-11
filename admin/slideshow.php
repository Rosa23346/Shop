<?php
/*
Action:
    0-Enable slideshow
    1-Disable slideshow
    2-Move slideshow up
    3-Move slideshow down
    4-Add new slideshow
    5-Edit slideshow
    6-Update slideshow
    7-Delete slideshow
*/

use LDAP\Result;

$error = -1; //error 0: not error , error 1: is error
$errormsg = "";
if(isset($_GET['action'])){
    $action = $_GET['action'];
    switch($action){
        case '0':
            $ssid = $_GET['ssid'];
            $sql = "UPDATE tbl_slideshow SET enable='1' WHERE ssid='$ssid'";
            mysqli_query($conn, $sql);
            break;
        case '1':
            $ssid = $_GET['ssid'];
            $sql = "UPDATE tbl_slideshow SET enable='0' WHERE ssid='$ssid'";
            mysqli_query($conn, $sql);
            break;
        case '2':
            $cur_ssid = $_GET['ssid'];
            $cur_ssorder = $_GET['ssorder'];
            $sql = "SELECT ssid, ssorder FROM tbl_slideshow WHERE ssorder < $cur_ssorder order by ssorder desc limit 1"; //to dg ah ler vea to move up
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0){ //or ==1
                $row = mysqli_fetch_array($result);
                $top_ssid = $row['ssid'];
                $top_ssorder = $row['ssorder'];

                $sql = "UPDATE tbl_slideshow SET ssorder=$cur_ssorder WHERE ssid=$top_ssid";
                mysqli_query($conn,$sql);
                $sql = "UPDATE tbl_slideshow SET ssorder=$top_ssorder WHERE ssid=$cur_ssid";
                mysqli_query($conn,$sql);
            }
            break;
        case '3':
            $cur_ssid = $_GET['ssid'];
            $cur_ssorder = $_GET['ssorder'];
            $sql = "SELECT ssid, ssorder FROM tbl_slideshow WHERE ssorder > $cur_ssorder order by ssorder limit 1"; //to dg ah ler vea to move up
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0){ //or ==1
                $row = mysqli_fetch_array($result);
                $butt_ssid = $row['ssid'];
                $butt_ssorder = $row['ssorder'];

                $sql = "UPDATE tbl_slideshow SET ssorder=$cur_ssorder WHERE ssid=$butt_ssid";
                mysqli_query($conn,$sql);
                $sql = "UPDATE tbl_slideshow SET ssorder=$butt_ssorder WHERE ssid=$cur_ssid";
                mysqli_query($conn,$sql);
            }
            break;
        case '4': //add slideshow
            $title = $_POST['txtTitle'];
            $subTitle = $_POST['txtSubTitle'];
            $text = $_POST['taText'];
            $link = $_POST['txtLink'];
            $enable = 0; // for checkbox if it's disabled is null so we should ues if for check it
            if(isset($_POST['chkEnable'])){
                $enable = 1;
            }
            $sql = "SELECT MAX(ssorder)+1 from tbl_slideshow limit 1";
            $result = mysqli_query($conn,$sql);
            $ssorder = 1;
            if(mysqli_num_rows($result)>=1){
                $row = mysqli_fetch_array($result);
                $ssorder = $row['MAX(ssorder)+1']; // max(ssorder)+1 is new row whict database own created 
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
                        createThumbnail($imageType,$tmp_name,$width,$height,100,100,$destination . "/thumbnail",$img);
                        createThumbnail($imageType,$tmp_name,$width,$height,480,480,$destination . "/slideshow",$img);
                        // move_uploaded_file($tmp_name,$destination . "/slideshow/" . $img);

                        $sql = "INSERT INTO tbl_slideshow (title, subtitle, text, link, enable, ssorder, img) 
                        VALUES('$title', '$subTitle', '$text','$link', '$enable', '$ssorder', '$img')";
                        $result = mysqli_query($conn, $sql);
                        if($result){
                        $error = 0;
                        $errormsg ="A slideshow has been added successfully";
                        }
                        else{
                        $error = 1 ;
                        $errormsg = "Fail to add a new slideshow";
                        }
                    }
                    else{
                        $error = 1 ;
                        $errormsg ="Fail, only type file must is image";
                    }
                }
                else{
                    $error =1 ;
                    $errormsg ="Fail, size of image must be less than 3MB.";
                }
            }
            else{
                $error =1 ;
                $errormsg ="Fail, file don't exist please exist file before!";
            }


            break;
        case '5':

            break;
        case '6':

            $ssid = $_GET['ssid'];
            $title = $_POST['txtEditTitle'];
            $subTitle = $_POST['txtEditSubTitle'];
            $text = $_POST['taEditText'];
            $link = $_POST['txtEditLink'];
            $enable = "0";
            if(isset($_POST['chkEditEnable'])) {
                $enable = "1";
            }
            
            if (!empty($title) && !empty($subTitle) && !empty($text)){
                if(file_exists($_FILES['fileEditImg']['tmp_name']) || is_uploaded_file($FILES['fileEditImg']['tmp_name'])){
                    if(($_FILES['fileEditImg']['size'])/(1048576) <= 3	){ //file size must less than 3 MB
                        $ext = pathinfo($_FILES['fileEditImg']['name'], PATHINFO_EXTENSION); // file extension
                        // echo $ext = pathinfo($_FILES['fileImg']['name'], PATHINFO_EXTENSION);
                        if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'png'){
                            $img = floor(microtime(true)*1000) . "." . $ext; // generate nameImg
    
                            $tmp_name = $_FILES['fileEditImg']['tmp_name'];
                            $sourceProperties = getimagesize($tmp_name);
                            $width = $sourceProperties[0];
                            $height = $sourceProperties[1];
                            $imageType = $sourceProperties[2];
                            $destination = "img";
                            createThumbnail($imageType,$tmp_name,$width,$height,100,100,$destination . "/thumbnail" ,$img);
                            createThumbnail($imageType,$tmp_name,$width,$height,480,480,$destination . "/slideshow" ,$img);
                            // move_uploaded_file($tmp_name,$destination . "/slideshow/" . $img);
    

                            $sql = "UPDATE tbl_slideshow SET title='$title', subtitle='$subTitle', text='$text', link='$link', enable='$enable', img='$img' WHERE ssid=$ssid";
                            $result = mysqli_query($conn,$sql);

                            if($result){
                                $oldImgName = $_POST['oldImgForDelete'];
                                $oldImg = $destination . "/slideshow/" . $oldImgName;
                                $oldImgThumbnail = $destination . "/thumbnail/" . $oldImgName;
                                if(file_exists($oldImg)){
                                    unlink($oldImg);
                                }
                                if(file_exists($oldImgThumbnail)){
                                    unlink($oldImgThumbnail);
                                }
                                $error = 0;
                                $errormsg ="A slideshow has been updated successfully";
                            }
                            else{
                                $error = 1 ;
                                $errormsg = "Fail to update slideshow";
                            }
                        }
                        else{
                            $error = 1 ;
                            $errormsg ="Fail, only type file must is image";
                        }
                    }
                    else{
                        $error =1 ;
                        $errormsg ="Fail, size of image must be less than 3MB.";
                    }
                }
                else{
                    $sql = "UPDATE tbl_slideshow SET title='$title', subtitle='$subTitle', text='$text', link='$link', enable='$enable' WHERE ssid=$ssid";
                    mysqli_query($conn,$sql);
                    $error = 0;
                    $errormsg = "A slideshwo has been updated successfully.";
                }
            }
            // mysqli_close($conn);
            break;
        case '7':
            $img = $_GET['img'];
            $ssid=$_GET['ssid'];
            $sql = "DELETE from tbl_slideshow WHERE ssid=$ssid";
            $result = mysqli_query($conn,$sql);
            if ($result){
                if(file_exists("img/slideshow/$img")){
                    unlink("img/slideshow/$img");
                }
                if(file_exists("img/thumbnail/$img")){
                    unlink("img/thumbnail/$img");
                }
                $error=0;
                $errormsg="A slideshow has been deleted successfully";
            }
            else{
                $error = 1;
                $errormsg =" Fail to delete slideshow";
            }
            break;
        case '8':
            break;
    }
};
// for count rows we have
$sql = "select * from tbl_slideshow order by ssorder asc";
$result = mysqli_query($conn, $sql);
$num_rows = mysqli_num_rows($result);
$num_pages = ceil($num_rows/MAXPERPAGE);//ceil បង្រ្គប់ឡើង​, floor បង្រ្គប់ចុះ
$pg = 1;
$offset = 0;
if (isset($_GET['pg'])){
    $pg = $_GET['pg'];
    $offset = ($pg-1)*MAXPERPAGE;
}
$sql = "select * from tbl_slideshow order by ssorder asc limit ". MAXPERPAGE ." offset $offset";
$result = mysqli_query($conn, $sql);


?>
<main class="content">
    <div class="container-fluid p-0">

        <h1 class="h3 mb-3"><strong>Slide Show</strong></h1>
        <a href="#" class="btn btn-primary float-end mb-3" data-bs-toggle="modal" data-bs-target="#addslideshowmodal">Add new slideshow</a>
        <!-- alert message -->
        <?php
        if ($error == 0 || $error ==1){
        ?>
        <div id="autoCloseAlert" class="alert alert-<?=$error==1?"danger":"success"?> alert-dismissible fade show" role="alert" style="clear:both">
            <?=$errormsg?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php
        }
        ?>
        <table class="table">
            <tr>
                <th>No</th>
                <th>Imgae</th>
                <th>Title</th>
                <th>Subtitle</th>
                <th>Text</th>
                <th>Link</th>
                <th>Action</th>
            </tr>
            <?php 
            $i=$offset+1;
            while ($row=mysqli_fetch_array($result)) {
            ?>
            <tr>
                <td id="ssid-<?=$row['ssid']?>"      data-value="<?=$row['ssid']?>"><?=$i?></td> <!-- data-value is attribute which own created for store data for ues other-->
                <td id="img-<?=$row['ssid']?>"       data-value="<?=$row['img']?>"><img src="img/thumbnail/<?=($row['img'])?>" alt="" style="width:50px"></td>
                <td id="title-<?=$row['ssid']?>"     data-value="<?=$row['title']?>"><?=$row['title']?></td>
                <td id="subtitle-<?=$row['ssid']?>"  data-value="<?=$row['subtitle']?>"><?=$row['subtitle']?></td>
                <td id="text-<?=$row['ssid']?>"      data-value="<?=$row['text']?>"><?=$row['text']?></td>
                <td id="link-<?=$row['ssid']?>"      data-value="<?=$row['link']?>"><?=$row['link']?></td>
                <td>
                    <a href="index.php?p=slideshow&action=<?=$row['enable']?>&ssid=<?=$row['ssid']?>" id="enable-<?=$row['ssid']?>" data-value="<?=$row['enable']?>"><i class="align-middle" data-feather="<?=$row['enable']=="1"?"eye":"eye-off"?>"></i></a>
                    <a href="index.php?p=slideshow&action=2&ssid=<?=$row['ssid']?>&ssorder=<?=$row['ssorder']?>"><i class="align-middle" data-feather="arrow-up"></i></a>
                    <a href="index.php?p=slideshow&action=3&ssid=<?=$row['ssid']?>&ssorder=<?=$row['ssorder']?>"><i class="align-middle" data-feather="arrow-down"></i></a>
                    <a href="index.php?p=slideshow" data-bs-toggle="modal" data-bs-target="#editslideshowmodal" onclick="loadDataForEdit('<?=$row['ssid']?>')"><i class="align-middle" data-feather="edit"></i></a>
                    <a href="index.php?" data-bs-toggle="modal" data-bs-target="#deletemodal" onclick="updateDeleteLink('<?=$row['img']?>','<?=$row['ssid']?>')"><i class="align-middle" data-feather="trash"></i></a>
                </td>
            </tr>
            <?php
            $i++;
            }
            ?>
        </table>

        <?php 
        if($num_pages>1){
        ?>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-end">
                <li class="page-item">
                    <a class="page-link" href="index.php?p=slideshow&pg=<?=$pg>1?$pg-1:$pg?>" tabindex="-1" aria-disabled="true">Previous</a>   
                </li>
                <?php
                for($i=1;$i<=$num_pages;$i++) {
                ?>
                <li class="page-item <?=$i==$pg?"active":""?>"><a class="page-link" href="index.php?p=slideshow&pg=<?=$i?>"><?=$i?></a></li>
                <?php
                }
                ?>
                <li class="page-item">
                    <a class="page-link" href="index.php?p=slideshow&pg=<?=$pg<$num_pages?$pg+1:$pg?>">Next</a>
                </li>
            </ul>
        </nav>
        <?php
        }
        ?>
    </div>
<!--delete Modal -->
    <div class="modal fade" id="deletemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">confirmation</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Do you want to delete slideshow?</p>
                </div>
                <div class="modal-footer">
                    <a href="#" type="button" id="deleteLink" class="btn btn-primary">Yes</a>
                    <a href="#" type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</a>
                </div>
            </div>
        </div>
    </div>
<!--end delete model-->

<!--Add slideshow Modal -->
    <div class="modal fade" id="addslideshowmodal" tabindex="-1" role="dialog" aria-labelledby="addslideshowModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addslideshowModalLabel">Add new slideshow</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="index.php?p=slideshow&action=4" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="txtTitle" class="form-label">Title</label>
                            <input type="text" id="txtTitle" class="form-control" required name="txtTitle">
                        </div>
                        <div class="mb-3">
                            <label for="txtSubTitle" class="form-label">SubTitle</label>
                            <input type="text" id="txtSubTitle" class="form-control" required name="txtSubTitle" >
                        </div>
                        <div class="mb-3">
                            <label for="taText" class="form-label">Textarea</label>
                            <textarea class="form-control" id="taText" placeholder="Textarea" required name="taText"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="txtLink" class="form-label">Link</label>
                            <input type="text" id="txtLink" class="form-control" required name="txtLink">
                        </div>  
        	            <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="chkEnable" checked name="chkEnable">
                            <label class="form-check-label" for="chkEnable">Enable</label>
                        </div>
                        <div class="mb-3">
                            <label for="fileImg" class="form-label">Select a slideshow image</label>
                            <input class="form-control" type="file" id="fileImg" required name="fileImg">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add new slideshow</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!--end add model-->

<!--edit slideshow Modal -->
    <div class="modal fade" id="editslideshowmodal" tabindex="-1" role="dialog" aria-labelledby="editslideshowModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editslideshowModalLabel">Edit slideshow</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="index.php?p=slideshow&action=6" id="sseditform" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="txtEditTitle" class="form-label">Title</label>
                            <input type="text" id="txtEditTitle" class="form-control" required name="txtEditTitle">
                        </div>
                        <div class="mb-3">
                            <label for="txtEditSubTitle" class="form-label">SubTitle</label>
                            <input type="text" id="txtEditSubTitle" class="form-control" required name="txtEditSubTitle" >
                        </div>
                        <div class="mb-3">
                            <label for="taEditText" class="form-label">Textarea</label>
                            <textarea class="form-control" id="taEditText" placeholder="Textarea" required name="taEditText"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="txtEditLink" class="form-label">Link</label>
                            <input type="text" id="txtEditLink" class="form-control" required name="txtEditLink">
                        </div>  
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="chkEditEnable" checked name="chkEditEnable">
                            <label class="form-check-label" for="chkEditEnable">Enable</label>
                        </div>
                        <div class="mb-3">
                            <label for="fileEditImg" class="form-label">Select a slideshow image</label>
                            <input class="form-control" type="file" id="fileEditImg" name="fileEditImg">
                        </div>
                        <div class="mb-3">
                            <img src="#" id="oldImg">
                            <label for="oldImg" class="form-label" id="oldImgName"></label>
                            <input type="hidden" value="" id= "oldImgForDelete" name="oldImgForDelete">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!--end edit model-->

    <script>
        function _(obj){
            return document.getElementById(obj);
        }
        function updateDeleteLink(img, ssid){
            
            
            _("deleteLink").href="index.php?p=slideshow&action=7&img="+img+"&ssid="+ssid;
            // alert(document.getElementById("deleteLink").href);
        }
        function loadDataForEdit(ssid){
            // alert(ssid);
            var  title = _("title-"+ ssid).getAttribute("data-value");
            _("txtEditTitle").value = title;
            var subTitle = _("subtitle-"+ ssid).getAttribute("data-value");
            _("txtEditSubTitle").value = subTitle;
            var text = _("text-"+ ssid).getAttribute("data-value");
            _("taEditText").value = text;
            var link = _("link-"+ ssid).getAttribute("data-value");
            _("txtEditLink").value = link;
            var enable = _("enable-"+ssid).getAttribute("data-value");
            if(enable=="1"){
                _("chkEditEnable").checked = true;
            }
            else{
                _("chkEditEnable").checked = false;
            }
            var img = _("img-"+ssid).getAttribute("data-value");
            _("oldImg").src = "img/thumbnail/"+img;
            _("oldImgForDelete").value = img;
            _("oldImgName").innerHTML = img;
            _("sseditform").action = _("sseditform").action + "&ssid=" + ssid;
            // alert(_("sseditform").action);
        }
        setTimeout(function () {
            var alert = document.getElementById('autoCloseAlert');
            if (alert) {
                var bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert.close();
            }
        }, 1500);
    </script>
</main>