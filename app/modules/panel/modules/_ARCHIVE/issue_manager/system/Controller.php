<?php

class Issue_managerController extends Controller
{
    use Validator;

    // Projects list
    public function indexAction()
    {
        Request::setTitle('API');
    }

    // Create task
    public function create_taskAction()
    {
        Request::ajaxPart();

        // Save file on current server and send array of urls
        $postData = $_POST;
        $i = 0;
        foreach ($_FILES as $file) {
            // Make file name
            $format = '.' . File::format($file['name']);
            $name = File::translit(str_replace($format, '', $file['name']));
            $name = randomPassword('7', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890') . '_' . makeSlug($name);
            // Save files
            $fileInfo = File::uploadFile($file, 'data/tracker/', $name);
            if ($fileInfo['url'])
                $postData['files'][] = rtrim(SITE_URL, '/') . $fileInfo['url'];
            $i++;
            if ($i >= 10) break;
        }

        // Request to task manager
        $res = get_contents('http://manager.loc/api/create_task', $_GET, $postData);

        // Replace tracker url to current module
        echo str_replace(
            ['https://donemen.com/api', str_replace('"', '', json_encode('https://donemen.com/api'))],
            ['issue_manager', str_replace('"', '', json_encode('issue_manager'))],
            $res);
        exit;

    }
}
/* End of file */