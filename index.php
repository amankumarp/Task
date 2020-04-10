<?php
error_reporting(0);
require_once "./vendor/phpoffice/phpexcel/Classes/PHPExcel.php";
require "./vendor/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel5.php";
if(isset($_POST['btn_upload'])){
   $n= $_FILES['user_file']['name'];
   $name= explode(".",$n);
   if($name[1]=='xlsx'){
   $file=$name[0].time().".".$name[1];
   if(move_uploaded_file($_FILES['user_file']['tmp_name'],"./Upload/".$file)){
    $excelReader = PHPExcel_IOFactory::createReaderForFile("./Upload/".$file);
    $excelObj = $excelReader->load("./Upload/".$file);
    $worksheet = $excelObj->getSheet(0);
    $lastRow = $worksheet->getHighestRow();
    $lastColumn=$worksheet->getHighestColumn();
    
    #modification excel file
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    for ($i='A';$i<=$lastColumn;$i++ ){
        $arr=array();
        for ($j=1;$j<=$lastRow;$j++){
            array_push($arr,$worksheet->getCell($i.$j)->getValue()); 
        }
            $arr=array_unique($arr);
            $u=0;
            for($k=1;$k<=count($arr);$k++){
                $objPHPExcel->getActiveSheet()->setCellValue($i.$k,$arr[$u]);
                $u=1+$u;
            }
         }
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
    $objWriter->save("./Upload/".$file);
    
    #Reopen File for Showing data in table 
    $excelReader = PHPExcel_IOFactory::createReaderForFile("./Upload/".$file);
    $excelObj = $excelReader->load("./Upload/".$file);
    $worksheet = $excelObj->getSheet(0);
    $lastRow = $worksheet->getHighestRow();
    $lastColumn=$worksheet->getHighestColumn();

    $table="<table class='table'><thead class='bg-primary text-light'>";
    for ($i='A';$i<=$lastColumn;$i++)
        $table=$table."<th>".$worksheet->getCell($i.'1')->getValue()."</th>";
    $table=$table."</thead><tbody>";
    for($i=2;$i<=$lastRow;$i++){
        $table=$table."<tr>";
        for($j='A';$j<=$lastColumn;$j++){
            $table=$table."<td>".$worksheet->getCell($j.$i)->getValue()."</td>";
        }
        $table=$table."</tr>";
    }
    $table=$table."</tbody></table>";
 }
 else{
     echo 'File not Upload';
 }
}
else
   echo "File Extension not have xlsx";
} 

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
   <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
<div class='container'>
<div class='col-md-5 offset-md-3 pt-5'>
<form class='form-group form-inline' enctype='multipart/form-data' method='post'>
    <input type="file"  name="user_file" required/>
    <button class='btn btn-primary' type='submit' name='btn_upload'>Upload</button>
    </form>
 </div>
    <?php if(isset($table)){
        echo $table;
    }
    ?>
    </div>
    
    
</body>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</html>