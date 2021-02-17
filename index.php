<?php
    $template_dir = "template";
    if(!is_dir($template_dir)){
        mkdir($template_dir);
    }

    $templates = array_values(array_diff(scandir($template_dir), [".",".."]));
        
    if(isset($_GET['template']) && $_GET['template'] != ''){
        // $template = json_decode(file_get_contents($template_dir."/".$_GET['template']),true);
        $template = file_get_contents($template_dir."/".$_GET['template']);
        echo $template; 
        exit();        
    }
    
    // foreach ($template as $key => $name) {
    //     ${$key} = $name; 
    // }
    // echo "<pre>"; print_r($template); echo "</pre>";
?>


<!DOCTYPE html>
<html>
<head>
    <title>Image Processing</title>
    <script src="jquery.min.js"></script>
</head>
<body>
    <label for="selectTemplate">Source Template:</label>
    <select name="selectedTemplate" id="selectTemplate">
        <option value=""></option>
        <?php 
            foreach ($templates as $key => $value) {
                echo "<option value='$value'>$value</option>";
            }
        ?>
    </select>

    <script>
        $(document).ready(function(){
            $("#selectTemplate").change(function() {
                var template_name = $("#selectTemplate option:selected").val();
                $.get('index.php?template='+template_name, function(data){
                    data = $.parseJSON(data);
                    console.log(data);
                    $("#templateForm input").each(function(){
                        // console.log($(this).attr("name"));

                        $(this).val( data[$(this).attr("name")] );
                    })


                });
            });

        })
    </script>

    <form id="templateForm" action="image.php" method="POST">
    
        <h1>Source & Destination:</h1>
        
        <label for="">Source Folder:</label>
        <input type="text" name="src_dir" value="" style="width: 100%;">
        <br><br>

        <label for="">Dest Folder:</label>
        <input type="text" name="dst_dir" value="" style="width: 100%;">
        <br><br>

        <label for="">Image Height:</label>
        <input type="text" name="image_height" value="">

        <label for="">Image Width:</label>
        <input type="text" name="image_width" value="">

        <label for="">Blur Background:</label>
        <input type="checkbox" name="bg_blur" value="">        
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
        <input type="checkbox" name="logo_center" value="">

        <br><br>


        <h1>Text:</h1>
        <label for="">Add Text:</label>
        <input type="checkbox" name="isAddText" value=""> <br>


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
        
        <label for="">Text3:</label>
        <input type="text" name="text[2][text]" value="Image Source: Internet :)">
        
        <label for="">Font:</label>
        <input type="text" name="text[2][font_size]" value="8">
        
        <label for="">X-Pos:</label>
        <input type="text" name="text[2][x]" value="20">
        
        <label for="">Y-Pos:</label>
        <input type="text" name="text[2][y]" value="580">
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