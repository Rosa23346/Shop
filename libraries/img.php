<?php
function imageResize($thumbnailImage,$width,$height,$targetWidth,$targetHeight){ //$thumbnailImage is​ thumbnail រូបដើម
    $resizeThumbnailImg = imagecreatetruecolor($targetWidth,$targetHeight);  //imagecreatetruecolor បង្កើតរូបcolorទទេមួយ ដែលមានទំហំ target
    imagecopyresampled($resizeThumbnailImg,$thumbnailImage,0,0,0,0,$targetWidth,$targetHeight,$width,$height); //copy by ori to target width and height
    return $resizeThumbnailImg;
}
function createThumbnail($imageType,$file,$sourceWidth,$sourceHeight,$targetWidth,$targetHeight,$folderPath,$fileNewName){
    switch($imageType){
        case IMAGETYPE_GIF:
            $thumbnailImage = imagecreatefromgif($file);  //imagecreatefrom mean create thumbnailImage which has size like $file
            $resizeThumbnailImg = imageResize($thumbnailImage,$sourceWidth,$sourceHeight,$targetWidth,$targetHeight);
            imagegif($resizeThumbnailImg,$folderPath . "/" . $fileNewName); //save img by memory to hard disk (follow filepath)
            break;
        case IMAGETYPE_JPEG:
            $thumbnailImage = imagecreatefromjpeg($file);
            $resizeThumbnailImg = imageResize($thumbnailImage,$sourceWidth,$sourceHeight,$targetWidth,$targetHeight);
            imagejpeg($resizeThumbnailImg,$folderPath . "/" . $fileNewName);
            break;
        case IMAGETYPE_PNG:
            $thumbnailImage = imagecreatefrompng($file);
            $resizeThumbnailImg = imageResize($thumbnailImage,$sourceWidth,$sourceHeight,$targetWidth,$targetHeight);
            imagepng($resizeThumbnailImg,$folderPath."/".$fileNewName);
            break;
        // case IMAGETYPE_ICO:
        //     $thumbnailImage = imagecreatefrompng($file);
        //     $resizeThumbnailImg = imageResize($thumbnailImage,$sourceWidth,$sourceHeight);
        //     imagepng($resizeThumbnailImg,$folderPath."/thumbnail/".$fileNewName);
        //     break;
        default:
            return false;
            break;
    }
}
?>