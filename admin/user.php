<?php

/*
Action:
    0-Enable 
    1-Disable 
    2-Add new
    3-Update 
    4-Delete 
*/

$error = -1;
$errormsg = "";

if(isset($_GET['action'])){
    $action = $_GET['action'];
    switch($action){
        case '0':
            $userid = $_GET['userid'];
            $sql = "UPDATE tbl_user SET active='1' WHERE userid='$userid'";
            mysqli_query($conn, $sql);
            break;
        case '1':
            $userid = $_GET['userid'];
            $sql = "UPDATE tbl_user SET active='0' WHERE userid='$userid'";
            mysqli_query($conn, $sql);
            break;

        case '2': //add 
            $username = $_POST['username'];
            $password = $_POST['txtPassword'];
            $text = $_POST['taText'];
            $link = $_POST['txtFullName'];
            $enable = 0; // for checkbox if it's disabled is null so we should ues if for check it
            if(isset($_POST['chkActive'])){
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
                        createThumbnail($imageType,$tmp_name,$width,$height,$destination,$img);
                        move_uploaded_file($tmp_name,$destination . "/" . $img);

                        $sql = "INSERT INTO tbl_slideshow (username, subtitle, text, link, enable, ssorder, img) 
                        VALUES('$username', '$password', '$text','$link', '$enable', '$ssorder', '$img')";
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

            $userid = $_GET['userid'];
            $username = $_POST['txtEditUser'];
            $password = $_POST['txtEditPassword'];
            $text = $_POST['taEditText'];
            $link = $_POST['txtEditFullName'];
            $enable = "0";
            if(isset($_POST['chkEditEnable'])) {
                $enable = "1";
            }
            
            if (!empty($username) && !empty($password) && !empty($text)){
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
                            createThumbnail($imageType,$tmp_name,$width,$height,$destination,$img);
                            move_uploaded_file($tmp_name,$destination . "/" . $img);
    

                            $sql = "UPDATE tbl_slideshow SET username='$username', subtitle='$password', text='$text', link='$link', enable='$enable', img='$img' WHERE userid=$userid";
                            $result = mysqli_query($conn,$sql);

                            if($result){
                                $oldImgName = $_POST['oldImgForDelete'];
                                $oldImg = $destination . "/" . $oldImgName;
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
                    $sql = "UPDATE tbl_slideshow SET username='$username', subtitle='$password', text='$text', link='$link', enable='$enable' WHERE userid=$userid";
                    mysqli_query($conn,$sql);
                    $error = 0;
                    $errormsg = "A slideshwo has been updated successfully.";
                }
            }
            // mysqli_close($conn);
            break;
        case '7':
            $img = $_GET['img'];
            $userid=$_GET['userid'];
            $sql = "DELETE from tbl_slideshow WHERE userid=$userid";
            $result = mysqli_query($conn,$sql);
            if ($result){
                if(file_exists("img/$img")){
                    unlink("img/$img");
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
$sql = "select * from tbl_user order by userid asc";
$result = mysqli_query($conn, $sql);
$num_rows = mysqli_num_rows($result);
$num_pages = ceil($num_rows/MAXPERPAGE);//ceil បង្រ្គប់ឡើង​, floor បង្រ្គប់ចុះ
$pg = 1;
$offset = 0;
if (isset($_GET['pg'])){
    $pg = $_GET['pg'];
    $offset = ($pg-1)*MAXPERPAGE;
}
$sql = "select * from tbl_user order by userid asc limit ". MAXPERPAGE ." offset $offset";
$result = mysqli_query($conn, $sql);

?>
<main class="content">
    <div class="container-fluid p-0">

        <h1 class="h3 mb-3"><strong>User</strong></h1>
        <a href="#" class="btn btn-primary float-end mb-3" data-bs-toggle="modal" data-bs-target="#addusermodal">Add new user</a>
        <!-- alert message -->
        <?php
        if ($error == 0){
        ?>
        <div class="alert alert-<?=$error==1?"danger":"success"?> alert-dismissible fade show" role="alert" style="clear:both">
            <?=$errormsg?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php
        }
        ?>
        <table class="table">
            <tr>
                <th>id</th>
                <th>UserName</th>
                <th>Password</th>
                <th>Fullname</th>
                <th>IsAdmin</th>
                <!-- <th>Active</th> -->
                <th>Action</th>
                <!-- <th>LastLogin</th> -->
            </tr>
            <?php 
            $i=$offset+1;
            while ($row=mysqli_fetch_array($result)) {
            ?>
            <tr>
                <td id="userid-<?=$row['userid']?>"      data-value="<?=$row['userid']?>"><?=$i?></td> <!-- data-value is attribute which own created for store data for ues other-->
                <td id="username-<?=$row['userid']?>"     data-value="<?=$row['username']?>"><?=$row['username']?></td>
                <td id="password-<?=$row['userid']?>"     data-value="<?=$row['password']?>"><?=$row['password']?></td>
                <td id="fullname-<?=$row['userid']?>"  data-value="<?=$row['fullname']?>"> <?=$row['fullname']?></td>
                <td id="isadmin-<?=$row['userid']?>"      data-value="<?=$row['isadmin']?>"><?=$row['isadmin']?></td>
                <td>
                    <a href="index.php?p=user&action=<?=$row['active']?>&userid=<?=$row['userid']?>" id="enable-<?=$row['userid']?>" data-value="<?=$row['active']?>"><i class="align-middle" data-feather="<?=$row['active']=="1"?"eye":"eye-off"?>"></i></a>
                    <a href="index.php?p=user" data-bs-toggle="modal" data-bs-target="#editusermodal" onclick="loadDataForEdit('<?=$row['userid']?>')"><i class="align-middle" data-feather="edit"></i></a>
                    <a href="index.php?" data-bs-toggle="modal" data-bs-target="#deleteusermodal" onclick="updateDeleteLink('<?=$row['userid']?>')"><i class="align-middle" data-feather="trash"></i></a>
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
                    <a class="page-link" href="index.php?p=user&pg=<?=$pg>1?$pg-1:$pg?>" tabindex="-1" aria-disabled="true">Previous</a>   
                </li>
                <?php
                for($i=1;$i<=$num_pages;$i++) {
                ?>
                <li class="page-item <?=$i==$pg?"active":""?>"><a class="page-link" href="index.php?p=user&pg=<?=$i?>"><?=$i?></a></li>
                <?php
                }
                ?>
                <li class="page-item">
                    <a class="page-link" href="index.php?p=user&pg=<?=$pg<$num_pages?$pg+1:$pg?>">Next</a>
                </li>
            </ul>
        </nav>
        <?php
        }
        ?>
    </div>
</main>
<!--delete Modal -->
    <div class="modal fade" id="deleteusermodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-username fs-5" id="exampleModalLabel">confirmation</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Do you want to delete user?</p>
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
    <div class="modal fade" id="addusermodal" tabindex="-1" role="dialog" aria-labelledby="adduserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-username fs-5" id="adduserModalLabel">Add new slideshow</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="index.php?p=user&action=2" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="txtUserName" class="form-label">UserName</label>
                            <input type="text" id="txtUserName" class="form-control" required name="txtUserName">
                        </div>
                        <div class="mb-3">
                            <label for="txtPassword" class="form-label">Password</label>
                            <input type="text" id="txtPassword" class="form-control" required name="txtPassword" >
                        </div>
                        <div class="mb-3">
                            <label for="txtFullName" class="form-label">Link</label>
                            <input type="text" id="txtFullName" class="form-control" required name="txtFullName">
                        </div>  
        	            <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="chkActive" checked name="chkActive">
                            <label class="form-check-label" for="chkActive">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add new user</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!--end add model-->

<!--edit slideshow Modal -->
    <div class="modal fade" id="editusermodal" tabindex="-1" role="dialog" aria-labelledby="edituserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-username fs-5" id="edituserModalLabel">Edit slideshow</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="index.php?p=user&action=3" id="usereditform" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="txtEditUserName" class="form-label">username</label>
                            <input type="text" id="txtEditUserName" class="form-control" required name="txtEditUserName">
                        </div>
                        <div class="mb-3">
                            <label for="txtEditPassword" class="form-label">password</label>
                            <input type="text" id="txtEditPassword" class="form-control" required name="txtEditPassword" >
                        </div>
                        <div class="mb-3">
                            <label for="txtEditFullName" class="form-label">fullname</label>
                            <input type="text" id="txtEditFullName" class="form-control" required name="txtEditFullName">
                        </div>  
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="chkEditEnable" checked name="chkEditEnable">
                            <label class="form-check-label" for="chkEditEnable">active</label>
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
        function updateDeleteLink(img, userid){
            
            
            _("deleteLink").href="index.php?p=slideshow&action=7&img="+img+"&userid="+userid;
            // alert(document.getElementById("deleteLink").href);
        }
        function loadDataForEdit(userid){
            // alert(userid);
            var  username = _("username-"+ userid).getAttribute("data-value");
            _("txtEditUserName").value = username;
            var password = _("password-"+ userid).getAttribute("data-value");
            _("txtEditPassword").value = password;
            var fullname = _("fullname-"+ userid).getAttribute("data-value");
            _("txtEditFullName").value = fullname;
            var enable = _("enable-"+userid).getAttribute("data-value");
            if(enable=="1"){
                _("chkEditEnable").checked = true;
            }
            else{
                _("chkEditEnable").checked = false;
            }
            _("sseditform").action = _("sseditform").action + "&userid=" + userid;
            // alert(_("sseditform").action);
        }
    </script>
