<?php
namespace HR\Core;

require_once LIB_DIR.'phpass/PasswordHash.php';
use \PasswordHash as PasswordHash;

class FrontendUtils
{	
    private $monthsArray_en;
    private static $randChars = array (2, "o", 9, "R", "s", "y", "K", "P", 6, "w", "Y", "h", "C", "m", "A", "Z", "l", "D", 8, "k", "T", "p", "L", "c", "G", "I", "b", "O", "F", "z", "S", "t", "E", "a", 3, "d", "V", "q", "H", 7, "v", "u", "X", "f", "r", "W", "e", 4, "J", "N", "i", "B", "n", 5, "U", "j", "g", 1, "Q", "x", "M");
    private static $digitMap = array('0' => '13', '1' => '12', '2' => '31', '3' => '81', '4' => '15', '5' => '42', '6' => '37', '7' => '26', '8' => '59', '9' => '83');
    private static $randMap = array(1 => '4', 2 => '03', 3 => '724', 4 => '3036', 5 => '80192', 6 => '709365', 7 => '1375062', 8 => '95034712', 9 => '173054302');
    
    public static function validateSessionData($data, $attributes) {
    	foreach($data as $key => $value) {
    		if(!in_array($key, $attributes)) {
    			throw new BaseException('Session Management', 'Attempt to retrieve field that is not part of session attributes');
    		}
    	}
    	    	    	
    	foreach($attributes as $attribute) {
    		if(!array_key_exists($attribute, $data)) {
    			$data[$attribute] = '';
    		}
    	}
    	
    	return $data;
    }
    
    
    public static function isUploadFileValid($file, $type, &$errorMessage) {
    	$type = strtoupper($type);
    
    	if(!in_array(strtoupper($type), array('FILE', 'PICTURE'))) {
    		return false;
    	}
    	
    	// Checking for file error
    	if(isset($file['error']) && $file['error'] != UPLOAD_ERR_OK) {
    		$errorMessage = constant('VALIDATION_' . $type . '_UPLOAD_ERROR');
    		return false;
    	}
    	
    	// Checking for file size
    	$fileSizes = unserialize(constant($type . '_FILESIZE'));
    	if($file["size"] < ($fileSizes['min'] * 1024)) {
    		$errorMessage = constant('VALIDATION_' . $type . '_FILESIZE_SMALL');
    		return false;
    	} elseif($file["size"] > ($fileSizes['max'] * 1024)) {
    		$errorMessage = constant('VALIDATION_' . $type . '_FILESIZE_LARGE');
    		return false;
    	}
    	
    	// Checking for right extension    	
    	$fileNameParts = explode('.', $file['name']);
    	$extension = strtolower(end($fileNameParts));
    	$mimeType = self::getFileMimeType($file['tmp_name']);
    	if(!in_array($extension, unserialize(constant($type . '_ALLOWED_EXTENSIONS'))) || 
    	   !in_array($mimeType, unserialize(constant($type . '_ALLOWED_TYPES')))) {
    		$errorMessage = constant('VALIDATION_' . $type . '_TYPE');
    		return false;
    	}
    	
    	if($type == TYPE_PICTURE) {
    		// For pictures checking pixel size as well
    		$extension = ($extension == "jpg") ? "jpeg" : $extension;
            $funcName = "imagecreatefrom" . $extension;
            
            $tempImage = $funcName($file["tmp_name"]);
                              
            $pxSizes = unserialize(constant('PICTURE_PXSIZE'));
            if(imagesx($tempImage) < $pxSizes['min'] || imagesy($tempImage) < $pxSizes['min']) {                
            	imagedestroy($tempImage);
            	$errorMessage = VALIDATION_PICTURE_PXSIZE_SMALL;
            	return false;
            } elseif(imagesx($tempImage) > $pxSizes['max'] || imagesy($tempImage) > $pxSizes['max']) {
            	imagedestroy($tempImage);
            	$errorMessage = VALIDATION_PICTURE_PXSIZE_LARGE;
            	return false;
            }
            
            imagedestroy($tempImage);
    	}
    	
    	return true;
    }
    
        
    public static function getDate($start, $end, $type=0, $lang) {
        $dateArray = array();
        if($type == 0) {
            $dateArray[''] = ($lang=='ru')?"Год":"Year";
        } else {
            $dateArray[''] = ($lang=='ru')?"День":"Day";
        }
        
        for($i=$end; $i>=$start; $i--) {
            $dateArray[$i] = $i;
        }
        
        return $dateArray;
    }
    
    
    public static function constructDate($year, $month, $day='01') {       
        return $year.'-'.$month.'-'.$day;
    }
    
