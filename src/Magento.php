<?php 
namespace Magento;

use Illuminate\Http\Request;
use App\Http\Requests;
use GuzzleHttp\Client;
use Magento\Traits\ResponseOptions;
use Magento\Contracts\MagentoContract;
/**
 * 
 */
class Magento implements MagentoContract
{	

	use ResponseOptions;

	protected $user;

	protected $magentoAuth;

	protected $apiCall;

	protected $requestPath;

	protected $httpClient;

	protected $setToken;

	public function __construct(MagentoAuth $magentoAuth)
    {
        $this->magentoAuth = $magentoAuth;
    }

    public function retrieve($shopURL, $username, $password, $token, $secretkey)
    {
        $this->apiCall = $this->magentoAuth->setShopURL( $shopURL );

        $this->setToken = $this->magentoAuth->setToken( $token );

        $this->requestPath = $this->magentoAuth->requestPath();

        $this->user = $this->magentoAuth->fetchToken($username, $password);

        // $this->user= $this->magentoAuth->getUserByToken('06llrmxczbxau12mfwzfa57y1g9kl1mr');

        // print_r($this->user);
        // exit;
  
        return $this;
    }

	public function make($shopURL, array $scope)
    {

        $allScope = $this->getAllScopes();

        if (!array_intersect( $allScope, $scope ) == $scope) {
            throw New InvalidArgumentException( 'invalid Scope' );
        }

        $this->apiCall = $this->shopifyAuth->stateless()->setShopURL( $shopURL )->scopes( $scope );

        $this->requestPath = $this->shopifyAuth->requestPath();

        return $this;
    }

    public function auth()
    {
        $this->user = $this->apiCall->user();

        return $this;
    }

    public function redirect()
    {
        return $this->shopifyAuth->redirect();
    }

	
	/* 
    	Fetches a single order details by using admin token and order id.
    */
    public function fetchSingleOrder($orderid){
    	
        $url = $this->magentoAuth->requestPath()."orders/".$orderid;       

           try{
	            try {
	            	
					$client = new \GuzzleHttp\Client();

					// $header =$this->magentoAuth->returnResponseHeaders();

					$header =$this->getResponseHeaders($this->magentoAuth->getToken());

			        $res = $client->request('GET',$url,array('headers' => $header));

			        $result= json_decode($res->getBody());
			        
			        dd($result);
		        	
		        
		        }catch (\GuzzleHttp\Exception\ClientException $e) {
		    		
					print_r($e->getMessage());
					
		        	$client = new \GuzzleHttp\Client();

					// $header = $this->getHeaderWithNewToken();
					$header =$this->magentoAuth->returnResponseHeaders();
					
			        $res = $client->request('GET',$url,array('headers' => $header));
			        
			        $result= json_decode($res->getBody());
			        dd($result);
			        
				}
			}catch (\GuzzleHttp\Exception\ClientException $e) {
				echo "Unable to fetch order" ;
				print_r($e->getMessage());
            	
			}

    }



    /* 
    	Fetches details of multiple orders by using admin token and dates.
    */
	public function fetchOrdersByDate($from, $to){

        $url = $this->magentoAuth->requestPath()."orders/?searchCriteria[filter_groups][0][filters][0][field]=created_at&searchCriteria[filter_groups][0][filters][0][value]=".$from."%2000:00:01&searchCriteria[filter_groups][0][filters][0][condition_type]=from&searchCriteria[filter_groups][1][filters][1][field]=created_at&searchCriteria[filter_groups][1][filters][1][value]=".$to."%2023:59:59&searchCriteria[filter_groups][1][filters][1][condition_type]=to";
            
        try {

	        try {
			
				$client = new \GuzzleHttp\Client();

				$header =$this->getResponseHeaders($this->magentoAuth->getToken());

		        $res = $client->request('GET',$url,array('headers' => $header));

		        $result= json_decode($res->getBody());
		        dd($result);
		        
		    }catch (\GuzzleHttp\Exception\ClientException $e) {

		    	print_r($e->getMessage());
	    		
	    		$header =$this->magentoAuth->returnResponseHeaders();

	    		$client = new \GuzzleHttp\Client();
				
		        $res = $client->request('GET',$url,array('headers' => $header));

		        $result= json_decode($res->getBody());
		        dd($result);

			}
		}catch (\GuzzleHttp\Exception\ClientException $e) {
			echo "Unable to fetch orders. ";
			print_r($e->getMessage());
            
		}

    }



