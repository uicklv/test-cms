<?php

class Bullhorn_API
{
    private $client_id;
    private $client_secret;
    private $username;
    private $password;
    private $redirect_uri;
    private $http_code;

    public function __construct($params)
    {
        $this->client_id = $params['client_id'];
        $this->client_secret = $params['client_secret'];
        $this->redirect_uri = $params['redirect_uri'];
        $this->username = $params['username'];
        $this->password = $params['password'];
    }

    public function get_oauth_link()
    {
        $params = array(
            "client_id" => $this->client_id,
            "response_type" => "code",
            "redirect_uri" => $this->redirect_uri,
            "state" => substr(md5($this->redirect_uri), 0, 10)
        );

        return "https://auth.bullhornstaffing.com/oauth/authorize?" . http_build_query($params);
    }

    public function auto_auth()
    {
        $params = array(
            "client_id"     => $this->client_id,
            "response_type" => "code",
            "action"        => "Login",
            "redirect_uri"  => $this->redirect_uri,
            "username"      => $this->username,
            "password"      => $this->password,
            "state"         => substr(md5($this->redirect_uri), 0, 10)
        );

        $url = "https://auth.bullhornstaffing.com/oauth/authorize?" . http_build_query($params);
        $response = file_get_contents($url); // redirected to -> sync_candidates
        Model::insert('bh_logs', ['message' => 'Try login:: ' . json_encode($response), 'time' => time()]);

//        if ($response->access_token && $response->refresh_token) {
//            Model::insert('bh_logs', ['message' => 'Try login - success', 'time' => time()]);
//
//            $data = array(
//                'access_token'  => $response->access_token,
//                'refresh_token' => $response->refresh_token,
//                'expires'       => $response->expires,
//                'note'          => 'auto_auth',
//                'created'       => $response->created
//            );
//
//            $result   = Model::insert('bullhorn_integration', $data); // Insert row
//            $insertID = Model::insertID();
//        } else {
//            return false;
//        }
        return true;
    }

    public function get_access_token($code)
    {
        $params = array(
            "grant_type"    => "authorization_code",
            "code"          => $code,
            "client_id"     => $this->client_id,
            "client_secret" => $this->client_secret,
            "redirect_uri"  => $this->redirect_uri
        );

        return $this->get_json("https://auth.bullhornstaffing.com/oauth/token?" . http_build_query($params), $params, array(), "POST");
    }

    public function get_refresh_token($refresh_token)
    {
        $params = array(
            "grant_type"    => "refresh_token",
            "refresh_token" => $refresh_token,
            "client_id"     => $this->client_id,
            "client_secret" => $this->client_secret
        );

        return $this->get_json("https://auth.bullhornstaffing.com/oauth/token?" . http_build_query($params), $params, array(), "POST");
    }

    public function get_login($access_token, $version = "2.0")
    {
        $params = array(
            "access_token" => $access_token,
            "version" => $version
        );

        return $this->get_json("https://rest.bullhornstaffing.com/rest-services/login?" . http_build_query($params));
    }

    public function get_json($url, $post = NULL, $headers = array(), $method = NULL)
    {
        return json_decode($this->get_content($url, $post, $headers, $method));
    }

    private function get_content($url, $post = NULL, $headers = array(), $method = NULL)
    {
        $handler = curl_init();
        curl_setopt($handler, CURLOPT_URL, $url);
        curl_setopt($handler, CURLOPT_HEADER, FALSE);
        curl_setopt($handler, CURLOPT_HTTPHEADER, $headers);
        if ($post || $method !== NULL) {
            if ($method !== NULL) {
                curl_setopt($handler, CURLOPT_CUSTOMREQUEST, $method);
                curl_setopt($handler, CURLOPT_POST, FALSE);
            } else {
                curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($handler, CURLOPT_POST, TRUE);
            }
            curl_setopt($handler, CURLOPT_POSTFIELDS, $post);
        }
        curl_setopt($handler, CURLINFO_HEADER_OUT, FALSE);
        curl_setopt($handler, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($handler, CURLOPT_MAXREDIRS, 10);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($handler, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($handler, CURLOPT_TIMEOUT, 30);
        curl_setopt($handler, CURLOPT_USERAGENT, "PHP/" . phpversion());
        $result = curl_exec($handler);
        $this->http_code = curl_getinfo($handler, CURLINFO_HTTP_CODE);
        curl_close($handler);

        return $result;
    }

    public function get_last_http_code()
    {
        return $this->http_code;
    }
}
/* End of file */