    public static function deconstructDate($date, &$year, &$month, &$day=0){
            list($year, $month, $day) = explode('-', $date);
    }
    
    public static  function getFormatedDate($date_string) {  
        $formated_date="";
        list($year, $month, $day) = explode('-', $date_string);
        $monthsArray = self::getMonths();
        
        $formated_date = $day." ".$monthsArray[$month]." ".$year;
        
        return $formated_date;
        
    }
    
    public static function calculatePaging($total_count,$page,$count) {
        $pagingArray = array();
        $pages_count = ceil($total_count/$count);
        
        $pagingArray["totalCount"] = $total_count;
        if($total_count > 0) {
            $pagingArray["resultFrom"] = ($page-1)*$count+1;
        }
        else {
            $pagingArray["resultFrom"] = 0;
        }
        if($total_count > ($page*$count)) {
            $pagingArray["resultTo"] = $page*$count;
            $pagingArray["next"] = ($page+1);           
            $pagingArray["nextHref"] = "/p/".($page+1);           
            $pagingArray["last"] = $pages_count;            
            $pagingArray["lastHref"] = "/p/".$pages_count;            
        }
        else         
            $pagingArray["resultTo"] = $total_count;
        
        if($page > 1) {        
            $pagingArray["prevHref"] = "/p/".($page-1);            
            $pagingArray["prev"] = ($page-1);            
            $pagingArray["firstHref"] = "/p/1";
            $pagingArray["first"] = "1";
        }
        
        return $pagingArray;
        
    }
    
    public static function calculateListing($total_count, $page, $count, $listing_count) { 
		$pages_count = ceil($total_count / $count);
		$listingArray = array();
		$loop_start = 0;
		$loop_end = 0;

		if($page < (ceil($listing_count / 2) + 1) && $total_count > $count) {
		   $loop_start = 1;
		   $loop_end = ($pages_count < $listing_count) ? $pages_count : $listing_count;
		   
		   $listingArray = self::getListingArray($page, $loop_start, $loop_end);
		} elseif(($page + floor($listing_count / 2)) <= $pages_count && $total_count > $count) {
		   $loop_start = $page - floor($listing_count / 2);
		   $loop_end = (($page + floor($listing_count / 2)) > $pages_count) ? $pages_count : $page + floor($listing_count / 2);
		   
		   $listingArray = self::getListingArray($page, $loop_start, $loop_end);
		} elseif($total_count > $count) {
		   $loop_start = ($pages_count < $listing_count) ? 1 : ($pages_count - $listing_count + 1);		   
		   $loop_end = $pages_count;

		   $listingArray = self::getListingArray($page, $loop_start, $loop_end);
		}
		
		return $listingArray;
   
    }
    
    
     public static function getListingArray($page, $start, $end) {
        $counter = 0;
        $listingArray = array();
        
        for ($i=$start; $i<=$end; $i++) {
           $listingArray[$counter]["digit"] = $i;
           if($i==$page) {
               $listingArray[$counter++]["active"] = 1;
           } else {
               $listingArray[$counter++]["active"] = 0;
           }
       }
       return $listingArray;
    }
    
    
    public function constructGetString($reqParameters) {       
        $getString = "";
        if(is_array($reqParameters) && !empty($reqParameters)) {       
            $getKeys = array_keys($reqParameters);
            $getString = "?";
            for($i=0; $i<count($reqParameters); $i++) {
                if($i==0) {
                    $getString .= $getKeys[$i] . "=" . $reqParameters[$getKeys[$i]];
                } else {
                    if(is_array($reqParameters[$getKeys[$i]])) {
                        foreach($reqParameters[$getKeys[$i]] as $val) {
                            $getString .= "&" . $getKeys[$i] . "%5B%5D=".$val;
                        }
                    } else {
                        $getString .= "&" . $getKeys[$i] . "=" . $reqParameters[$getKeys[$i]];
                    }
                }
            }
        }
        return str_replace(" ", "+", $getString);
    }
    
    
    
    public static function generateFileName($filename) {
        $microsecs = "";
        $secs = "";
        $filetype = "";
        
        $filetype = substr($filename, strrpos($filename, ".") + 1); 
        list($microsecs, $secs) = explode(" ", microtime());       
        $microsecs = substr($microsecs, 2);
        
        
        return "temp_file_" . $secs.$microsecs . "." . $filetype;
    }
    
    
    
