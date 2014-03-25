<?php
namespace HR\Supprot;

use HR\Core\FrontendUtils;

class AjaxuploadAction extends FrontendController {

    function perform($reqParameters)
    {     
    	$minsize = 1;
        $maxsize = 100;
        $minPxSize = 0;
        $maxPxSize = 200;
        $picture_path = "";        
        $filetypeArray = array("jpg","jpeg","gif","png");
        $crop = false;
        $cropDimension = 180;
        $pxSizeMsg = VALIDATION_REGISTRATION_UPLOAD_PXSIZE;
        
        $picMaxSize = 300;
        $picMinPxSize = 50;
        $picMaxPxSize = 800;
        
        $tempFile = "";
        $jsonArray = array("status"=>"ok");

        if(isset($reqParameters["crop"]) && $reqParameters["crop"]) {
        	$crop = $reqParameters["crop"];
        	$minPxSize = 120;
        	$maxPxSize = 400;
        	$pxSizeMsg = VALIDATION_OFFER_CROP_PXSIZE_BIG;
        }
        
		if(isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["name"]!="") {
            if($_FILES["fileToUpload"]["size"]<=($minsize*1024) || $_FILES["fileToUpload"]["size"]>($maxsize*1024)) {	
            	$jsonArray["status"] = "fail";
                $jsonArray["message"] = VALIDATION_REGISTRATION_UPLOAD_FILESIZE;
            }                
            $imagetype = strtolower(substr($_FILES["fileToUpload"]["name"], strrpos($_FILES["fileToUpload"]["name"],".")+1));
            if(!in_array($imagetype,$filetypeArray)) {            	
                $jsonArray["status"] = "fail";
                $jsonArray["message"] = VALIDATION_REGISTRATION_UPLOAD_FILETYPE;
            }
            else {
                $imagetype = ($imagetype=="jpg")?"jpeg":$imagetype;
                $func_name = "imagecreatefrom".$imagetype;
                
                $tmp_img = $func_name($_FILES["fileToUpload"]["tmp_name"]);
                                
                if(imagesx($tmp_img)<$minPxSize || imagesy($tmp_img)<$minPxSize) {
                    $jsonArray["status"] = "fail";
                	$jsonArray["message"] = VALIDATION_OFFER_CROP_PXSIZE_SMALL;
                	
                } elseif(imagesx($tmp_img)>$maxPxSize || imagesy($tmp_img)>$maxPxSize) {
                	$jsonArray["status"] = "fail";
                	$jsonArray["message"] = $pxSizeMsg;
                }
                
                imagedestroy($tmp_img);
            }
            
            if($jsonArray["status"]=="ok") {
            	$tempFile = FrontendUtils::uploadPicture($_FILES["fileToUpload"]["tmp_name"],$_FILES["fileToUpload"]["name"]);
            	if($tempFile=="") {
            		$jsonArray["status"] = "fail";
            		$jsonArray["message"] = VALIDATION_REGISTRATION_UPLOAD_ERROR;
            	} else {
            		if($crop) {
            			FrontendUtils::cropAndSaveImage(MEDIA_DIR."temp_files/tmp_file_".$tempFile, MEDIA_DIR."temp_files/tmp_file_".$tempFile, $cropDimension, $cropDimension, $imagetype);
            		}
            		$jsonArray["tempFile"] = $tempFile;
            	}
            }            
        } elseif(isset($_FILES["pictureToUpload"]) && $_FILES["pictureToUpload"]["name"]!="") {
            if($_FILES["pictureToUpload"]["size"]<=($minsize*1024) || $_FILES["pictureToUpload"]["size"]>($picMaxSize*1024)) {	
            	$jsonArray["status"] = "fail";
                $jsonArray["message"] = VALIDATION_OFFER_PICTURE_FILESIZE;
            }                
            $imagetype = strtolower(substr($_FILES["pictureToUpload"]["name"], strrpos($_FILES["pictureToUpload"]["name"],".")+1));
            if(!in_array($imagetype,$filetypeArray)) {            	
                $jsonArray["status"] = "fail";
                $jsonArray["message"] = VALIDATION_REGISTRATION_UPLOAD_FILETYPE;
            }
            else {
                $imagetype = ($imagetype=="jpg")?"jpeg":$imagetype;
                $func_name = "imagecreatefrom".$imagetype;
                
                $tmp_img = $func_name($_FILES["pictureToUpload"]["tmp_name"]);

                $jsonArray["width"] = imagesx($tmp_img);
                $jsonArray["height"] = imagesy($tmp_img);
                               
                if(imagesx($tmp_img)<$picMinPxSize || imagesy($tmp_img)<$picMinPxSize) {
                    $jsonArray["status"] = "fail";
                	$jsonArray["message"] = VALIDATION_OFFER_PICTURE_PXSIZE_SMALL;
                	
                } elseif(imagesx($tmp_img)>$picMaxPxSize || imagesy($tmp_img)>$picMaxPxSize) {
                	$jsonArray["status"] = "fail";
                	$jsonArray["message"] = VALIDATION_OFFER_PICTURE_PXSIZE_BIG;
                }
                
                imagedestroy($tmp_img);
            }
            
            if($jsonArray["status"]=="ok") {
            	$tempFile = FrontendUtils::uploadPicture($_FILES["pictureToUpload"]["tmp_name"],$_FILES["pictureToUpload"]["name"]);
            	
            	
            	if($tempFile=="") {
            		$jsonArray["status"] = "fail";
            		$jsonArray["message"] = VALIDATION_REGISTRATION_UPLOAD_ERROR;
            	} else {            		
            		
            		$jsonArray["tempFile"] = $tempFile;
            	}
            }            
        }
        
        echo json_encode($jsonArray);
    }
}
//EOF
?>