    /*
    	Updates a single order details by using admin token and order id.
    */
    public function updateSingleOrder($data){


        $url = $this->magentoAuth->requestPath()."orders/".$data->orderid."/comments";
            
        try{
	        try {
				
				$client = new \GuzzleHttp\Client();

				$header =$this->getResponseHeaders($this->magentoAuth->getToken());

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

		    	print_r($e->getMessage());
		    	
		    	$client = new \GuzzleHttp\Client();
				
				$header =$this->magentoAuth->returnResponseHeaders();
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
				print_r($e->getMessage());
            	
		}
        

    }




    /* 
    	Updates details of multiple orders by using order ids.
    */
    public function updateOrdersById($data){

		$client = new \GuzzleHttp\Client();
		
		for($i=1;$i<=$data->no_of_orders;$i++) {
			try {

				$orderid="order_id".$i;
				$comment="comment".$i;
				$status="status".$i;
				$url = $this->magentoAuth->requestPath()."orders/".$data->$orderid."/comments";

				try{
					
					$header =$this->getResponseHeaders($this->magentoAuth->getToken());

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

		    		print_r($e->getMessage());
		    		
		    		$header =$this->magentoAuth->returnResponseHeaders();

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
		    		
		    	echo "Exception at Order no. ".$data->$orderid."\n";
		    	print_r($e->getMessage());

			}

		}


    }


    /* 
    	Updates details of multiple orders by using admin token and dates.
    */
    public function updateOrdersByDate($data){

        $url = $this->magentoAuth->requestPath()."orders/?searchCriteria[filter_groups][0][filters][0][field]=created_at&searchCriteria[filter_groups][0][filters][0][value]=".$from."%2000:00:01&searchCriteria[filter_groups][0][filters][0][condition_type]=from&searchCriteria[filter_groups][1][filters][1][field]=created_at&searchCriteria[filter_groups][1][filters][1][value]=".$to."%2023:59:59&searchCriteria[filter_groups][1][filters][1][condition_type]=to";
        
        
        try {
			
			try{
				$client = new \GuzzleHttp\Client();

				$header =$this->getResponseHeaders($this->magentoAuth->getToken());

		        $res = $client->request('GET',$url,array('headers' => $header) );

		        $result= json_decode($res->getBody());
	        	
		    }catch (\GuzzleHttp\Exception\ClientException $e) {

		    	print_r($e->getMessage());
	    		$client = new \GuzzleHttp\Client();
				$header =$this->magentoAuth->returnResponseHeaders();
		        $res = $client->request('GET',$url,array('headers' => $header));

		        $result= json_decode($res->getBody());
			}

	        foreach ($result->items as $order) {
	        	
				$client = new \GuzzleHttp\Client();
				$url = $this->magentoAuth->requestPath()."orders/".$order->entity_id."/comments";

				$header =$this->getResponseHeaders($this->magentoAuth->getToken());
		        $res = $client->request('POST',$url,
		        	array(
		        	'headers' => $header,
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

		       

	    	}
	        echo "Orders Updated Suceessfully.";
	        
	    }catch (\GuzzleHttp\Exception\ClientException $e) {
	    		// echo"Unable to update the orders.";
	    		print_r($e->getMessage());
	    		
		}

    }


    /* 
    	Creates a new order.
    */
    public function createOrder($data){
    	
        try {

        $client = new \GuzzleHttp\Client();
        $url = $this->magentoAuth->requestPath()."integration/customer/token";
        $header=$this->getHeader();
        $res = $client->request('POST',$url,
        	array(
        	'headers' => $header,
        	'json' => [
                "username" => $data->customer_username,
				"password" => $data->Customer_password
            ])   
        );

        $CustomerToken= json_decode($res->getBody());
        
        $headerWithCustomerToken=([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$CustomerToken
            ]);

        $client = new \GuzzleHttp\Client();
        $url = $this->magentoAuth->requestPath()."carts/mine";

        $res = $client->request('POST',$url,
        	array(
        	'headers' => $headerWithCustomerToken)   
        );

        $quoteId= json_decode($res->getBody());
        //echo $quoteId;exit;

        $client = new \GuzzleHttp\Client();
        $url = $this->magentoAuth->requestPath()."carts/mine/items";

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
        $url = $this->magentoAuth->requestPath()."carts/mine/estimate-shipping-methods";

        $res = $client->request('POST',$url,
        	array(
        	'headers' => $headerWithCustomerToken,
        	'json' => [
                "address" => $data->address
            ])    
        );
        
        $client = new \GuzzleHttp\Client();
        $url = $this->magentoAuth->requestPath()."carts/mine/shipping-information";

        $res = $client->request('POST',$url,
        	array(
        	'headers' => $headerWithCustomerToken,
        	'json' => [
        			"addressInformation" => $data->addressInfo
            ])    
        );


        $client = new \GuzzleHttp\Client();
        $url = $this->magentoAuth->requestPath()."carts/mine/payment-information";

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
	    		print_r($e->getMessage());
            	
		}

    }



    /* 
    	Create a new product.
    */
    public function createProduct($data){

    		$url = $this->magentoAuth->requestPath()."products";
    		

    		try {

    		$client = new \GuzzleHttp\Client();
			$header = $this->getHeaderForUpdate($this->magentoAuth->getToken());

			
	        $res = $client->request('POST',$url,
	        	array(
	        	'headers' => $header,
	            'json' => [
	                		"product" => $data->productData
            		]
	        ));

	       
	        }catch (\GuzzleHttp\Exception\ClientException $e) {
	    		
	    		print_r($e->getMessage());
	    		$client = new \GuzzleHttp\Client();
				$header =$this->getHeaderForUpdateWithNewToken();
				
				
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
    public function getStock($productsku){

    	$url = $this->magentoAuth->requestPath()."stockItems/".$productsku."/";

    	try{

	    	try{

		    	$client = new \GuzzleHttp\Client();

		    	$header =$this->getResponseHeaders($this->magentoAuth->getToken());

		        $res = $client->request('GET',$url,array('headers' => $header));

		        $result = json_decode($res->getBody());
		        dd($result);

	    	}catch(\GuzzleHttp\Exception\ClientException $e){

	    		print_r($e->getMessage());

	    		$client = new \GuzzleHttp\Client();

		    	$header =$this->magentoAuth->returnResponseHeaders();

		        $res = $client->request('GET',$url,array('headers' => $header));

		        $result = json_decode($res->getBody());
		        dd($result);
	    	}
    	}catch(\GuzzleHttp\Exception\ClientException $e){
    		echo "Unable to get details. ";
    		print_r($e->getMessage()); 	
    	}

    }


    /*
    	Fetch all Stocks.
    */
    public function getAllStock(){

    	$url = $this->magentoAuth->requestPath()."products?searchCriteria=";

    	try{

	    	try{
	    		
		    	$client = new \GuzzleHttp\Client();
		    	
		    	$header =$this->getResponseHeaders($this->magentoAuth->getToken());

		        $res = $client->request('GET',$url,array('headers' => $header));

		        $result = json_decode($res->getBody());
		        dd($result);

	    	}catch(\GuzzleHttp\Exception\ClientException $e){

	    		print_r($e->getMessage());

	    		$client = new \GuzzleHttp\Client();

		    	$header =$this->magentoAuth->returnResponseHeaders();

		        $res = $client->request('GET',$url,array('headers' => $header));

		        $result = json_decode($res->getBody());
		        dd($result);
	    	}

    	}catch(\GuzzleHttp\Exception\ClientException $e){
    		echo "Unable to get stock.";
    		print_r($e->getMessage()); 
    	}

    }



    /*
    	Update Stock by product.
    */
    public function updateStock($data){

    	$url = $this->magentoAuth->requestPath()."products/".$data->productsku."/stockItems/1";
    	   	
    	try{
	    	try{
		    	$client = new \GuzzleHttp\Client();

		    	$header =$this->getHeaderForUpdate($this->magentoAuth->getToken());

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

	    		print_r($e->getMessage());

	    		$client = new \GuzzleHttp\Client();

		    	$header =$this->getHeaderForUpdateWithNewToken();

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
    		print_r($e->getMessage()); 
    	}

    }	


    /*
    	Update Stock of multiple products.
    */
     public function updateMultipleProducts($data){

    	for($i=1;$i<=$data->no_of_products;$i++) {
    		
	    	try{
	    		$product="productsku".$i;
				$qty="quantity".$i;
				
				$url = $this->magentoAuth->requestPath()."products/".$data->$product."/stockItems/1";
		    	try{

			    	$client = new \GuzzleHttp\Client();

			    	$header =$this->getHeaderForUpdate($this->magentoAuth->getToken());

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

		    		print_r($e->getMessage());

		    		$client = new \GuzzleHttp\Client();

			    	$header =$this->getHeaderForUpdateWithNewToken();

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
	    		print_r($e->getMessage()); 
	    	}
    	}

    }	


}
