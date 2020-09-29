<?php

use App\Environment;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Slim\Http\StatusCode;

require __DIR__ . '/../vendor/autoload.php';

{// log settings
    error_reporting(-1);
    ini_set("log_errors", "on");
    ini_set("error_log", "php://stderr");

# display_error
    ini_set("display_errors", "0");
    ini_set("display_startup_errors", "0");
    ini_set('html_errors', "0");

# ログ出力調整
    ini_set("log_errors_max_len", "1048576"); # 1 * 1024 * 1024 = 1MB
}

/*
export MYSQL_HOSTNAME=10.0.0.47
export MYSQL_PORT=3306
export MYSQL_ROOT_PASSWORD=password
export MYSQL_DATABASE=isutrain
export MYSQL_USER=isutrain
export MYSQL_PASSWORD=isutrain
 */

$db = [
    'host' => "10.0.0.229",
    'port' => "3306",
    'username' => "isutrain",
    'password' => "isutrain",
    'dbname' => "isutrain",
];
$dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=UTF8MB4', $db['host'], $db['port'], $db['dbname']);

while(1) {
    echo "connecting".PHP_EOL;
    try {
        $pdo = new PDO($dsn, $db['username'], $db['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => true
        ]);
        break;
    }catch(PDOException $e){
        sleep(1);
    }
}

while (1) {
    sleep(3);
    $stmt = $pdo->prepare("select * from payment_cancel_jobs where is_done=0;");
    $stmt->execute();

    $id_list = [];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $id_list[] = $row['id'];
    }
    var_dump($id_list);
    // bulkで投げる
    { // ==== キャンセルリクエスト
        $payment_api = Environment::get('PAYMENT_API', 'http://10.0.0.36:5000');
        $http_client = new Client();
        try {
            $r = $http_client->post($payment_api . "/payment/_bulk", [
                'json' => ['payment_id' => $id_list],
                'timeout' => 10,
            ]);
        } catch (RequestException $e) {
            error_log("request exception {$e->getMessage()}");
        }
        if ($r->getStatusCode() != StatusCode::HTTP_OK) { // ここで、嘘の決済IDをもってくるやつがいるかというと居ないぽいのでここはイラないしょりっぽい
            error_log("request status code is not OK {$e->getMessage()}");
        }
        error_log($r->getBody());
    }

    // TODO 後で IN句に書き換える
//    $in = str_repeat('?,', count($usableTrainClassList) -1) .  '?';
//    $sql = "SELECT * FROM `train_master` WHERE `date`=? AND `train_class` IN (${in}) AND `is_nobori`=?";
//    $args = array_merge(
//        [$date->format(self::DATE_SQL_FORMAT)],
//        $usableTrainClassList,
//        [$isNobori]
//    );
    $stmt = $pdo->prepare("update payment_cancel_jobs set is_done=1 where id=:id");
    foreach($id_list as $id){
        $stmt->bindValue('id', $id, PDO::PARAM_STR);
        $stmt->execute();
    }
}
