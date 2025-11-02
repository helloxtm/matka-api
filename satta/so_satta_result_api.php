<?php
require('../database/config.php');
require('../function.php');

header('Content-Type:application/json');

foreach (getallheaders() as $key => $value) 
{
    if ($key == "token" || $key == "Token") 
    {
        $token = $value;
    } 
}

$apiDetails = getapiDetails();

//API key add
if(isset($token) && $token==$apiDetails['apikey'])
{
    http_response_code(200);

    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if ($data) 
    {
        foreach($data as $arr)
        {
            extract($arr);
            
            $sattaDataExec = $db->prepare('SELECT * FROM `satta_list` WHERE `name`=?');
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