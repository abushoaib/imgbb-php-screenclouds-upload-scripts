<title> Image bb upload scripts using api key </title>

<?php
// set root path of the image bb directory 
$path = "/home/abu/Pictures/screenclouds";

function save_record_image($image, $name = null) {
    $API_KEY = '';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.imgbb.com/1/upload?key=' . $API_KEY);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
    $extension = pathinfo($image, PATHINFO_EXTENSION);
    $file_name = ($name) ? $name . '.' . $extension : "test";
    $data = array('image' => base64_encode(file_get_contents($image)), 'name' => $file_name);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        return 'Error:' . curl_error($ch);
    } else {
        return json_decode($result, true);
    }
    curl_close($ch);
}

// This function will return a random 
// string of specified length 
function random_strings($length_of_string) 
{ 
    // String of all alphanumeric character 
    $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
    // Shufle the $str_result and returns substring 
    // of specified length 
    return substr(str_shuffle($str_result),  
                       0, $length_of_string); 
} 

function is_dir_empty($dir) {
  if (!is_readable($dir)) return NULL; 
  return (count(scandir($dir)) == 2);
}





if(is_dir_empty($path)){
      echo "<center><h1>No file found </h1></center>"; 
}

$addons_dir = @opendir($path);
if ($addons_dir) {
    
   
            
    while (($file = readdir($addons_dir) ) !== false) {
        if (substr($file, 0, 1) == '.') {
            continue;
        }
        $fullImagePath =  $path . "/" . $file ;
        $return = save_record_image($fullImagePath, random_strings(4));
        if(isset($return['data']['url'])){
            @unlink($fullImagePath);
            header("Location:" . $return['data']['url']);
            exit;
        }
       
    }
   
  
    closedir($addons_dir);
}


?>

