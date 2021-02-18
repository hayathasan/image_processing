
<?php
    set_time_limit(1200);
    
    $src_dir   = $_POST['src_dir'];
    $dst_dir   = $_POST['dst_dir'];
    $height    = (int)$_POST['image_height'];
    $width     = (int)$_POST['image_width'];
    $bg_blur = isset($_POST['bg_blur']) && $_POST['bg_blur'] != '' ? $_POST['bg_blur'] : '';

    $logo_url  = isset($_POST['logo']) && $_POST['logo'] != '' ? $_POST['logo'] : '';
    $logo_x = isset($_POST['logo_x']) && $_POST['logo_x'] != '' ? $_POST['logo_x'] : '';
    $logo_y = isset($_POST['logo_y']) && $_POST['logo_y'] != '' ? $_POST['logo_y'] : '';
    $logo_center = isset($_POST['logo_center']) && $_POST['logo_center'] != '' ? $_POST['logo_center'] : '';

    $isAddText = isset($_POST['isAddText']) && $_POST['isAddText'] != '' ? $_POST['isAddText'] : '';
    $text      = $_POST['text'];

    $isAddCode = isset($_POST['isAddCode']) && $_POST['isAddCode'] != '' ? $_POST['isAddCode'] : '';
    $code      = $_POST['code'];
    $codeInt   = (int)$code['number_start'];

    $font = "C:\\Windows\\Fonts\\segoeui.ttf";
    $src_files = array_values(preg_grep('~\.(jpeg|jpg|png)$~', scandir($src_dir)));
    
    // echo "<pre>"; print_r($src_files); echo "<pre>"; 
    // echo "<pre>"; print_r($_POST); echo "<pre>"; 
    // exit();


    foreach ($src_files as $image) {

        $src_img = $src_dir."\\".$image;

        $ext = pathinfo($src_img, PATHINFO_EXTENSION);

        try {
            $info = getimagesize($src_img);
        } catch (Exception $e){
            return false;
        }

        $mimetype = image_type_to_mime_type($info[2]);

        switch ($mimetype) {
            case 'image/jpeg': $src_img = imagecreatefromjpeg($src_img); break;
            case 'image/gif' : $src_img = imagecreatefromgif($src_img); break;
            case 'image/png' : $src_img = imagecreatefrompng($src_img); break;
                 default     : return false;
        }

        // $degrees = 270;
        // $src_img = imagerotate($src_img, $degrees, 0);

        $src_w = imagesx($src_img);
        $src_h = imagesy($src_img);
        
        
        
        // create dst size blank image
        $maxfact    = max($width/$src_w, $height/$src_h);
        $dst_img_w = $src_w * $maxfact;
        $dst_img_h = $src_h * $maxfact;
        $dst_img   = imagecreatetruecolor($width, $height);
        

        
        
        
        if($bg_blur == 'on' && ($src_w/$src_h != 1)){
            
            // blur back image            
            imagecopyresampled($dst_img, $src_img, -(($dst_img_w - $width)/2), -(($dst_img_h - $height)/2), 0, 0, $dst_img_w, $dst_img_h, $src_w, $src_h);
            for ($x=1; $x<=80; $x++){ imagefilter($dst_img, IMG_FILTER_GAUSSIAN_BLUR,100); }        
            imagefilter($dst_img, IMG_FILTER_SMOOTH,100);
            imagefilter($dst_img, IMG_FILTER_BRIGHTNESS,10);

        } else {
            
            // fill solid color in back image instead of blur            
            // $bg_color  = imagecolorallocate($dst_img, 242, 242, 242); //255,255,255
            // imagefilledrectangle($dst_img,0,0,$width-1,$height-1,$bg_color);

        }

        
        
        // resize src_img with dest_img size
        $minfact    = min($width/$src_w, $height/$src_h);
        $src_img_new_w = $src_w*$minfact;
        $src_img_new_h = $src_h*$minfact;
        $src_img_new   = imagecreatetruecolor($src_img_new_w, $src_img_new_h);
        imagecopyresampled($src_img_new, $src_img, 0, 0, 0, 0, $src_img_new_w, $src_img_new_h, $src_w, $src_h);
        


        

        // merge front & back image
        imagecopymerge($dst_img, $src_img_new,-(($src_img_new_w - $width)/2), -(($src_img_new_h - $height)/2), 0, 0, $src_img_new_w, $src_img_new_h, 100);




        
        // add logo 
        if($logo_url != ''){
            $logo   = imagecreatefrompng($logo_url);
            $logo_w = imagesx($logo);
            $logo_h = imagesy($logo);

            if($logo_center == 'on'){                
                $logo_x = ($width / 2) - ($logo_w / 2); 
                $logo_y = ($height / 2) - ($logo_h / 2); 
            }          
            imagecopy($dst_img, $logo, $logo_x, $logo_y, 0, 0, $logo_w, $logo_h);
        }
        






        // add text            
        if($isAddText == 'on'){
            foreach ($text as $k => $v) {                
                $color_code = [255, 255, 255];
                addText($dst_img, $font,(int) $v['font_size'], (int) $v['x'], (int) $v['y'], $v['text'], $color_code);
            }            
        }
        




        // add code
        if($isAddCode == 'on'){
            // $black_h  = 50;
            // $imgBlack = imagecreatetruecolor($width, $black_h);
            // $bg_color = imagecolorallocate($imgBlack, 242, 242, 242);  //255,255,255
            // imagefilledrectangle($imgBlack, 0, 0, $width-1, $black_h-1, $bg_color);
            // imagecopy($dst_img, $imgBlack, 0, $height - $black_h, 0, 0, $width, $black_h);

            $codeText      = "Code: $codeInt";
            $codeTextWidth = imagettfbbox($code['font_size'], 0, $font, $codeText)[2];
            $color_code    = [0, 0, 0];
            addText($dst_img, $font, (int) $code['font_size'], $width - $codeTextWidth - (int) $code['x'], (int) $code['y'], $codeText, $color_code);
        }
        
        
        


        // output new file
        $quality = 50;
        if($isAddCode == 'on'){
            imagejpeg($dst_img, $dst_dir."\\".$codeInt.".".$ext, $quality);
            $codeInt++; 
        } else {
            imagejpeg($dst_img, $dst_dir."\\".$image, $quality);
        }   
        
        


        imagedestroy($dst_img);
        imagedestroy($src_img_new);

        if($isAddCode == 'on'){
            // imagedestroy($imgBlack);
        }    
    }



    echo "Image Processed Successfully";


    function addText($image, $font, $font_size=0, $text_x=0, $text_y=0, $text=" ", $cc){
        $color = imagecolorallocate($image, $cc[0], $cc[1], $cc[2]); 
        return imagettftext($image, $font_size, 0, $text_x, $text_y, $color, $font, $text);
    }
    
?>   

