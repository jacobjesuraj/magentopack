<?php

namespace Magento\Traits;

use GuzzleHttp\ClientInterface;
use Magento;
use Magento\MagentoAuth;

trait ResponseOptions
{

    /**
     * Get the response header of the API request
     *
     * @param $token
     * @return array
     */
    public function getResponseHeaders($token)
    {   
       
        return ([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$token
             ]);
    }

    public function getHeader() {
        
        return ([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]);
    }

    public function getHeaderWithNewToken() {
        
        $token = $this->fetchToken();
        
        return ([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$token
            ]);
    }

    public function getHeaderForUpdate($token){

     return  ([
                 'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '.$token

                ]);
    }


    public function getHeaderForUpdateWithNewToken(){

     $token = $this->fetchToken();

     return  ([
                 'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '.$token
                ]);
    }


}