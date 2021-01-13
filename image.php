
<?php
    set_time_limit(1200);
    
    $src_dir   = $_POST['src_dir'];
    $dst_dir   = $_POST['dst_dir'];
    $height    = (int)$_POST['image_height'];
    $width     = (int)$_POST['image_width'];

    $logo_url  = isset($_POST['logo']) && $_POST['logo'] != '' ? $_POST['logo'] : '';
    $logo_x = isset($_POST['logo_x']) && $_POST['logo_x'] != '' ? $_POST['logo_x'] : '';
    $logo_y = isset($_POST['logo_y']) && $_POST['logo_y'] != '' ? $_POST['logo_y'] : '';
    $logo_center = isset($_POST['logo_center']) && $_POST['logo_center'] != '' ? $_POST['logo_center'] : '';

    $text      = [];
    $code      = '';
    $codeInt   = '';

    // $logo_url  = isset($_POST['logo']) && $_POST['logo'] != '' ? $_POST['logo'] : '';
    // $text      = isset($_POST['text']) && count($_POST['text']) > 0 ? $_POST['text'] : '';
    // $code      = isset($_POST['code']) && $_POST['code']['text'] != '' > 0 ? $_POST['code'] : '';
    // $codeInt   = $code != '' ? (int)$code['text'] : 1;

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

        $img_blur = imagecreatetruecolor($width, $height);
        
        $src_w    = imagesx($src_img);
        $src_h    = imagesy($src_img);
        $maxfact = max($width/$src_w, $height/$src_h);
        $new_w   = $src_w * $maxfact;
        $new_h   = $src_h * $maxfact;

        imagecopyresampled($img_blur, $src_img, -(($new_w - $width)/2), -(($new_h - $height)/2), 0, 0, $new_w, $new_h, $src_w, $src_h);

        // blur back image
        for ($x=1; $x<=40; $x++){
            imagefilter($img_blur, IMG_FILTER_GAUSSIAN_BLUR,300);
        }
        
        // imagefilter($img_blur, IMG_FILTER_GAUSSIAN_BLUR,300);
        imagefilter($img_blur, IMG_FILTER_SMOOTH,100);
        imagefilter($img_blur, IMG_FILTER_BRIGHTNESS,10);

        $minfact  = min($width/$src_w, $height/$src_h);
        $new_w    = $src_w*$minfact;
        $new_h    = $src_h*$minfact;
        $img_main = imagecreatetruecolor($new_w, $new_h);
        imagecopyresampled($img_main, $src_img, 0, 0, 0, 0, $new_w, $new_h, $src_w, $src_h);
        
        // merge front & back image
        // imagecopymerge($img_blur, $img_main,-(($new_w - $width)/2), -(($new_h - $height)/2), 0, 0, $new_w, $new_h, 100);
        imagecopy($img_blur, $img_main,-(($new_w - $width)/2), -(($new_h - $height)/2), 0, 0, $new_w, $new_h);

        
        // add logo to front image
        if($logo_url != ''){
            $logo   = imagecreatefrompng($logo_url);
            $logo_w = imagesx($logo);
            $logo_h = imagesy($logo);

            if($logo_center == 'on'){                
                $logo_x = ($new_w / 2) - ($logo_w / 2); 
                $logo_y = ($new_h / 2) - ($logo_h / 2); 
                // imagecopy($img_main, $logo, $logo_x, $logo_y, 0, 0, $logo_w, $logo_h);
            }          
            imagecopy($img_blur, $logo, $logo_x, $logo_y, 0, 0, $logo_w, $logo_h);
            
        }
        
        if($code != ''){
            $black_h   = 50;
            $img_black = imagecreate($width, $black_h);
            imagefilter($img_black, IMG_FILTER_GAUSSIAN_BLUR);
            imagecopy($img_main, $img_black, 0, $height - $black_h, 0, 0, $width, $black_h);
        }
        
        // add text            
        if($text != []){
            foreach ($text as $k => $v) {                
                $color_code = [255, 255, 255];
                addText($img_blur, $font,(int) $v['font_size'], (int) $v['x'], (int) $v['y'], $v['text'], $color_code);
            }            
        }
        
        // add code
        if($code != ''){
            $codeText      = "Code: " . $codeInt;
            $codeTextWidth = imagettfbbox($code['font_size'], 0, $font, $codeText)[2];
            $color_code = [255, 255, 255];
            addText($img_blur, $font, (int) $code['font_size'], $width - $codeTextWidth - (int) $code['x'], (int) $code['y'], $codeText, $color_code);
        }
        
        // output new file
        if($code != ''){ 
            imagejpeg($img_blur, $dst_dir."\\".$codeInt.".".$ext, 100);
            $codeInt++; 
        } else {
            imagejpeg($img_blur, $dst_dir."\\".$image, 100);
        }   
        
        
        imagedestroy($img_blur);
        imagedestroy($img_main);

        if($code != ''){
            imagedestroy($img_black);
        }    
    }



    echo "Image Processed Successfully";


    function addText($image, $font, $font_size=0, $text_x=0, $text_y=0, $text=" ", $cc){
        $color = imagecolorallocate($image, $cc[0], $cc[1], $cc[2]); 
        return imagettftext($image, $font_size, 0, $text_x, $text_y, $color, $font, $text);
    }
    
?>   

