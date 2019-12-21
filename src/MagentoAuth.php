<?php

namespace Magento;


use Illuminate\Http\Request;
use App\Http\Requests;
use Magento\Traits\ResponseOptions;
use GuzzleHttp\Client;

class MagentoAuth
{
    use ResponseOptions;

	protected $shopURL;

    protected $token;

    protected $adminPath="index.php/rest/V1/";

    protected $responseHeaders;

	public function setShopURL($shopURL)
    {
        $this->shopURL = $shopURL;

        return $this->shopURL;      

    }

    public function setToken($token)
    {
        $this->token = $token;

        return $this->token;      

    }

    public function getToken(){

        return $this->token;
    }

    public function getTokenUrl()
    {
        
        return 'http://' . $this->shopURL . $this->adminPath.'integration/admin/token';

        // return "http://novuslogic.in/vietech/index.php/rest/V1/integration/admin/token";
       
    }

    public function fetchToken($username, $password)
    {
        
        $url = $this->getTokenUrl();
    
        // $username = config('magento.magento_username');
        // $password = config('magento.magento_password');

        try {
        
        $client = new \GuzzleHttp\Client();
        
        $header =   $this->getHeader();
        $res = $client->request('POST',$url,
            array(
            'headers' => $header,
            'json' => [
                "username" => $username,
                "password" => $password
            ])   
        );

        $result= json_decode($res->getBody());

        $this->responseHeaders=$this->getResponseHeaders($result);
        // print_r($this->responseHeaders);
        return $result;
        
        }catch (\GuzzleHttp\Guzzle\Exception\RequestException $e) {
            print_r($e->getMessage());
            
        }
    }

    public function returnResponseHeaders(){

        return $this->responseHeaders;
    }


    public function getUserByToken($token)
    {
        $userUrl = 'http://' . $this->shopURL . $this->adminPath . "customers";

        // $response = $this->getHttpClient()->get( $userUrl,
        //         [
        //             'headers' => $this->getResponseHeaders($token)
        //         ]);

        // $user = json_decode($response->getBody(), true);

        // echo $userUrl;exit;
        $client = new \GuzzleHttp\Client();

        $header =$this->getResponseHeaders($token);
                    
        $res = $client->request('GET',$userUrl,array('headers' => $header));


        // $this->responseHeaders = $response->getHeaders();

        $result= json_decode($res->getBody());

        dd($result);

        // return $user['shop'];
    }



     public function requestPath()
    {
      
        if($this->shopURL != null)
            $this->requestPath = $this->shopURL . $this->adminPath;

        return $this->requestPath;

        // return "http://novuslogic.in/vietech/index.php/rest/V1";

    }

    


}