<?php 
namespace Magento;

use Illuminate\Http\Request;
use App\Http\Requests;

/**
 * 
 */
class MagentoFetch 
{	

	/* 
    	Generates a new token by getting admin credetials.
    */
	public function FetchToken($data)
	{
		//echo "da";exit;
		$jsonString = file_get_contents(base_path('resources/lang/'.$data));
		$data = json_decode($jsonString);
		$url = $data->url."/index.php/rest/".$data->version."/integration/admin/token";
		$username = config('magento.magento_username');
        $password = config('magento.magento_password');
		   
		try {
		
		$client = new \GuzzleHttp\Client();
		$header = $this->GetHeader(); 

        $res = $client->request('POST',$url,
        	array(
        	'headers' => $header,
        	'json' => [
                "username" => $username,
				"password" => $password
            ])   
        );

        $result= $res->getBody();
        return $result;
        
        }catch (\GuzzleHttp\Guzzle\Exception\RequestException $e) {
    		var_dump($e);
    		exit();
		}
	}



	/* 
    	Fetches a single order details by using admin token and order id.
    */
    public function FetchSingleOrder($data){

        $getTokenData=$data;
        $jsonString = file_get_contents(base_path('resources/lang/'.$data));
    	$data = json_decode($jsonString);
   
    	$username = config('magento.magento_username');
        $password = config('magento.magento_password');

        $url = $data->url."/index.php/rest/".$data->version."/orders/".$data->order_id;
        $token = $data->token;

           try{
	            try {
			
					$client = new \GuzzleHttp\Client();
					$header =$this->GetHeaderWithExistingToken($token);
			        $res = $client->request('GET',$url,array('headers' => $header));

			        $result= json_decode($res->getBody());
			        dd($result);
		        
		        
		        }catch (\GuzzleHttp\Exception\ClientException $e) {
		    		
					//$token = $this->FetchToken($jsonfile);
					$header =$this->GetHeaderWithToken($getTokenData);
			        $res = $client->request('GET',$url,array('headers' => $header));

			        $result= json_decode($res->getBody());
			        dd($result);
				}
			}catch (\GuzzleHttp\Exception\ClientException $e) {
				echo "Unable to fetch order" ;
			}

    }



    /* 
    	Fetches details of multiple orders by using admin token and dates.
    */
	public function FetchOrdersByDate($data){

        $getTokenData=$data;
        $jsonString = file_get_contents(base_path('resources/lang/'.$data));
        $data = json_decode($jsonString);
        
        $username = config('magento.magento_username');
        $password = config('magento.magento_password');

        $token=$data->token;

        $url = $data->url."/index.php/rest/".$data->version."/orders/?searchCriteria[filter_groups][0][filters][0][field]=created_at&searchCriteria[filter_groups][0][filters][0][value]=".$data->from."%2000:00:01&searchCriteria[filter_groups][0][filters][0][condition_type]=from&searchCriteria[filter_groups][1][filters][1][field]=created_at&searchCriteria[filter_groups][1][filters][1][value]=".$data->to."%2023:59:59&searchCriteria[filter_groups][1][filters][1][condition_type]=to";
            
        try {
	        try {
			
				$client = new \GuzzleHttp\Client();
				$header =$this->GetHeaderWithExistingToken($token);
		        $res = $client->request('GET',$url,array('headers' => $header));

		        $result= json_decode($res->getBody());
		        dd($result);
		        
		    }catch (\GuzzleHttp\Exception\ClientException $e) {
	    		
	    		//$token = $this->GetToken($jsonfile);
	    		$header =$this->GetHeaderWithToken($getTokenData);
	    		$client = new \GuzzleHttp\Client();
				
		        $res = $client->request('GET',$url,array('headers' => $header));

		        $result= json_decode($res->getBody());
		        dd($result);

			}
		}catch (\GuzzleHttp\Exception\ClientException $e) {
			echo "Unable to fetch orders. "
		}



    }



