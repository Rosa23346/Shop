<?php
    if(isset($_GET['action']) && $_GET['action']=="1"){
        $pageid = $_GET['pageid'];
        $pagetitle = $_POST['txttitle'];
        $pagecontent = $_POST['txtcontent'];
        $sql = "UPDATE tbl_page SET pagetitle = '$pagetitle' , pagecontent= '$pagecontent' WHERE pageid = $pageid";
        mysqli_query($conn,$sql);
    }
    $sql = "SELECT * FROM tbl_page order by pageid asc";
    $result = mysqli_query($conn,$sql);
?>
<main class="content">
    <div class="container-fluid p-0">

        <h1 class="h3 mb-3"><strong>Page</strong></h1>
        <table class="table">
            <tr>
                <th>No</th>
                <th>Title</th>
                <th>Content</th>
                <th>Action</th>
            </tr>

            <?php
            $i=1;
                while($row = mysqli_fetch_array($result)){
            ?>
            <tr>
                <td><?=$i?></td>
                <td><?=($row['pagetitle'])?></td>
                <td><?=($row['pagecontent'])?></td>
                <td>
                    <a href="index.php?p=editpage&pageid=<?=($row['pageid'])?>"><i class="align-middle" data-feather="edit"></i></a>
                </td>
            </tr>
            <?php $i++;} ?>

        </table>
    </div>
</main>