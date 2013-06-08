<?php
require('./google-api-php-client/src/Google_Client.php');
require('./google-api-php-client/src/contrib/Google_DriveService.php');

//session start
session_start();

$client = new Google_Client();

//clientID
$client->setClientId('59507635988-l3a65l3kn463bf76vnikprdeh1u9hnh3.apps.googleusercontent.com');
//clientsecret
$client->setClientSecret('FXExfhbfx8sEAMJfSAbgxpHf');
$client->setRedirectUri('http://kfes.jp/php/');

$service = new Google_DriveService($client);

//
//
if(isset($_GET['code'])){
    $client->authenticate();
    $_SESSION['token'] = $client->getAccessToken();
    header('Location: http://kfes.jp/php/index.php');
    exit;
}

if(isset($_SESSION['token'])){
    $client->setAccessToken($_SESSION['token']);
}

if($client->getAccessToken()){
    try{
        // 追加したいファイルオブジェクトを作成
        $file = new Google_DriveFile();
        $file->setTitle('テストファイル.txt');
        $file->setDescription('テストファイルですよ！');
        $file->setMimeType('text/plain');

        // 親としたいフォルダの ID
        $parentId = 'root';
        // 親オブジェクト
        $parent = new Google_ParentReference();
        $parent->setId($parentId);

        // ファイルに親をセット
        $file->setParents(array($parent));

        // ファイルデータ
        // 今回はテキストとしてなのでただの文字列
        $data = "テストファイルです。\n改行はどうなんでしょう？";

        // ファイル追加, 追加したファイル情報がオブジェクトとして返ります
        $createdFile = $service->files->insert($file, array(
            'data' => $data,
            'mimeType' => 'text/plain',
        ));
    }catch(Google_Exception $e){
        echo $e->getMessage();
    }
}else{
    $authUrl = $client->createAuthUrl();
    echo '<a href="'.$authUrl.'">認可してください<a>';
}

?>