    /*
    	Updates a single order details by using admin token and order id.
    */
    public function UpdateSingleOrder($data){

        $getTokenData=$data;
        $jsonString = file_get_contents(base_path('resources/lang/'.$data));
        $data = json_decode($jsonString);
        
        $token=$data->token;

        $url = $data->url."/index.php/rest/".$data->version."/orders/".$data->order_id."/comments";
            
        try{
	        try {
				
				$client = new \GuzzleHttp\Client();
				$header =$this->GetHeaderWithExistingToken($token);
		        $res = $client->request('POST',$url,
		        	array(
		        	'headers' => $header,
		            'json' => [
		                	'id' => $data->order_id, //order_id
	                		'statusHistory' => array(
	                    	'comment' => $data->comment,
	                    	'entity_id' => null,
	                    	'is_customer_notified' => '1',
	                    	'created_at' => now(),
	                   	    'parent_id' => $data->order_id, //order_id
	                    	'entity_name' => 'order',
	                    	'status' => $data->status, //assign new status to order
	                    	'is_visible_on_front' => '1'
	            		)]
		        ));

		        $result= json_decode($res->getBody());
		        dd($result);
		        
		    }catch (\GuzzleHttp\Exception\ClientException $e) {
		    	
		    	$client = new \GuzzleHttp\Client();
				
				$header =$this->GetHeaderWithToken($getTokenData);
		        $res = $client->request('POST',$url,
		        	array(
		        	'headers' => $header,
		            'json' => [
		                	'id' => $data->order_id, //order_id
	                		'statusHistory' => array(
	                    	'comment' => $data->comment,
	                    	'entity_id' => null,
	                    	'is_customer_notified' => '1',
	                    	'created_at' => now(),
	                   	    'parent_id' => $data->order_id, //order_id
	                    	'entity_name' => 'order',
	                    	'status' => $data->status, //assign new status to order
	                    	'is_visible_on_front' => '1'
	            		)]
		        ));

		        $result= json_decode($res->getBody());
		        dd($result);
			}
		}catch (\GuzzleHttp\Exception\ClientException $e) {
				echo "Unable to update order";
		}
        

    }




    /* 
    	Updates details of multiple orders by using order ids.
    */
    public function UpdateOrdersById($data){

        $getTokenData=$data;
        $jsonString = file_get_contents(base_path('resources/lang/'.$data));
        $data = json_decode($jsonString);
          
		$client = new \GuzzleHttp\Client();
		
		$token = $data->token;

		for($i=1;$i<=$data->no_of_orders;$i++) {
			try {

				$orderid="order_id".$i;
				$comment="comment".$i;
				$status="status".$i;
				$url = $data->url."/index.php/rest/".$data->version."/orders/".$data->$orderid."/comments";

				try{
					
					$header =$this->GetHeaderWithExistingToken($token);
			        $res = $client->request('POST',$url,
			        	array(
			        	'headers' => $header,
			            'json' => [
			                	'id' => $data->$orderid, //order_id
		                		'statusHistory' => array(
		                    	'comment' => $data->$comment,
		                    	'entity_id' => null,
		                    	'is_customer_notified' => '1',
		                    	'created_at' => now(),
		                   	    'parent_id' => $data->$orderid, //order_id
		                    	'entity_name' => 'order',
		                    	'status' => $data->$status, //assign new status to order
		                    	'is_visible_on_front' => '1'
		            		)]
			        ));

		    	}catch (\GuzzleHttp\Exception\ClientException $e) {
		    		
		    		$header =$this->GetHeaderWithToken($getTokenData);
		    		$res = $client->request('POST',$url,
			        	array(
			        	'headers' => $header,
			            'json' => [
			                	'id' => $data->$orderid, //order_id
		                		'statusHistory' => array(
		                    	'comment' => $data->$comment,
		                    	'entity_id' => null,
		                    	'is_customer_notified' => '1',
		                    	'created_at' => now(),
		                   	    'parent_id' => $data->$orderid, //order_id
		                    	'entity_name' => 'order',
		                    	'status' => $data->$status, //assign new status to order
		                    	'is_visible_on_front' => '1'
		            		)]
			        ));
				}
		        
	    	
	    	//echo "Orders Updated Successfully";
	    	// $result= json_decode($res->getBody());
		    // dd($result);
	        
		    }catch (\GuzzleHttp\Exception\ClientException $e) {
		    		
		    	echo "Exception at Order no. ".$data->$orderid."<br>";
			}

		}


    }


