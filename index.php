<!DOCTYPE html>
<html>
<head>
    <title>Image Processing</title>
    <style>
        body {
            padding: 10px;
            padding-right: 20px;
        }
    </style>
</head>
<body>

    <form action="image.php" method="POST">
    
        <label for="">Source Folder:</label>
        <input type="text" name="src_dir" value="" style="width: 100%;">
        <br><br>

        <label for="">Dest Folder:</label>
        <input type="text" name="dst_dir" value="" style="width: 100%;">
        <br><br>

        <label for="">Image Height:</label>
        <input type="text" name="image_height" value="3024">

        <label for="">Image Width:</label>
        <input type="text" name="image_width" value="3024">
        <br><br>

        
        <label for="">Logo:</label>
        <input type="text" name="logo" value="" style="width: 100%;">
        <br><br>

        <label for="">Logo Position (X):</label>
        <input type="text" name="logo_x" value="">

        <label for="">Logo Position (Y):</label>
        <input type="text" name="logo_y" value="">

        <label for="">Logo Position (Center):</label>
        <input type="checkbox" name="logo_center">

        <br><br>



        <label for="">Text1:</label>
        <input type="text" name="text[0][text]" placeholder="fb.com/munirasfantasy">
        
        <label for="">Font:</label>
        <input type="text" name="text[0][font_size]" placeholder="16">
        
        <label for="">X-Pos:</label>
        <input type="text" name="text[0][x]" placeholder="20">
        
        <label for="">Y-Pos:</label>
        <input type="text" name="text[0][y]" placeholder="580">
        <br><br>


        <!-- <label for="">Text2:</label>
        <input type="text" name="text[1][text]" value="Code: 1001">

        <label for="">Font:</label>
        <input type="text" name="text[1][font_size]" value="0">
        
        <label for="">X-Pos:</label>
        <input type="text" name="text[1][x]" value="400">
        
        <label for="">Y-Pos:</label>
        <input type="text" name="text[1][y]" value="580">
        <br><br> -->


        <label for="">Code:</label>
        <input type="text" name="code[text]" placeholder="1001">

        <label for="">Font:</label>
        <input type="text" name="code[font_size]" placeholder="16">
        
        <label for="">X-Pos:</label>
        <input type="text" name="code[x]" placeholder="20">
        
        <label for="">Y-Pos:</label>
        <input type="text" name="code[y]" placeholder="580">
        <br><br>



        <button type="submit">Submit</button>
        <br><br>

    </form>

</body>
</html>