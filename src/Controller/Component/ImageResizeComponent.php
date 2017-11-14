<?php
namespace App\Controller\Component;

use Cake\Controller\Component;

class ImageResizeComponent extends Component { 
    // Resize any specified jpeg, gif, or png image 
    function resize($imagePath, $destinationWidth, $destinationHeight, $MaxWidth=0) { 
        // The file has to exist to be resized 
        if(file_exists($imagePath)) { 
            // Gather some info about the image 
            $imageInfo = getimagesize($imagePath); 
             
            // Find the intial size of the image 
            $sourceWidth = $imageInfo[0]; 
            $sourceHeight = $imageInfo[1]; 
             
            // Find the mime type of the image 
            $mimeType = $imageInfo['mime']; 
             
			 //adjust image size
			 $size = getimagesize($imagePath);
			 if($size[0]>$destinationWidth){
				 if (($size[1]/$destinationHeight) > ($size[0]/$destinationWidth))  // $size[0]:destinationWidth, [1]:destinationHeight, [2]:type 
					$destinationWidth = ceil(($size[0]/$size[1]) * $destinationHeight); 
				else  
					$destinationHeight = ceil($destinationWidth / ($size[0]/$size[1])); 
			 }else{
				$destinationWidth=$size[0];
			 }
			 
			 if($size[1]>$destinationHeight){ 
				//$destinationHeight = ceil($destinationWidth / ($size[0]/$size[1])); 
			 }else{
				$destinationHeight=$size[1];
			 }
			 
			 // Create the destination for the new image 
			$destination = imagecreatetruecolor($destinationWidth, $destinationHeight); 

			// Now determine what kind of image it is and resize it appropriately 
			if($mimeType == 'image/jpeg' || $mimeType == 'image/pjpeg') { 
				$source = imagecreatefromjpeg($imagePath); 
				imagecopyresampled($destination, $source, 0, 0, 0, 0, $destinationWidth, $destinationHeight, $sourceWidth, $sourceHeight);
				imagejpeg($destination, $imagePath); 
			} else if($mimeType == 'image/gif') { 
				$source = imagecreatefromgif($imagePath); 
				imagecopyresampled($destination, $source, 0, 0, 0, 0, $destinationWidth, $destinationHeight, $sourceWidth, $sourceHeight);
				imagegif($destination, $imagePath); 
			} else if($mimeType == 'image/png' || $mimeType == 'image/x-png') { 
				$source = imagecreatefrompng($imagePath); 
				imagecopyresampled($destination, $source, 0, 0, 0, 0, $destinationWidth, $destinationHeight, $sourceWidth, $sourceHeight);
				imagepng($destination, $imagePath); 
			} else { 
				$this->_error('This image type is not supported.'); 
			} 
			 
			// Free up memory 
			imagedestroy($source); 
			imagedestroy($destination); 
        } else { 
            $this->_error('The requested file does not exist.'); 
        } 
    } 
     
    // Outputs errors... 
    function _error($message) { 
        trigger_error('The file could not be resized for the following reason: ' . $message); 
    } 
} 
?>