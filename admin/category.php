<?php

if(isset($_GET['action'])){
    $action = ($_GET['action']);

    switch ($action){
        case 1:
            $name = $_POST['txtName'];
            $sql = "INSERT INTO tbl_category (c_name) VALUES ('$name')";
            $result = mysqli_query($conn,$sql);
            break;
        case 2:
            break;
        case 3:
            $id = $_GET['categoryid'];
            $sql = "DELETE FROM tbl_category WHERE c_id = $id";
            $result = mysqli_query($conn,$sql);
            break;
    }

}


$error = -1; //error 0: not error , error 1: is error
$errormsg = "";

// for count rows we have
$sql = "select * from tbl_category order by c_id asc";
$result = mysqli_query($conn, $sql);
$num_rows = mysqli_num_rows($result);
$num_pages = ceil($num_rows/MAXPERPAGE);//ceil បង្រ្គប់ឡើង​, floor បង្រ្គប់ចុះ
$pg = 1;
$offset = 0;
if (isset($_GET['pg'])){
    $pg = $_GET['pg'];
    $offset = ($pg-1)*MAXPERPAGE;
}
$sql = "select * from tbl_category order by c_id asc limit ". MAXPERPAGE ." offset $offset";
$result = mysqli_query($conn, $sql);

?>

<main class="content">
    <div class="container-fluid p-0">

        <h1 class="h3 mb-3"><strong>Category</strong></h1>
        <a href="#" class="btn btn-primary float-end mb-3" data-bs-toggle="modal" data-bs-target="#addmodal">Add New Category</a>
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
                <th>Name</th>
                <th>Action</th>
            </tr>
            <?php 
            $i=$offset+1;
            while ($row=mysqli_fetch_array($result)) {
            ?>
            <tr>
                <td><?=$i?></td> <!-- data-value is attribute which own created for store data for ues other-->
                <td><?=$row['c_name']?></td>
                <td>
                    <a href="index.php?p=category"><i class="align-middle" data-feather="edit"></i></a>
                    <a href="index.php?" data-bs-toggle="modal" data-bs-target="#deletemodal" onclick="updateDeleteLinkCate('<?=($row['c_id'])?>')"><i class="align-middle" data-feather="trash"></i></a>
                </td>
            </tr>

            <?php $i++;} ?>
        </table>

        <?php 
        if($num_pages>1){
        ?>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-end">
                <li class="page-item">
                    <a class="page-link" href="index.php?p=category&pg=<?=$pg>1?$pg-1:$pg?>" tabindex="-1" aria-disabled="true">Previous</a>   
                </li>
                <?php
                for($i=1;$i<=$num_pages;$i++) {
                ?>
                <li class="page-item <?=$i==$pg?"active":""?>"><a class="page-link" href="index.php?p=category&pg=<?=$i?>"><?=$i?></a></li>
                <?php
                }
                ?>
                <li class="page-item">
                    <a class="page-link" href="index.php?p=category&pg=<?=$pg<$num_pages?$pg+1:$pg?>">Next</a>
                </li>
            </ul>
        </nav>
        <?php
        }
        ?>
    </div>
</main>

<!--delete Modal -->
    <div class="modal fade" id="deletemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">confirmation</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Do you want to delete this categorys?</p>
                </div>
                <div class="modal-footer">
                    <a href="#" type="button" id="deleteLinkCate" class="btn btn-primary">Yes</a>
                    <a href="#" type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</a>
                </div>
            </div>
        </div>
    </div>
<!--end delete model-->
<!--Add slideshow Modal -->
    <div class="modal fade" id="addmodal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addModalLabel">Add new Category</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="index.php?p=category&action=1" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="txtName" class="form-label">Name</label>
                            <input type="text" id="txtName" class="form-control" required name="txtName">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add new Category</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!--end add model-->

<script>

        function _(obj){
            return document.getElementById(obj);
        }
        function updateDeleteLinkCate(id){
            _("deleteLinkCate").href = "index.php?p=category&action=3&categoryid="+id;
        }
</script>