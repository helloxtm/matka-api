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

if(isset($token) && $token==$apiDetails['apikey']){
    http_response_code(200);

    $input = file_get_contents('php://input');
    $decoded = json_decode($input, true);
    $data = (is_array($decoded) && isset($decoded['data'])) ? $decoded['data'] : $decoded;

    if ($data && is_array($data)) {

        foreach($data as $arr){
            if (!is_array($arr)) continue;
            $name = $arr['name'] ?? $arr['game'] ?? null;
            $open_time = $arr['open_time'] ?? null;
            $close_time = $arr['close_time'] ?? null;
            if (!$name) continue;

            $existMarketExec = $db->prepare('SELECT * FROM `market_list` WHERE `name`=?');
            $existMarketExec->execute([$name]);
            $existMarketData = $existMarketExec->fetch(PDO::FETCH_ASSOC);
            
            if($existMarketExec->rowCount()){
                
                $UpdateMarketExec = $db->prepare('UPDATE `market_list` SET `open_time`=?,`close_time`=? WHERE `name`=?'); 
            }else{
                $UpdateMarketExec = $db->prepare('INSERT INTO `market_list`(`open_time`, `close_time`, `name`) VALUES (?,?,?)'); 
            }
            
            $UpdateMarketExec->execute([$open_time, $close_time, $name]);
        }
        
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    }
}

?>