    /* 
    	Updates details of multiple orders by using admin token and dates.
    */
    public function UpdateOrdersByDate($data){

    	$getTokenData=$data;
        $jsonString = file_get_contents(base_path('resources/lang/'.$data));
        $data = json_decode($jsonString);
       	//var_dump($data);exit;
        $token=$data->token;

        $url = $data->url."/index.php/rest/".$data->version."/orders/?searchCriteria[filter_groups][0][filters][0][field]=created_at&searchCriteria[filter_groups][0][filters][0][value]=".$data->from."%2000:00:01&searchCriteria[filter_groups][0][filters][0][condition_type]=from&searchCriteria[filter_groups][1][filters][1][field]=created_at&searchCriteria[filter_groups][1][filters][1][value]=".$data->to."%2023:59:59&searchCriteria[filter_groups][1][filters][1][condition_type]=to";
        
        $token = $this->GetToken($jsonfile);
        try {
			
			try{
				$client = new \GuzzleHttp\Client();
				$header =$this->GetHeaderWithExistingToken($token);
		        $res = $client->request('GET',$url,array('headers' => $header) );

		        $result= json_decode($res->getBody());
	        	//dd($result);exit;
		    }catch (\GuzzleHttp\Exception\ClientException $e) {
	    		$client = new \GuzzleHttp\Client();
				$header =$this->GetHeaderWithToken($getTokenData);
		        $res = $client->request('GET',$url,array('headers' => $header));

		        $result= json_decode($res->getBody());
			}

	        foreach ($result->items as $order) {
	        	
				$client = new \GuzzleHttp\Client();
				$url = $data->url."/index.php/rest/".$data->version."/orders/".$order->entity_id."/comments";
				$token = $this->GetToken($jsonfile);
		        $res = $client->request('POST',$url,
		        	array(
		        	'headers' => [
		                'Content-Type' => 'application/json',
		        		'Authorization' => 'Bearer '.$token
		            ],
		            'json' => [
		                	'id' => $order->entity_id, //order_id
	                		'statusHistory' => array(
	                    	'comment' => $data->comment,
	                    	'entity_id' => null,
	                    	'is_customer_notified' => '1',
	                    	'created_at' => now(),
	                   	    'parent_id' => $order->entity_id, //order_id
	                    	'entity_name' => 'order',
	                    	'status' => $data->status, //assign new status to order
	                    	'is_visible_on_front' => '1'
	            		)]
		        	// )
		        ));

		        //$result= json_decode($res->getBody());
	        	//dd($result);


	    	}
	        echo "Orders Updated Suceessfully.";
	        
	    }catch (\GuzzleHttp\Exception\ClientException $e) {
	    		echo"Unable to update the orders.";
	    		//var_dump($e);
	    		exit();
		}

    }


    /* 
    	Creates a new order.
    */
    public function CreateOrder($data){
    	
        $getTokenData=$data;
        $jsonString = file_get_contents(base_path('resources/lang/'.$data));
        $data = json_decode($jsonString);
        
        try {

        $client = new \GuzzleHttp\Client();
        $url = $data->url."/index.php/rest/".$data->version."/integration/customer/token";
        $header=$this->GetHeader();
        $res = $client->request('POST',$url,
        	array(
        	'headers' => $header,
        	'json' => [
                "username" => $data->username,
				"password" => $data->password
            ])   
        );

        $CustomerToken= json_decode($res->getBody());
        
        $headerWithCustomerToken=([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$CustomerToken
            ]);

        $client = new \GuzzleHttp\Client();
        $url = $data->url."/index.php/rest/".$data->version."/carts/mine";

        $res = $client->request('POST',$url,
        	array(
        	'headers' => $headerWithCustomerToken)   
        );

        $quoteId= json_decode($res->getBody());
        //echo $quoteId;exit;

        $client = new \GuzzleHttp\Client();
        $url = $data->url."/index.php/rest//".$data->version."/carts/mine/items";

        $res = $client->request('POST',$url,
        	array(
        	'headers' => $headerWithCustomerToken,
        	'json' => [
                'cart_item' => [
                    'quote_id' => $quoteId,
                    'sku' => $data->sku,
                    'qty' => $data->qty
                ]
            ])    
        );



        $client = new \GuzzleHttp\Client();
        $url = $data->url."/index.php/rest//".$data->version."/carts/mine/estimate-shipping-methods";

        $res = $client->request('POST',$url,
        	array(
        	'headers' => $headerWithCustomerToken,
        	'json' => [
                "address" => $data->address
            ])    
        );
        
        $client = new \GuzzleHttp\Client();
        $url = $data->url."/index.php/rest//".$data->version."/carts/mine/shipping-information";

        $res = $client->request('POST',$url,
        	array(
        	'headers' => $headerWithCustomerToken,
        	'json' => [
        			"addressInformation" => $data->addressInfo
            ])    
        );


        $client = new \GuzzleHttp\Client();
        $url = $data->url."/index.php/rest//".$data->version."/carts/mine/payment-information";

        $res = $client->request('POST',$url,
        	array(
        	'headers' => $headerWithCustomerToken,
        	'json' => [
        			"paymentMethod" => $data->paymentMethod,
        			"billing_address" => $data->billing_address
            ])    
        );

        echo "Order Created Suceessfully.";

    	}catch (\GuzzleHttp\Exception\ClientException $e) {
	    		echo "Unable to create the order.";
	    		//var_dump($e);
	    		exit();
		}

    }



