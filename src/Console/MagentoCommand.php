<?php 
namespace Magento\Console;

use Illuminate\Console\Command;
use Magento\Facades\MagentoFetch;
/**
 * 
 */
class MagentoCommand extends Command
{
	protected $signature='magento:fetchsingleorder {data}';
	
	protected $description = 'Fetches single order';

	public function handle()
    {	
    	$data= $this->argument('data');
    	return MagentoFetch::FetchSingleOrder($data);
    }
}

class MagentoFetchOrderByDate extends Command
{
	protected $signature='magento:fetchordersbydate {data}';
	
	protected $description = 'Fetch orders by date';

	public function handle()
    {
    	$data= $this->argument('data');
    	return MagentoFetch::FetchOrdersByDate($data);
    }
}

class UpdateSingleOrder extends Command
{
	protected $signature='magento:updatesingleorder {data}';
	
	protected $description = 'Updates order by id';

	public function handle()
    {
    	$data= $this->argument('data');
    	return MagentoFetch::UpdateSingleOrder($data);
    }
}

class UpdateOrdersById extends Command
{
	protected $signature='magento:updateordersbyid {data}';
	
	protected $description = 'Updates multiple orders by ids';

	public function handle()
    {
    	$data= $this->argument('data');
    	return MagentoFetch::UpdateOrdersById($data);
    }
}

class UpdateOrdersByDate extends Command
{
	protected $signature='magento:updateordersbydate {data}';
	
	protected $description = 'Updates multiple orders by dates';

	public function handle()
    {
    	$data= $this->argument('data');
    	return MagentoFetch::UpdateOrdersByDate($data);
    }
}

class CreateOrder extends Command
{
	protected $signature='magento:createorder {data}';
	
	protected $description = 'Create order';

	public function handle()
    {
    	$data= $this->argument('data');
    	return MagentoFetch::CreateOrder($data);
    }
}

class CreateProduct extends Command
{
	protected $signature='magento:createproduct {data}';
	
	protected $description = 'Create new product';

	public function handle()
    {
    	$data= $this->argument('data');
    	return MagentoFetch::CreateProduct($data);
    }
}


class GetStock extends Command
{
	protected $signature='magento:getstock {data}';
	
	protected $description = 'Get product details';

	public function handle()
    {
    	$data= $this->argument('data');
    	return MagentoFetch::GetStock($data);
    }
}


class GetAllStock extends Command
{
	protected $signature='magento:getallproducts {data}';
	
	protected $description = 'Get all products';

	public function handle()
    {
    	$data= $this->argument('data');
    	return MagentoFetch::GetAllStock($data);
    }
}

class UpdateStock extends Command
{
	protected $signature='magento:updatesingleproduct {data}';
	
	protected $description = 'Update stock of a single product';

	public function handle()
    {
    	$data= $this->argument('data');
    	return MagentoFetch::UpdateStock($data);
    }
}


class UpdateMultipleProducts extends Command
{
	
	protected $signature='magento:updateproducts {data}';
	
	protected $description = 'Update stock of a mulptiple products';

	public function handle()
    {
    	$data= $this->argument('data');
    	return MagentoFetch::UpdateMultipleProducts($data);
    }
}
