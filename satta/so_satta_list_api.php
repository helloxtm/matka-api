<?php
require('../database/config.php');
require('../function.php');

header('Content-Type:application/json');

foreach (getallheaders() as $key => $value) {
    if ($key == "token" || $key == "Token") {
        $token = $value;
    } 
}

$apiDetails = getapiDetails();

//API key add
if(isset($token) && $token==$apiDetails['apikey']){
    http_response_code(200);

    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if ($data) {

        foreach($data as $arr){ extract($arr);
            
            $existSattaExec = $db->prepare('SELECT * FROM `satta_list` WHERE `name`=?');
            $existSattaExec->execute([$name]); 
            
            if($existSattaExec->rowCount()){
                
                $UpdateSattaExec = $db->prepare('UPDATE `satta_list` SET `result_time`=? WHERE `name`=?'); 
            }else{
                $UpdateSattaExec = $db->prepare('INSERT INTO `satta_list`(`result_time`, `name`) VALUES (?,?)'); 
            }
            
            $UpdateSattaExec->execute([$result_time, $name]);
        }
        
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    }
}

?>