    public static function generateUniqueId($id=0) {
        $microsecs = "";
        $secs = "";        
                
        list($microsecs,$secs) = explode(" ",microtime());       
        $microtime = $secs + $microsecs;
                        
        if($id==0)
            return md5($microtime);
        else 
            return md5($id.$microtime);
    }
    
    
    public static function notEmpty($string) {
    	return (!is_null($string) && trim($string) !== "");
    }
    
    
    public static function isEmailAddress($mail) {
	    $mail = trim($mail);
	    if($mail == '') {
	        return false;
	    }

        $is_valid = !(preg_match('!@.*@|\.\.|\,|\;!', $mail) ||
        !preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i', $mail));            
                                
        return $is_valid;
    }
    
    
    public static function isLinkedIn($url) {
	    $url = trim($url);
	    if($url == '') {
	        return true;
	    }
	    
        return preg_match('/^((http|https):\/\/)?((www|\w{2})\.)?linkedin.com\/((in\/[^\/]+\/?)|(pub\/[^\/]+\/(\w+\/?){3}))$/i', $url);
    }
    
    public static function isFacebook($url) {
	    $url = trim($url);
	    if($url == '') {
	        return true;
	    }

	    return preg_match('#https?\://(?:www\.)?facebook\.com/(\d+|[A-Za-z0-9\.]+)/?#', $url);
    }
    
	public static function isTwitter($url) {
	    $url = trim($url);
	    if($url == '') {
	        return true;
	    }
	    
        return  preg_match("|https?://(www\.)?twitter\.com/(#!/)?@?([^/]*)|", $url, $matches);
    }
    
    public static function isLatin($string) {
    	$string = trim($string);
	    if($string == '') {
	        return true;
	    }

	    return preg_match('/^[a-zA-Z\s,]*$/u', $string);
    }
    
    
    public static function isDate($date) {
    	$date = trim($date);
	    if($date == '') {
	        return true;
	    }
	    
	    return strtotime($date);
    }
    
    
    public static function isNumber($string) {
	    if(strlen($string) == 0)
        	return false;        

    	return preg_match('/[0-9\.\-\ ]{6}/', $string);
    }
    
    
    public static function isPasswordLength($password) {
    	if($password != '') {
    		return (mb_strlen($password) >= HR_MIN_PASSWORD_LENGTH);
    	}
    	
    	return true;
    }

       
    public static function passwordsMatch($passwords) {
    	if(is_array($passwords) && count($passwords) == 2) {
    		return ($passwords[0] === $passwords[1]);
    	}
    	
    	return false;
    }

    
    public static function notDuplicate($array) {
    	return count($array) == count(array_unique($array));
    }       
    
    
    public static function isEmailAddresses($mail_string) {

        if(strlen($mail_string) == 0)
            return false;
    
        // in case value is several addresses separated by newlines
        $mailArray = explode(',', $mail_string);
    
        foreach($mailArray as $mail) {
            $mail = trim($mail);
            if($mail == '')
                continue;
                $is_valid = !(preg_match('!@.*@|\.\.|\,|\;!', $mail) ||
                !preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i", $mail));            
            
            if(!$is_valid)
                return false;
        }
        return true;
    }
    
    
    public static function isUsernameValid($username) {
    	$username = trim($username);
	    if($username == '') {
	        return false;
	    }
	    
        return (preg_match("/^[a-z0-9_\-]+$/i", $username));
    }
    
    
    public static function randomString($length, $captcha = false, $nocase = false) {
    	
    	$randomString = "";
        $charsArray = array(2, 3, 4, 7, 9); // 1, 5, 6 and 8 may look like characters.
        $charsArray = array_merge($charsArray, array('A','C','D','E','F','H',
        'J','K','L','M','N','P','Q','R','T',
        'U','V','W','X','Y','Z'));
        // I, O, S, B may look like numbers

        if(!$captcha) {
            $charsArray = array_merge($charsArray,array(1, 5, 6, 8 ,'I','O','S','B'));
        }
        
        $charIndxArray   = array_rand($charsArray, $length);
        
        foreach($charIndxArray as $charidx) {           
            if(rand(0,1)==0 && !$captcha && $nocase) {
                $randomString .= strtolower($charsArray[$charidx]);	
            } else {
                $randomString .= $charsArray[$charidx];    
            }
        }        

        return $randomString;
    }
    
    
    public static function createCaptchaImage($captcha_string) {
        list($musec, $msec) = explode(' ', microtime());
        $srand = (float) $msec + ((float) $musec * 100000);
        srand($srand);
        mt_srand($srand);


        $width    = 120;
        $height   = 40;

        $bgcolors = explode(',', '245,245,245');

        // available font-types (not all are used):
        // Vera.ttf, VeraSe.ttf, chumbly.ttf, 36daysago.ttf
        $fontfiles = array('DexterC.ttf', 'heck.ttf', 'Astonished.ttf');

        $charsArray  = str_split($captcha_string);
        $fontname = $fontfiles[array_rand($fontfiles)];
        $font     = LIB_DIR.'modules/frontend/Reg/fonts/'.$fontname;

        $image  = imagecreate($width, $height);
        imagecolorallocate($image, trim($bgcolors[0]), trim($bgcolors[1]), trim($bgcolors[2]));

        //$pos_x  = 5;
        $pos_x = ($width / sizeof($charsArray)) / 6; 
        foreach($charsArray as $char) {
            $color = imagecolorallocate($image, mt_rand(50, 220), mt_rand(50, 220), mt_rand(50,220));
            $size  = mt_rand($width / 7, $width / 6.8);
            $angle = mt_rand(-25, 25);
            $pos_y = ceil($height - (mt_rand($size/1.3, $size/1.6)));
            

            imagettftext(
            $image,
            $size,
            $angle,
            $pos_x,
            $pos_y,
            $color,
            $font,
            $char
            );

            $pos_x = $pos_x + $size + (($width / sizeof($charsArray)) / 6);

        }
        /*
        header('Content-Type: image/jpeg');
        imagejpeg($image, '', 90);
        */
        header('Content-type: image/png');
        imagepng($image);
        imagedestroy($image);

    }
    
    
    public static function outputImage($filePath, $dimension, $dimensionSize) {
        $width = 0;
        $height = 0;
        $ratio = 0;

        list($width, $height) = getimagesize($filePath);
        
        if($dimension == 'w') {
	        $ratio = floatval($width) / floatval($dimensionSize);        
	        $newWidth = $dimensionSize;
	        $newHeight = $height / $ratio;
        } elseif($dimension == 'h') {
        	$ratio = floatval($height) / floatval($dimensionSize);        	        
	        $newHeight = $dimensionSize; 
	        $newWidth = $width / $ratio;
        }
        
        $fileNameParts = explode('.', $filePath);
        $extension = strtolower(end($fileNameParts));        
        
        $imagecreateFunc = "imagecreatefrom" . $extension;        
        
        // Resample
        $newImage = imagecreatetruecolor($newWidth, $newWidth);
        $white = imagecolorallocate($newImage, 255, 255, 255);
        imagefill($newImage, 0, 0, $white);
        
        $image = $imagecreateFunc($filePath);
        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        
        Response::setHeader('Content-type', 'image/' . $extension);
        
        switch($extension) {
            case 'jpeg':
                imagejpeg($newImage, null, 100);
                break;
            case 'png':
                imagepng($newImage, null);
                break;
            case 'gif':
                imagegif($newImage);         
        }
    }
    
    
    public static function getTagCloud($allTags) {        
        $tagCloud = array();
        $numArray = array();
        $count = count($allTags);        
        $key = 0;
        
        for($i=0;$i<$count;$i++) {
            if(!in_array($allTags[$i], $tagCloud)) {
                $num = 1;
                for($j=$i+1; $j<$count; $j++) {
                    if($allTags[$i]==$allTags[$j]) {                        
                        $num++;
                    }
                }
            
                $tagCloud[$key] = $allTags[$i];
                $numArray[$key++] = $num;
            }
        }
        
        $max_num = max($numArray);
        $min_num = min($numArray);
        $num_range = ($max_num-$min_num==0)?1:($max_num-$min_num);
        
        $coef = (TAG_MAX_SIZE-TAG_MIN_SIZE)/$num_range;
        
        foreach($tagCloud as $key => $value) {            
            $tagCloud[$key]['font_size'] = ceil(TAG_MIN_SIZE + $coef * ($numArray[$key] - $min_num));
            $tagCloud[$key]['url_tag'] = urlencode($value['tag']);
        }
        
        return $tagCloud;
    }    
    
    
    public static function uploadFile($tempFilename, $fileName, $keepName = false) {
    	// Getting file extension
    	$fileNameParts = explode('.', $fileName);
        $extension = strtolower(end($fileNameParts));
        // Getting file name without extension
        $fileName = trim($fileName, '.' . $extension);
        $extension = ($extension == 'jpg') ? 'jpeg' : $extension;
        // Generating new temporary file name
        $newFileName = ($keepName) ? self::randomString(4, false, true) . '_' . str_replace(' ', '_', $fileName) : self::randomString(10, false, true);
        $filePath = sprintf(TEMP_FILE_PATH, $newFileName, $extension);

        if (move_uploaded_file($tempFilename, $filePath)) {
            return $newFileName;
        }
        else {
            return '';
        }
    }
    
    
    public static function uploadVideo($tempFilename,$fileName,$newFileName) {
    	$microsecs = "";
        $secs = "";
        $fileType = "";
        
        $fileType = substr($fileName, strrpos($fileName,".")+1); 
        
        /*srand((double) microtime()*1000000);          
        
        $newFileName = rand(0,99999999).".".$fileType;        */
        // Generating 10 symbols length random string
        //$newFileName = self::randomString(10, false, true);
        $filePath = MEDIA_DIR."videos/".$newFileName.".".$fileType;

        if (move_uploaded_file($tempFilename, $filePath)) {
            return $newFileName;
        }
        else {
            return "";
        }

    }

    
    public static function getTempFilePath($key) {
    	$allExtensions = array_merge(unserialize(HR_PICTURE_EXTENSIONS), unserialize(HR_FILE_EXTENSIONS));
    	
    	foreach($allExtensions as $extension) {
	    	if(file_exists(sprintf(TEMP_FILE_PATH, $key, $extension))) {
	    		return sprintf(TEMP_FILE_PATH, $key, $extension);
	    	}
    	}
	    	
    	return '';
    }
    
    
    public static function getPicturePath($key, $type = USER, $square = false) { 
    	$picturePath = constant(strtoupper($type) . (($square) ? '_SQUARE' : '') . '_PICTURE_PATH');   	
    	foreach(unserialize(HR_PICTURE_EXTENSIONS) as $extension) {
	    	if(file_exists(sprintf($picturePath, $key, $extension))) {
	    		return sprintf($picturePath, $key, $extension);
	    	}
    	}    	
    	
    	return '';
    }
    
    
    public static function getUserFilePath($key, $square = false) {
    	return array(
    			   'original' => self::getPicturePath($key, USER), 
    			   'square' => self::getPicturePath($key, USER, true)
			   );
    }

    
    public static function getCompanyFilePath($key) {
    	return self::getPicturePath($key, COMPANY);
    }
    
    
	public static function getFilePath($key, $type = RESUME) { 
    	$picturePath = constant(strtoupper($type) . '_PATH');
    	foreach(unserialize(HR_FILE_EXTENSIONS) as $extension) {
	    	if(file_exists(sprintf($picturePath, $key, $extension))) {
	    		return sprintf($picturePath, $key, $extension);
	    	}
    	}    	
    	
    	return '';
    }
    
    
    public static function getResumeFilePath($key) {
    	return self::getFilePath($key, RESUME);
    }

    
    public static function getVacancyFilePath($key) {
    	return self::getFilePath($key, VACANCY);
    }

    public static function saveTemporaryFile($key, $type, $stripPrefix = false) {
    	// Finding temp path by key
    	$tempLocation = self::getTempFilePath($key);
		
    	if($tempLocation != '') {
	    	$fileNameParts = explode('.', $tempLocation);
	    	$extension = strtolower(end($fileNameParts));
	    	
	    	switch($type) {
	    		case USER: 
	    			$permanentLocation = sprintf(USER_PICTURE_PATH, $key, $extension);
	    			break;
    			case COMPANY:
    				$permanentLocation = sprintf(COMPANY_PICTURE_PATH, $key, $extension);
    				break;
				case RESUME:
					$permanentLocation = sprintf(RESUME_PATH, $key, $extension);
					break;
				case VACANCY:
					$permanentLocation = sprintf(VACANCY_PATH, $key, $extension);
					break;
	    	}
	    	
	    	if(file_exists($tempLocation)) {
	    		if(rename($tempLocation, $permanentLocation)) {
	    			return $permanentLocation;
	    		}
	    	}
    	}
    	
    	return false;
    }
    
    
    public static function deleteFile($filePath) {
    	if(!file_exists($filePath)) {
    		return false;
    	}
    	
    	return unlink($filePath);
    }
    
    
    public static function generateQrImage($secretKey, $imageSize=500) {
    	$fileName = "";
    	
    	// Response url to check coupon as used
    	$qrUrl = "http://".$_SERVER["HTTP_HOST"]."/coupon/read/sk/".$secretKey."/t/";
    	
    	$pictureUrl = "http://chart.apis.google.com/chart?chs=".$imageSize."x".$imageSize."&cht=qr&chld=L|0&chl=".urlencode($qrUrl);
    	    	
    	$image = file_get_contents($pictureUrl);

    	// A little bit risky, but we still assume google charts to return only PNG images in the future as well, since unable to get image extension
    	$extension = "png";
    	
    	$fileName = $secretKey.".".$extension;
    	
		$wr = fopen(MEDIA_DIR.'qrs/'.$fileName, 'w+');
		fputs($wr, $image);
		fclose($wr);
		
		if(file_exists(MEDIA_DIR.'qrs/'.$fileName)) {
			return true;
		}
		
		return false;
    }
    
    
    
    public static function getDateAgoString($minsAgo, $short=false, $addStr=" ago") {
    	$agoStr = "";
    	
    	if($minsAgo == 0) {
    		return $agoStr = "just now";
    	} elseif($minsAgo < 60) {
    		$label = ($short) ? "m" : " minute";
    		$agoStr = $minsAgo.$label.(($minsAgo == 1 || $short) ? "" : "s").$addStr;
    		return $agoStr;
    	} elseif($minsAgo < 60*24) {
    		$label = ($short) ? "h" : " hour";
    		$hours = floor($minsAgo/60);
    		$agoStr = $hours.$label.(($hours == 1 || $short) ? "" : "s").$addStr;
    		return $agoStr;
    	} elseif($minsAgo < 60*24*31) {
    		$label = ($short) ? "d" : " day";
    		$days = floor($minsAgo/(60*24));
    		$agoStr = $days.$label.(($days == 1 || $short) ? "" : "s") .$addStr;
    		return $agoStr;
    	} elseif($minsAgo < 60*24*365) {
    		$label = ($short) ? "mo" : " month";
    		$months = floor($minsAgo/(60*24*31));
    		$agoStr = $months.$label.(($months == 1 || $short) ? "" : "s") .$addStr;
    		return $agoStr;
    	} else {
    		$label = ($short) ? "y" : " year";
    		$years = floor($minsAgo/(60*24*365));
    		$agoStr = $years.$label.(($years == 1 || $short) ? "" : "s") .$addStr;
    		return $agoStr;
    	}
    }
    
    
    public static function cropAndSaveImage($picturePath, $destinationPath, $newWidth, $newHeight, $type, $croppedWidth=0, $croppedHeight=0, $cropX=0, $cropY=0) {
    	// Getting dimensions of the original picture
		list($originalWidth, $originalHeight) = getimagesize($picturePath);
		
		if($originalWidth >= $newWidth || $originalHeight >= $newHeight) {
	    	// Getting image resource for the original image
	    	$imagecreate = 'imagecreatefrom' . $type;
	    	
	    	if(function_exists($imagecreate)) {
		    	$originalImage = $imagecreate($picturePath);	    	
		    	
		    	if($croppedWidth == 0 && $croppedHeight == 0) {								
					$originalRatio = $originalWidth / $originalHeight;
					$newRatio = $newWidth/$newHeight;
					
					// Getting the dimensions of cropped original image
					if($originalRatio < $newRatio) {
						$croppedWidth = ceil($originalWidth);
						$croppedHeight = ceil($croppedWidth / $newRatio);
					} else {
						$croppedHeight = ceil($originalHeight);
						$croppedWidth = ceil($croppedHeight * $newRatio);
					}
					
					// Calculating crop coordinates
					if($cropX == 0 && $cropY == 0) {
						$cropX = ceil(($originalWidth - $croppedWidth) / 2);
						$cropY = ceil(($originalHeight - $croppedHeight) / 2);
					}		
		    	}
				
				// Creating new image resource for our result image
				$newImage = imagecreatetruecolor($newWidth, $newHeight);
				
				// Cropping and resizing the original image
				imagecopyresampled($newImage, $originalImage, 0, 0, $cropX, $cropY, $newWidth, $newHeight, $croppedWidth, $croppedHeight);
				
				// Saving the result image into file
				$imageFunc = 'image' . $type;
				$quality = ($type == 'png') ? 8 : 90;
				if(function_exists($imageFunc)) {
					return $imageFunc($newImage, $destinationPath, $quality);
				}
	    	}
		}
		
		return false;
    }

    
    public static function getCoverPhotoUrl($externalId) {
    	$coverUrl = "";
    	
    	$curlOptions = array(
    		CURLOPT_HEADER => 0,
			CURLOPT_RETURNTRANSFER => 1,			
			CURLOPT_HTTPHEADER => array('Accept: application/json', 'Content-Type: application/json'),
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_SSL_VERIFYHOST => 2,
			CURLOPT_TIMEOUT => 1
		);


		// FB Graph url for cover
    	$requestUrl = "https://graph.facebook.com/".$externalId."?fields=cover";

		// Configuring curl options
		$curlOptions[CURLOPT_URL] = $requestUrl;
		
		$curl = curl_init();
		 
		// Setting curl options
		curl_setopt_array($curl, $curlOptions);

		// Getting results
		$jsonOutput =  curl_exec($curl);		

		// Check for curl errors
		if ($error = curl_error($curl)) {
			return "";
		} else {
			$output = json_decode($jsonOutput, true);

			if(isset($output["cover"]) && isset($output["cover"]["source"])) {
				$coverUrl = $output["cover"]["source"];
			}
		}
		
		// Close handle
		curl_close($curl);
		
		return $coverUrl;
    }
    
    
    /*public static function urlizeEntity($entityName) {    	
    	$entityName = join("-", explode(" ", preg_replace('(\-)', '--', trim($entityName))));
    	return strtolower($entityName);
    }*/
    
    
    public static function urlizeEntity($entityName) {    
    	$pattern = "/ |,|\.|\/|\:|\\\|\;|%|\(|\)|\{|\}|\?|\[|\]|\*|\\$|\@|\&/";			
		return preg_replace("/^-|-$/", "", preg_replace("/(-){2,}/", "-", preg_replace($pattern, "-", strtolower($entityName))));
    }
    
    
    public static function saveRemotePicture($pictureUrl, $newLocation, $pictureName) {
    	$fileName = "";    	    	
    	    	
    	$image = file_get_contents($pictureUrl);
    	
		$wr = fopen(MEDIA_DIR.$newLocation.$pictureName, 'w+');
		fputs($wr, $image);
		fclose($wr);
		
		if(file_exists(MEDIA_DIR.$newLocation.$pictureName)) {
			return true;
		}
		
		return false;
    }
        
    
    
    public static function constructUserApiKey($userId) {
    	return sha1(MAVEN_API_KEY.$userId);
    }
    
    
    public static function hrEncode($number, $hashLenght = 12, $onlyDigits = false) {
    	return $onlyDigits ? self::hrStringEncode($number, $hashLenght) : self::hrDigitEncode($number);
    }
    
    
    public static function hrDecode($hashStr) {
    	return ctype_digit($hashStr) ? self::hrDigitDecode($hashStr) : self::hrStringDecode($hashStr);
    }
    
    
	private static function hrStringEncode($number, $hashLength = 12) {				
		$numberStr = (string) $number;
		$length = strlen($numberStr);
		$hashStr = "";		
		$count = count(self::$randChars);
		$startIdx = $number % $count;
		$step = 0;
	
		for($i = 1; $i <= $hashLength; $i++) {
			if($i <= $length) {
				if($i < $length && isset($numberStr[$length-$i-1]) && $numberStr[$length-$i] != "0" && $numberStr[$length-$i-1] != "0" && intval($numberStr[$length-$i].$numberStr[$length-$i-1]) < $count) {
					$currentDigit = intval($numberStr[$length-$i].$numberStr[$length-$i-1]);
				} else {
					$currentDigit = intval($numberStr[$length-$i]);
				}
				
				$step += intval($numberStr[$i-1]);
				$hashStr .= self::$randChars[$currentDigit];
			} else {
				if($i == ($hashLength -1)) {
					$hashStr .= self::$randChars[$length];
				} else {
					$idx = (($i*$step) + $number) % $count;			
					$hashStr .= self::$randChars[$idx];
				}
			}
		}
		
		return $hashStr;
	}
	
	
	private static function hrStringDecode($hashStr) {		
		$count = count(self::$randChars);
		$numbersStr = '';
	
		$length = array_search($hashStr[strlen($hashStr) - 2], self::$randChars);
		
		if(strlen($hashStr) > $length) {
			$digitsIdxs = substr($hashStr, 0, $length);
						
			for($i=$length-1; $i>=0; $i--) {				
				$numberStr = (string) array_search($digitsIdxs[$i], self::$randChars);
				$numbersStr .= $numberStr[0];
			}
		}
		
		return (int) $numbersStr;
	}
	
	
	private static function hrDigitEncode($number) {    	
    	$hashStr = '';
    	$numberStr = (string) $number;
    	$length = strlen($numberStr);
    	$randPattern = self::$randMap[$length];    	
    	
    	for($idx = 0; $idx < $length; $idx++) {
    		$substituteStr = self::$digitMap[$numberStr[$idx]];
    		$hashStr .= substr_replace($substituteStr, $randPattern[$idx], 1, 0);
    	}    
    	
    	return $hashStr;
    }
    
    
    private static function hrDigitDecode($hashStr) {    	
    	$numberStr = '';
    	$length = strlen($hashStr);    	
    	
    	for($idx = 0; $idx < $length; $idx++) {
    		if(($idx + 2) % 3 == 0) {
    			$key = $hashStr[$idx-1] . $hashStr[$idx+1];
    			$numberStr .= array_search($key, self::$digitMap);
    		}
    	}
    	
    	return (int) $numberStr;
    }

	/**
	 * Calculates the great-circle distance between two points, with
	 * Spherical Earth formula.
	 * @param float $latFrom Latitude of start point in [deg decimal]
	 * @param float $lngFrom Longitude of start point in [deg decimal]
	 * @param float $latTo Latitude of target point in [deg decimal]
	 * @param float $lngTo Longitude of target point in [deg decimal]
	 * @param float $measureToKm Ratio of given measure to km (e.g. km/mile)
	 * @param float $earthRadius Mean earth radius in [km]
	 * @return float Distance between points (same as earthRadius)
	 */
	public static function calculateDistance($latFrom, $lngFrom, $latTo, $lngTo, $measureToKm=1, $earthRadius=6371.009) {
		// Converting degrees to radians
		$latFrom = deg2rad($latFrom);
		$lngFrom = deg2rad($lngFrom);
		$latTo = deg2rad($latTo);
		$lngTo = deg2rad($lngTo);
		
		// Calculating differences
		$latDelta = $latTo - $latFrom;
		$lngDelta = $lngTo - $lngFrom;
		$latMean = ($latFrom + $latTo) / 2;
		
		// Here comes the formula
		$distance = $measureToKm * $earthRadius * sqrt(pow($latDelta, 2) + pow((cos($latMean) * $lngDelta) ,2));
		
		$precision = ($distance > 100) ? 0 : 1;
		
		return round($distance, $precision);
	}
	
	
	
	public static function generateBcryptHash($string) {
		$bcrypt = new PasswordHash(12, false);		
		$string = $bcrypt->hashPassword($string);		
		// Cutting the bcrypt start ($2a$12$)
		return substr($string, 7);
	}
	
	
	public static function checkBcryptHash($string, $hash) {
		$bcrypt = new PasswordHash(12, false);
		// Concatenating previously cut bcrypt part
		$hash = "$2a$12$" . $hash;
		return $bcrypt->checkPassword($string, $hash);
	}
	
	
	public static function getRefArray($inputArray) {	    
        $refArray = array();
        
        foreach($inputArray as $key => $val) {
            $refArray[$key] = &$inputArray[$key];
        }
        
        return $refArray;	    
	} 
	
	
	public static function strposa($haystack, $needles, $offset, $escapeChar = '') {
		$posArray = array();
		if(!is_array($needles)) $needles = array($needles);
		
        foreach($needles as $needle) {
        	if(!in_array($needle, $posArray)) {
	            $pos = strpos($haystack, $needle, $offset);
	            
	            if($pos != false) {
	            	// Skipping escaped $needle characters (e.g. %%d)
	            	$needleFound = true;
	            	while(($haystack[$pos - 1] == $escapeChar) || !$needleFound) {
	            		if(strpos($haystack, $needle, $pos + 1)) {
	            			$pos = strpos($haystack, $needle, $pos + 1);
	            		} else {
	            			// No unecsaped charecter found in string, ending while
	            			$needleFound = false;
	            		}
	            	}
	            	
	            	if($needleFound) $posArray[$pos] = $needle;
	            }	            
        	}
        }
        
        if(empty($posArray)) return false;
        
        $minPos = min(array_keys($posArray));
        return array($minPos, $posArray[$minPos]);
	}
	
	public static function truncateString($string, $length) {
		if(strlen($string) > $length) {
			return substr($string, 0, $length)."..";
		}
		
		return $string;
	}
	
	
	public static function getFileMimeType($file, $encoding = false) {
	    $mime = '';

	    if(function_exists('finfo_file')) {
	        $finfo = finfo_open(FILEINFO_MIME);
	        $mime = finfo_file($finfo, $file);
	        finfo_close($finfo);
	    }
	    elseif(substr(PHP_OS, 0, 3) == 'WIN') {
	        $mime = mime_content_type($file);
	    }
	    else {
	        $file = escapeshellarg($file);
	        $cmd = "file -iL $file";
	
	        exec($cmd, $output, $r);
	
	        if ($r == 0) {
	            $mime = substr($output[0], strpos($output[0], ': ') + 2);
	        }
	    }
	
	    if($mime == '') {
	        return false;
	    }

		if ($encoding) {
	        return $mime;
	    }
    
        return substr($mime, 0, strpos($mime, '; '));
	}
	
	
	public static function arrayUnique($array) {
		$dupes = array();
		$count = count($array);
		foreach(array_reverse($array) as $key => $value) {
			if(in_array($value, $dupes)) {
				unset($array[$count - $key - 1]);
			} else {			
				$dupes[] = $value;
			}
		}
		
		return $array;
	}
	
}
?>