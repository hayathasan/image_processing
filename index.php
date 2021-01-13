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
    
        <h1>Source & Destination:</h1>
        
        <label for="">Source Folder:</label>
        <input type="text" name="src_dir" value="" style="width: 100%;">
        <br><br>

        <label for="">Dest Folder:</label>
        <input type="text" name="dst_dir" value="" style="width: 100%;">
        <br><br>

        <label for="">Image Height:</label>
        <input type="text" name="image_height" value="600">

        <label for="">Image Width:</label>
        <input type="text" name="image_width" value="600">

        <label for="">Blur Background:</label>
        <input type="checkbox" name="bg_blur">        
        <br><br>

        <h1>Logo:</h1>
        
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


        <h1>Text:</h1>
        <label for="">Add Text:</label>
        <input type="checkbox" name="isAddText"> <br>


        <label for="">Text1:</label>
        <input type="text" name="text[0][text]" value="facebook.com/muniba.mom ~ facebook.com/muniba.mom ~ facebook.com/muniba.mom ~ facebook.com/muniba.mom ~ facebook.com/muniba.mom">
        
        <label for="">Font:</label>
        <input type="text" name="text[0][font_size]" value="8">
        
        <label for="">X-Pos:</label>
        <input type="text" name="text[0][x]" value="0">
        
        <label for="">Y-Pos:</label>
        <input type="text" name="text[0][y]" value="200">
        <br><br>

        <label for="">Text2:</label>
        <input type="text" name="text[1][text]" value="facebook.com/muniba.mom ~ facebook.com/muniba.mom ~ facebook.com/muniba.mom ~ facebook.com/muniba.mom ~ facebook.com/muniba.mom">
        
        <label for="">Font:</label>
        <input type="text" name="text[1][font_size]" value="8">
        
        <label for="">X-Pos:</label>
        <input type="text" name="text[1][x]" value="0">
        
        <label for="">Y-Pos:</label>
        <input type="text" name="text[1][y]" value="400">
        <br><br>





        <h1>Code:</h1>
        <label for="">Add Code:</label>
        <input type="checkbox" name="isAddCode"> 

        <label for="">Code:</label>
        <input type="text" name="code[number_start]" value="1001">

        <label for="">Font:</label>
        <input type="text" name="code[font_size]" value="16">
        
        <label for="">X-Pos:</label>
        <input type="text" name="code[x]" value="20">
        
        <label for="">Y-Pos:</label>
        <input type="text" name="code[y]" value="580">
        <br><br>



        <button type="submit">Submit</button>
        <br><br>

    </form>

</body>
</html>