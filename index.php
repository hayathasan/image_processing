<?php
    $template_dir = "template";
    if(!is_dir($template_dir)){
        mkdir($template_dir);
    }

    $templates = array_values(array_diff(scandir($template_dir), [".",".."]));
        
    if(isset($_GET['get_template']) && $_GET['get_template'] != ''){
        $template = file_get_contents($template_dir."/".$_GET['get_template']);
        echo $template; 
        exit();        
    }
    if(isset($_GET['template_data']) && $_GET['template_data'] != ''){
        $template_data = $_GET['template_data'];

        $template_data = json_decode($template_data);
        $template_data = array_filter($template_data);
        $template_data = json_encode($template_data);

        $template_name = $_GET['template_name'];
        $template = file_put_contents($template_dir."/".$template_name,$template_data);
        echo 'true';
        exit();        
    }
    
    // echo "<pre>"; print_r($template); echo "</pre>";
?>


<!DOCTYPE html>
<html>
<head>
    <title>Image Processing</title>
    <script src="jquery.min.js"></script>
    <script src="jquery.serialize-object.min.js"></script>
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
        <input type="checkbox" value="" name="bg_blur">        
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
        <input type="checkbox" value="" name="logo_center" >

        <br><br>


        <h1>Text:</h1>
        <label>Add Text:</label>
        <input type="checkbox" value="" name="isAddText"> <br>

        <div class="text_divs">
            <!-- <div class="text_div" style="margin-bottom: 10px;">
                <label>Text:</label>
                <input type="text" name="text[0][text]">
                
                <label>Font:</label>
                <input type="text" name="text[0][font_size]">
                
                <label>X-Pos:</label>
                <input type="text" name="text[0][x]">
                
                <label>Y-Pos:</label>
                <input type="text" name="text[0][y]">        
            </div> -->
        </div>
        <!-- <br><br> -->

        <!-- <label for="">Text2:</label>
        <input type="text" name="text[1][text]" value="facebook.com/muniba.mom ~ facebook.com/muniba.mom ~ facebook.com/muniba.mom ~ facebook.com/muniba.mom ~ facebook.com/muniba.mom">
        
        <label for="">Font:</label>
        <input type="text" name="text[1][font_size]" value="8">
        
        <label for="">X-Pos:</label>
        <input type="text" name="text[1][x]" value="0">
        
        <label for="">Y-Pos:</label>
        <input type="text" name="text[1][y]" value="400"> -->
        <!-- <br><br> -->
        
        <!-- <label for="">Text3:</label>
        <input type="text" name="text[2][text]" value="Image Source: Internet :)">
        
        <label for="">Font:</label>
        <input type="text" name="text[2][font_size]" value="8">
        
        <label for="">X-Pos:</label>
        <input type="text" name="text[2][x]" value="20">
        
        <label for="">Y-Pos:</label>
        <input type="text" name="text[2][y]" value="580">
        <br><br> -->





        <h1>Code:</h1>
        <label for="">Add Code:</label>
        <input type="checkbox" value="" name="isAddCode"> 

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

    <button id="updateTemplate">Update Template</button>

    <script>
        $(document).ready(function(){
            $("#selectTemplate").change(function() {
                $('.text_divs').html('');
                var template_name = $("#selectTemplate option:selected").val();                
                $.get('index.php?get_template='+template_name, function(data){
                    data = $.parseJSON(data);
                    $("#templateForm input").each(function(){
                        $(this).val( data[$(this).attr("name")] );                        
                    });
                    if(data.text.length > 0){
                        for(var i=0; i<data.text.length; i++){
                            addTextDiv(i); 
                        }
                    }
                    $.each(data.text, function(k,v){
                        $('#templateForm input[name="text['+k+'][text]'+'"]').val(v.text);
                        $('#templateForm input[name="text['+k+'][font_size]'+'"]').val(v.font_size);
                        $('#templateForm input[name="text['+k+'][x]'+'"]').val(v.x);
                        $('#templateForm input[name="text['+k+'][y]'+'"]').val(v.y);
                    });
                    $('#templateForm input[type="checkbox"]').each(function(){
                        $(this).prop('checked',data[$(this).attr("name")]);
                    });
                });
            });

            $("#updateTemplate").click(function(){
                var template_name = prompt("Please enter template name (.json) !", $("#selectTemplate").val()); 
                
                if (template_name == null || template_name == "") {
                    alert('Please enter template name (.json) !');
                } else {
                    var templateData = $.parseJSON($("#templateForm").serializeJSON());                                    
                    
                    $('#templateForm input[type="checkbox"]').each(function(){
                        templateData[$(this).attr('name')] = $(this)[0].checked; 
                    });

                    // $.each(templateData.text, function(k,v){
                    // templateData.text = templateData.text.reverse();
                    // for(var i=0; i<(templateData.text.length+1); i++){                        
                    //     if(templateData.text[i].text == ''){
                    //         templateData.text.splice(i, 1);
                    //     }
                    // };
                    
                    templateData = encodeURIComponent(JSON.stringify(templateData));

                    $.get('index.php?template_name='+template_name+'&template_data='+templateData, function(data){
                        console.log("Template updated")
                    });
                }
            });

            $("input[name='isAddText']").on('change',function(){
                if( $(this)[0].checked ){
                    var textId = $('.text_divs .text_div').length;
                    addTextDiv(textId);                   
                }
            })

            function addTextDiv(textId){
                var text_div = '' +
                    '<div class="text_div" style="margin-bottom: 10px;">'+
                        '<label>Text:</label>'+
                        '<input type="text" name="text['+textId+'][text]">'+
                        
                        '<label>Font:</label>'+
                        '<input type="text" name="text['+textId+'][font_size]">'+
                        
                        '<label>X-Pos:</label>'+
                        '<input type="text" name="text['+textId+'][x]">'+
                        
                        '<label>Y-Pos:</label>'+
                        '<input type="text" name="text['+textId+'][y]">'+
                    '</div>';

                $('.text_divs').append(text_div);
            }
        })
    </script>

</body>
</html>