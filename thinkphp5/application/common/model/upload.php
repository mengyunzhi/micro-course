<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$ExcelFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
}
?>