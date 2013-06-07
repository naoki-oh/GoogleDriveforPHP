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
                        echo "now";
                        
                }catch(Google_Exception $e){
                        echo $e->getMessage();
                }
        }else{
                $authUrl = $client->createAuthUrl();
                echo '<a href="'.$authUrl.'">アプリケーションのアクセスを許可してください<a>';
        }

?>
