<!DOCTYPE html>
<html>
<head>
	<title>Image Processing</title>
</head>
<body>

    <form action="" method="POST">
    
        <label for="">Source Folder:</label>
        <input type="text" name="src_dir">

        <br>
        <br>

        <label for="">Dest Folder:</label>
        <input type="text" name="dst_dir">

        <br>
        <br>

        <label for="">Watermark:</label>
        <input type="text" name="watermark" value="D:\GoogleDrive\muniba & mom\Logo & Banner\logo_142x66.png">

        <br>
        <br>

        <button type="submit">Submit</button>

        <br>
        <br>

    </form>

</body>
</html>

<?php

    // echo "<pre>";
    // print_r($_POST);
    // echo "<pre>";
    // echo "Script Disabled, Please comment out exit() function."; exit();
	
    set_time_limit(1200);
    $src_dir = "";
    $dst_dir = "";

    if(isset($_POST['src_dir']) && $_POST['src_dir'] != '' && 
        isset($_POST['dst_dir']) && $_POST['dst_dir'] != '' &&
        isset($_POST['watermark']) && $_POST['watermark'] != '')
    {
        $src_dir = $_POST['src_dir'] . '\\';
        $dst_dir = $_POST['dst_dir']. '\\';
        $watermark = $_POST['watermark'];
        $image_processor = new image_processor($src_dir, $dst_dir, $watermark);
    } else {
        echo "Source or Destination is empty"; 
    }

    class image_processor {
        public $font = "C:\\Windows\\Fonts\\segoeui.ttf";
        public $watermark = "";
        public $src_dir = "";
        public $dst_dir = "";        
        public $src_file_exception = [".", "..","desktop.ini","image.php","new"];
        
        // public $text = "This image is collected from internet!";
        // public $text = "facebook.com/munirasfantasy";
        public $text = "facebook.com/muniba.mom";
        
        public $height = 600;
        public $width = 600;  
        public $src_files = []; 

        // function __construct() {
        function __construct($src_dir = "", $dst_dir = "", $watermark = "") {

            $this->src_dir = $src_dir;
            $this->dst_dir = $dst_dir;
            $this->watermark = $watermark;
            $this->src_files = array_values(array_diff(scandir($this->src_dir . "/"), $this->src_file_exception));
            
            foreach ($this->src_files as $file) {
                $this->image_blurred_bg($file);    
            }

            echo "Image Processed Successfully";

        }
    
        function image_blurred_bg($image){
            $src_img = $this->src_dir.$image;
            try {
                $info = getimagesize($src_img);
            } catch (Exception $e){
                return false;
            }

            $mimetype = image_type_to_mime_type($info[2]);
            switch ($mimetype) {
                case 'image/jpeg': $src_img = imagecreatefromjpeg($src_img); break;
                case 'image/gif': $src_img = imagecreatefromgif($src_img); break;
                case 'image/png': $src_img = imagecreatefrompng($src_img); break;
                default: return false;
            }

            $wor = imagesx($src_img);
            $hor = imagesy($src_img);
            $back = imagecreatetruecolor($this->width, $this->height);

            $maxfact = max($this->width/$wor, $this->height/$hor);
            $new_w = $wor*$maxfact;
            $new_h = $hor*$maxfact;
            imagecopyresampled($back, $src_img, -(($new_w - $this->width)/2), -(($new_h - $this->height)/2), 0, 0, $new_w, $new_h, $wor, $hor);

            // blur back image
            for ($x=1; $x <=40; $x++){
                imagefilter($back, IMG_FILTER_GAUSSIAN_BLUR, 999);
            }
            imagefilter($back, IMG_FILTER_SMOOTH,99);
            imagefilter($back, IMG_FILTER_BRIGHTNESS, 10);

            $minfact = min($this->width/$wor, $this->height/$hor);
            $new_w = $wor*$minfact;
            $new_h = $hor*$minfact;

            $front = imagecreatetruecolor($new_w, $new_h);
            imagecopyresampled($front, $src_img, 0, 0, 0, 0, $new_w, $new_h, $wor, $hor);


            // add watermark to front image
            $watermark = imagecreatefrompng($this->watermark);
            $wtrmrk_w = imagesx($watermark);
            $wtrmrk_h = imagesy($watermark);
            // $center_x = ($new_w / 2) - ($wtrmrk_w / 2); 
            // $center_y = ($new_h / 2) - ($wtrmrk_h / 2); 
            $center_x = 20; 
            $center_y = 20; 
            imagecopy($front, $watermark, $center_x, $center_y, 0, 0, $wtrmrk_w, $wtrmrk_h);

            // merge front & back image
            imagecopymerge($back, $front,-(($new_w - $this->width)/2), -(($new_h - $this->height)/2), 0, 0, $new_w, $new_h, 100);
            
            
            
            
            
            // add text        
            $font_size = 12;
            // $bbox = imagettfbbox($font_size, 0, $this->font, $this->text);            
            // $text_x = (imagesx($back) / 2) - (($bbox[2] - $bbox[0]) / 2); 
                   
            $text_x = 10;        
            $text_y = imagesy($back) - 10;

            $color = imagecolorallocate($front, 255, 255, 255);
            $this->text = "$text_x x $text_y";
            imagettftext($back, $font_size, 0, $text_x, $text_y, $color, $this->font, $this->text);





            // output new file
            imagejpeg($back, $this->dst_dir.$image, 100);
            imagedestroy($back);
            imagedestroy($front);

            return true;
        }
    }
    
?>   

