<?php
require('../database/config.php');
require('../function.php');

header('Content-Type:application/json');

function toTimeFormat($val) {
    if (empty($val)) return null;
    $ts = strtotime($val);
    return $ts ? date('H:i:s', $ts) : null;
}

foreach (getallheaders() as $key => $value) 
{
    if ($key == "token" || $key == "Token") 
    {
        $token = $value;
    } 
}

$apiDetails = getapiDetails();

if(isset($token) && $token==$apiDetails['apikey'])
{
    http_response_code(200);

    $input = file_get_contents('php://input');
    $decoded = json_decode($input, true);
    // Support both: raw array OR {token, data: [...]} from api-solutions
    $data = (isset($decoded['data']) && is_array($decoded['data'])) ? $decoded['data'] : $decoded;

    if ($data && is_array($data)) 
    {
        foreach($data as $arr)
        {
            if (!is_array($arr)) continue;
            
            $name = $arr['name'] ?? null;
            $date = $arr['date'] ?? null;
            $result = $arr['result'] ?? '';
            $update_time_raw = $arr['update_time'] ?? date('H:i');
            $update_time = toTimeFormat($update_time_raw) ?: $update_time_raw;
            
            if (!$name || !$date) continue;
            
            $sattaDataExec = $db->prepare('SELECT id FROM `satta_list` WHERE `name`=?');
            $sattaDataExec->execute([$name]);  $sattaData = $sattaDataExec->fetch(PDO::FETCH_ASSOC);
          
            if(isset($sattaData['id']))
            {
                $gameID = $sattaData['id'];

                $existTodayResultExec = $db->prepare('SELECT * FROM `satta_result` WHERE `game_id`=? AND `date`=?');
                $existTodayResultExec->execute([$gameID, $date]);  $existTodayResultData = $existTodayResultExec->fetch(PDO::FETCH_ASSOC);
                
                if($existTodayResultExec->rowCount())
                {
                    $resultUpdateExec = $db->prepare("UPDATE `satta_result` SET `result`=? WHERE `date`=? AND `game_id`=?"); 
                    $resultUpdateExec->execute([$result, $date, $gameID]);
                }else{
                    $resultUpdateExec = $db->prepare("INSERT INTO `satta_result`(`result`, `update_time`, `date`, `game_id`) VALUES (?,?,?,?)"); 
                    $resultUpdateExec->execute([$result, $update_time, $date, $gameID]);
                }
            }
        }

    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    }
}

?>