    /* 
    	Create a new product.
    */
    public function CreateProduct($data){

    		$getTokenData=$data;
	        $jsonString = file_get_contents(base_path('resources/lang/'.$data));
	        $data = json_decode($jsonString);
    		
    		$url = $data->url."/index.php/rest/".$data->version."/products";
    		$token=$data->token;
    		
    		try {

    		$client = new \GuzzleHttp\Client();
			$header =$this->GetHeaderForUpdate($token);

			$token = $this->GetToken($jsonfile);
	        $res = $client->request('POST',$url,
	        	array(
	        	'headers' => $header,
	            'json' => [
	                		"product" => $data->productData
            		]
	        ));

	        // $result= json_decode($res->getBody());
	        // dd($result);

	        //echo "Product Added Successfully.";

	        }catch (\GuzzleHttp\Exception\ClientException $e) {
	    		
	    		$client = new \GuzzleHttp\Client();
				$header =$this->GetHeaderForUpdateWithNewToken($getTokenData);
				
				$token = $this->GetToken($jsonfile);
		        $res = $client->request('POST',$url,
		        	array(
		        	'headers' => $header,
		            'json' => [
		                		"product" => $data->productData
	            		]
		        ));
			}
    }


    /*
    	Fetch Stocks.
    */
    public function GetStock($data){

    	$getTokenData=$data;
	    $jsonString = file_get_contents(base_path('resources/lang/'.$data));
	    $data = json_decode($jsonString);
	    $token=$data->token;

    	$url = $data->url."/index.php/rest/".$data->version."/stockItems/".$data->productsku."/";

    	try{

	    	try{


		    	$client = new \GuzzleHttp\Client();
		    	$header =$this->GetHeaderWithExistingToken($token);
		        $res = $client->request('GET',$url,array('headers' => $header));

		        $result = json_decode($res->getBody());
		        dd($result);

	    	}catch(\GuzzleHttp\Exception\ClientException $e){

	    		$client = new \GuzzleHttp\Client();
		    	$header =$this->GetHeaderWithToken($getTokenData);
		        $res = $client->request('GET',$url,array('headers' => $header));

		        $result = json_decode($res->getBody());
		        dd($result);
	    	}
    	}catch(\GuzzleHttp\Exception\ClientException $e){
    		echo "Unable to get details. ";
    		exit;
    	}

    }


    /*
    	Fetch all Stocks.
    */
    public function GetAllStock($data){

    	$getTokenData=$data;
	    $jsonString = file_get_contents(base_path('resources/lang/'.$data));
	    $data = json_decode($jsonString);
	    $token=$data->token;

    	$url = $data->url."/index.php/rest/".$data->version."/products?searchCriteria=";

    	try{

	    	try{
	    		// echo $url;exit;
		    	$client = new \GuzzleHttp\Client();
		    	$header =$this->GetHeaderWithExistingToken($token);
		        $res = $client->request('GET',$url,array('headers' => $header));

		        $result = json_decode($res->getBody());
		        dd($result);

	    	}catch(\GuzzleHttp\Exception\ClientException $e){

	    		$client = new \GuzzleHttp\Client();
		    	$header =$this->GetHeaderWithToken($getTokenData);
		        $res = $client->request('GET',$url,array('headers' => $header));

		        $result = json_decode($res->getBody());
		        dd($result);
	    	}

    	}catch(\GuzzleHttp\Exception\ClientException $e){
    		echo "Unable to get stock.";
    		exit;
    	}

    }



