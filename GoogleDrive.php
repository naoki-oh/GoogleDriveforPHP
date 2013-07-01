<?php
require('./google-api-php-client/src/Google_Client.php');
require('./google-api-php-client/src/contrib/Google_DriveService.php');
require('./google-api-php-client');

function insertFile($service, $title, $description, $parentId, $mimeType, $fileName){
        $file = new Google_Drive();
        $file->setTitle($title);
        $file->setDescription($description);
        $file->setMimeType($mimeType);

        //setparent Folder
        if($parentId != null){
                $parent = new ParentReference();
                $parent->setId($parentId);
                $file->setParents(array($parent));
        }

        try{
                $data = file_get_contents($fileName);

                $currentedFile = $service->files->insert($file, array(
                        'data' => $data,
                        'mimetype' => $mimeType
                ));
                return $currentedFile;
        }catch(Exception $e){
                print "An error occurred" . $e->getMessage();
        }
}

function retrieveFiles($service){
        $result = array();
        $pageToken = NULL;

        do{
                try{
                        $parameters = array();
                        if($pageToken){
                                $parameters['pageToken'] = $pageToken;
                        }
                        $files = $service->files->listFiles($parameters);
                        $result = array_merge($result, $file->getItems());
                        $pageToken = $files->getNextPageToken();
                }catch(Exception $e){
                        print "An error occurred" . $e->getMessage();
                        $pageToken = NULL;
                }
        }while($pageToken);
        return $result;
}
