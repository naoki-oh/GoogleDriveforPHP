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
        // �ǉ��������t�@�C���I�u�W�F�N�g���쐬
        $file = new Google_DriveFile();
        $file->setTitle('�e�X�g�t�@�C��.txt');
        $file->setDescription('�e�X�g�t�@�C���ł���I');
        $file->setMimeType('text/plain');

        // �e�Ƃ������t�H���_�� ID
        $parentId = 'root';
        // �e�I�u�W�F�N�g
        $parent = new Google_ParentReference();
        $parent->setId($parentId);

        // �t�@�C���ɐe���Z�b�g
        $file->setParents(array($parent));

        // �t�@�C���f�[�^
        // ����̓e�L�X�g�Ƃ��ĂȂ̂ł����̕�����
        $data = "�e�X�g�t�@�C���ł��B\n���s�͂ǂ��Ȃ�ł��傤�H";

        // �t�@�C���ǉ�, �ǉ������t�@�C����񂪃I�u�W�F�N�g�Ƃ��ĕԂ�܂�
        $createdFile = $service->files->insert($file, array(
            'data' => $data,
            'mimeType' => 'text/plain',
        ));
    }catch(Google_Exception $e){
        echo $e->getMessage();
    }
}else{
    $authUrl = $client->createAuthUrl();
    echo '<a href="'.$authUrl.'">�F���Ă�������<a>';
}

?>