    /*
    	Update Stock by product.
    */
    public function UpdateStock($data){

    	$getTokenData=$data;
	    $jsonString = file_get_contents(base_path('resources/lang/'.$data));
	    $data = json_decode($jsonString);
	   	$token=$data->token;
    	$url = $data->url."/index.php/rest/".$data->version."/products/".$data->productsku."/stockItems/1";
    	//echo $url;exit;
    	
    	try{
	    	try{
		    	$client = new \GuzzleHttp\Client();
		    	$header =$this->GetHeaderForUpdate($token);

		        $res = $client->request('PUT',$url,
		        	array(
		        	'headers' => $header,
		            'json' => 	[	
		            				'stockItem' => [
								    	'qty' => $data->quantity,
								    	'is_in_stock' => true
									]
								]
		            		)
		        );

		        $result = json_decode($res->getBody());
		        dd($result);
	    	
	    	}catch(\GuzzleHttp\Exception\ClientException $e){

	    		$client = new \GuzzleHttp\Client();
		    	$header =$this->GetHeaderForUpdateWithNewToken($getTokenData);

		        $res = $client->request('PUT',$url,
		        	array(
		        	'headers' => $header,
		            'json' => 	[	
		            				'stockItem' => [
								    	'qty' => $data->quantity,
								    	'is_in_stock' => true
									]
								]
		            		)
		        );

		        $result = json_decode($res->getBody());
		        dd($result);
	    	}

    	}catch(\GuzzleHttp\Exception\ClientException $e){
    		echo "Unable to update the stock";
    		exit;
    	}

    }	



     public function UpdateMultipleProducts($data){

    	$getTokenData=$data;
	    $jsonString = file_get_contents(base_path('resources/lang/'.$data));
	    $data = json_decode($jsonString);
	   	$token=$data->token;

    	for($i=1;$i<=$data->no_of_products;$i++) {
    		
	    	try{
	    		$product="productsku".$i;
				$qty="quantity".$i;
				
				$url = $data->url."/index.php/rest/".$data->version."/products/".$data->$product."/stockItems/1";
		    	try{

			    	$client = new \GuzzleHttp\Client();
			    	$header =$this->GetHeaderForUpdate($token);

			        $res = $client->request('PUT',$url,
			        	array(
			        	'headers' => $header,
			            'json' => 	[	
			            				'stockItem' => [
									    	'qty' => $data->$qty,
									    	'is_in_stock' => true
										]
									]
			            		)
			        );

			        $result = json_decode($res->getBody());
			        dd($result);
		    	
		    	}catch(\GuzzleHttp\Exception\ClientException $e){

		    		$client = new \GuzzleHttp\Client();
			    	$header =$this->GetHeaderForUpdateWithNewToken($getTokenData);

			        $res = $client->request('PUT',$url,
			        	array(
			        	'headers' => $header,
			            'json' => 	[	
			            				'stockItem' => [
									    	'qty' => $data->$qty,
									    	'is_in_stock' => true
										]
									]
			            		)
			        );

			        $result = json_decode($res->getBody());
			        dd($result);
		    	}

	    	}catch(\GuzzleHttp\Exception\ClientException $e){
	    		echo "Exception at product ".$data->$product."<br>";
	    		
	    	}
    	}

    }	

    public function GetHeader() {
        
        return ([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]);
    }
    

    public function GetHeaderWithToken($getTokenData) {
        
        $token = $this->FetchToken($getTokenData);
        
        return ([
               	'Content-Type' => 'application/json',
	        	'Authorization' => 'Bearer '.json_decode($token)
            ]);
    }

    public function GetHeaderWithExistingToken($token) {
        
        return ([
               	'Content-Type' => 'application/json',
	        	'Authorization' => 'Bearer '.$token
            ]);
    }

    public function GetHeaderForUpdate($token){


    	return	([
	        		'Accept' => 'application/json',
	                'Content-Type' => 'application/json',
	                'Authorization' => 'Bearer '.$token

	            ]);
    }


    public function GetHeaderForUpdateWithNewToken($getTokenData){

    	$token = $this->FetchToken($getTokenData);
    	return	([
	        		'Accept' => 'application/json',
	                'Content-Type' => 'application/json',
	                'Authorization' => 'Bearer '.json_decode($token)
	            ]);
    }



    public function fetch(){
    	echo "asd";
    }


}