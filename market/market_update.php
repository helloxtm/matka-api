<?php
include('../database/config.php');
include('../function.php');
loadEnv('../.env');

$domain = getenv('domain');
$api_key = getenv('api_key');
$domain_key = getenv('domain_key');
$api_url = getenv('api_url');

$data = array(
    'domain' => $domain,
    'api_key' => $api_key,
    'domain_key' => $domain_key,
    'market' => 'all'
);

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "$api_url/apis/market_api.php",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
    ),
));

$response = curl_exec($curl);
curl_close($curl);
$response = json_decode($response);

foreach($response->data as $row){
    $name= $row->name;
    $open_time= date("H:i:s",strtotime($row->open_time));
    $close_time= date("H:i:s",strtotime($row->close_time));

    $stmt = $db->prepare("SELECT COUNT(*) FROM `market_list` WHERE `name` = :name");
    $stmt->execute([':name' => $name]);
    $count = $stmt->fetchColumn();
    if ($count > 0) {
        $stmt = $db->prepare("UPDATE `market_list` SET `open_time` = :open_time, `close_time` = :close_time WHERE `name` = :name");
        $stmt->execute([
            ':open_time' => $open_time,
            ':close_time' => $close_time,
            ':name' => $name
        ]);
    } else {
        $stmt = $db->prepare("INSERT INTO `market_list` (name, `open_time`, `close_time`, status) VALUES (:name, :open_time, :close_time, 'default_status')");
        $stmt->execute([
            ':name' => $name,
            ':open_time' => $open_time,
            ':close_time' => $close_time
        ]);
    }
}
echo "Market Data Updated Successfully";
?>