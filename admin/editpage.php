<?php
if(isset($_GET['pageid']))
{
    $pageid = $_GET['pageid'];
    $sql = "SELECT * FROM tbl_page WHERE pageid=$pageid";
    $result = mysqli_query($conn,$sql);
    $row = mysqli_fetch_array($result);
?>
<main class="content">
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3"><strong>Page Edit</strong></h1>
        <form action="index.php?p=page&action=1&pageid=<?=($row['pageid'])?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="txttitle" class="form-label">Title</label>
                <input type="Text" class="form-control" id="txttitle" name="txttitle" value="<?=($row['pagetitle'])?>">
            </div>

            <div class="mb-3">
                <label for="txtcontent" class="form-label">Content</label>
                <textarea class="form-control h-25" id="txtcontent" name="txtcontent"><?=($row['pagecontent'])?></textarea>
            </div>
            <input type="submit" value="Update" class="btn btn-primary">
            <input type="button" value="Cancel" class="btn btn-secondary" onclick="window.location='index.php?p=page'">
        </form>
    </div>  
</main>

<script>
    tinymce.init({
        selector: '#txtcontent',
        height : 300,
        plugins: [
        'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
        'checklist', 'mediaembed', 'casechange', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'editimage', 'advtemplate', 'ai', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown','importword', 'exportword', 'exportpdf'
        ],
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | align lineheight | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        mergetags_list: [
        { value: 'First.Name', title: 'First Name' },
        { value: 'Email', title: 'Email' },
        ],


        ai_request: (request, respondWith) => respondWith.string(() => Promise.reject('See docs to implement AI Assistant')),

        file_picker_types: 'image',
        
        file_picker_callback: function (callback, value, meta) {
            let input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');

            input.onchange = function () {
            let file = input.files[0];
            let reader = new FileReader();
            
            reader.onload = function () {
                callback(reader.result, { alt: file.name });
            };
            
            reader.readAsDataURL(file);
            };

            input.click();
        }
    });
</script>

<?php
}
else{
?>
<main class="content">
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3"><strong>Error 404 - Page not found</strong></h1>
    </div>
</main>
<?php
}
?>

