<?php

class ZohoController extends Controller
{
    use Validator;

    private $code = '1000.7aafe7b7411cc5e9a645fc7708e14618.82fe39a249e2b5beada4cca92c54bfdc'; // generated from panel
    private $redirect_uri = 'https://www.antal.com/page/zoho_redirect';
    private $client_id = '1000.O05IB9AY6KMX765GPBNP1BOLTZUS4X';
    private $client_secret = 'ed034cb67ba1c02df505db4eedf2c5c6c5d9de8df7';

    public function get_refresh_tokenAction()
    {

        $post = [
            'code' => $this->code,
            'redirect_uri' => $this->redirect_uri,
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type' => 'authorization_code',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://accounts.zoho.com/oauth/v2/token');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

        $response = curl_exec($ch);
        $response = json_decode($response);

        // TODO insert refresh token to DB

    }

    public function get_auth_token()
    {

       $refresh_token = ZohoModel::getToken();

        $post = [
            'refresh_token' => $refresh_token,
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type' => 'refresh_token',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://accounts.zoho.com/oauth/v2/token');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

        $response = curl_exec($ch);
        $response = json_decode($response);

        return $response->access_token;

    }


    public function process($data, $filePath, $fileName)
    {

        $token = $this->get_auth_token();

        $curl_pointer = curl_init();

        $curl_options = array();
        $url = "https://www.zohoapis.com/crm/v2/Leads";

        $curl_options[CURLOPT_URL] = $url;
        $curl_options[CURLOPT_RETURNTRANSFER] = true;
        $curl_options[CURLOPT_HEADER] = 1;
        $curl_options[CURLOPT_CUSTOMREQUEST] = "POST";

        $requestBody = array();
        $recordArray = array();
        $recordObject = array();
        $recordObject = $data;


        $recordArray[] = $recordObject;
        $requestBody["data"] = $recordArray;
        $curl_options[CURLOPT_POSTFIELDS] = json_encode($requestBody);

        $headersArray[] = "Authorization" . ":" . "Zoho-oauthtoken " . $token;

        $curl_options[CURLOPT_HTTPHEADER] = $headersArray;

        curl_setopt_array($curl_pointer, $curl_options);

        $result = curl_exec($curl_pointer);
        $responseInfo = curl_getinfo($curl_pointer);
        curl_close($curl_pointer);

        list ($headers, $content) = explode("\r\n\r\n", $result, 2);
        if(strpos($headers," 100 Continue")!==false){
            list( $headers, $content) = explode( "\r\n\r\n", $content , 2);
        }
        $headerArray = (explode("\r\n", $headers, 50));
        $headerMap = array();
        foreach ($headerArray as $key) {
            if (strpos($key, ":") != false) {
                $firstHalf = substr($key, 0, strpos($key, ":"));
                $secondHalf = substr($key, strpos($key, ":") + 1);
                $headerMap[$firstHalf] = trim($secondHalf);
            }
        }
        $jsonResponse = json_decode($content, true);
        if ($jsonResponse == null && $responseInfo['http_code'] != 204) {
            list ($headers, $content) = explode("\r\n\r\n", $content, 2);
            $jsonResponse = json_decode($content, true);
        }

        $lead_id = $jsonResponse['data'][0]['details']['id'];

        $this->add_cv($lead_id, $filePath, $fileName);

    }

    public function add_cv($id, $filePath, $fileName)
    {
        $curl_pointer = curl_init();

        $curl_options = array();
        $curl_options[CURLOPT_URL] = "https://www.zohoapis.com/crm/v2/Leads/" . $id . "/Attachments";
        $curl_options[CURLOPT_RETURNTRANSFER] = true;
        $curl_options[CURLOPT_HEADER] = 1;
        $curl_options[CURLOPT_CUSTOMREQUEST] = "POST";
        $file = fopen($filePath, "rb");
        $fileData = fread($file, filesize($filePath));
        $date = new \DateTime();

        $current_time_long= $date->getTimestamp();

        $lineEnd = "\r\n";

        $hypen = "--";

        $contentDisp = "Content-Disposition: form-data; name=\""."file"."\";filename=\"".$fileName."\"".$lineEnd.$lineEnd;


        $data = utf8_encode($lineEnd);

        $boundaryStart = utf8_encode($hypen.(string)$current_time_long.$lineEnd) ;

        $data = $data.$boundaryStart;

        $data = $data.utf8_encode($contentDisp);

        $data = $data.$fileData.utf8_encode($lineEnd);

        $boundaryend = $hypen.(string)$current_time_long.$hypen.$lineEnd.$lineEnd;

        $data = $data.utf8_encode($boundaryend);

        $curl_options[CURLOPT_POSTFIELDS]= $data;
        $headersArray = array();

        $headersArray = ['ENCTYPE: multipart/form-data','Content-Type:multipart/form-data;boundary='.(string)$current_time_long];
        $headersArray[] = "content-type".":"."multipart/form-data";
        $headersArray[] = "Authorization". ":" . "Zoho-oauthtoken " . $this->get_auth_token();

        $curl_options[CURLOPT_HTTPHEADER]=$headersArray;

        curl_setopt_array($curl_pointer, $curl_options);

        $result = curl_exec($curl_pointer);
        $responseInfo = curl_getinfo($curl_pointer);
        curl_close($curl_pointer);
    }

}
/* End of file */