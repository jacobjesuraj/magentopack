<?php
namespace Magento\Facades;

use Illuminate\Support\Facades\Facade;

use Magento\Contracts\MagentoContract as Magento;

// use Magento\Magento;

class MagentoFacade extends Facade {
  
   protected static function getFacadeAccessor() { 

   	// return 'magento'; 
   	return Magento::class;

   }

}
