<?php
require('../database/config.php');
require('../function.php');

header('Content-Type:application/json');

function toTimeFormat($val) {
    if (empty($val)) return null;
    $ts = strtotime($val);
    return $ts ? date('H:i:s', $ts) : null;
}

foreach (getallheaders() as $key => $value) {
    if ($key == "token" || $key == "Token") {
        $token = $value;
    } 
}

$apiDetails = getapiDetails();
$internalToken = getInternalToken();

if(isset($token) && ($token==$apiDetails['apikey'] || $token==$internalToken)){
    http_response_code(200);

    $input = file_get_contents('php://input');
    $decoded = json_decode($input, true);
    // Support both: raw array OR {token, data: [...]} from api-solutions send_result
    $data = (isset($decoded['data']) && is_array($decoded['data'])) ? $decoded['data'] : $decoded;

    if ($data && is_array($data)) {
        
        foreach($data as $arr){
            if (!is_array($arr)) continue;
            
            $market_name = $arr['market_name'] ?? null;
            $result_date = $arr['result_date'] ?? date('Y-m-d');
            $open = $arr['open'] ?? '';
            $open_sd = $arr['open_sd'] ?? '';
            $close = $arr['close'] ?? '';
            $close_sd = $arr['close_sd'] ?? '';
            // api-solutions sends open_update_time/close_update_time, accept both
            $openTime = $arr['open_result_time'] ?? $arr['open_update_time'] ?? date('H:i');
            $closeTime = $arr['close_result_time'] ?? $arr['close_update_time'] ?? date('H:i');
            $open_result_time = toTimeFormat($openTime) ?: $openTime;
            $close_result_time = toTimeFormat($closeTime) ?: $closeTime;
            
            if (!$market_name) continue;
            
            $jodi = $open_sd . $close_sd;

            $marketDataExec = $db->prepare('SELECT id FROM `market_list` WHERE `name`=?');
            $marketDataExec->execute([$market_name]);
            $marketData = $marketDataExec->fetch(PDO::FETCH_ASSOC);
            
            $gameID = $marketData['id'] ?? null;

            if (!$gameID) {
                $ins = $db->prepare('INSERT INTO `market_list` (`name`, `open_time`, `close_time`) VALUES (?, NULL, NULL)');
                $ins->execute([$market_name]);
                $gameID = (int) $db->lastInsertId();
            }
            
            if($gameID){
                
                $existTodayResultExec = $db->prepare('SELECT id FROM `market_result` WHERE `game_id`=? AND `date`=?');
                $existTodayResultExec->execute([$gameID, $result_date]);
                
                if($existTodayResultExec->rowCount()){
                    $resultUpdateExec = $db->prepare("UPDATE `market_result` SET `open_patti`=?, `open_sd`=?, `open_update_time`=?, `close_patti`=?, `close_sd`=?, `close_update_time`=?, `jodi`=? WHERE `date`=? AND `game_id`=?"); 
                }else{
                    $resultUpdateExec = $db->prepare("INSERT INTO `market_result`(`open_patti`, `open_sd`, `open_update_time`, `close_patti`, `close_sd`, `close_update_time`, `jodi`, `date`, `game_id`) VALUES (?,?,?,?,?,?,?,?,?)"); 
                }
                
                $resultUpdateExec->execute([$open, $open_sd, $open_result_time, $close, $close_sd, $close_result_time, $jodi, $result_date, $gameID]);
            }
        }
        
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    }
}

?>