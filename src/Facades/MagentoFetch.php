<?php
namespace Magento\Facades;

use Illuminate\Support\Facades\Facade;
//use Magento\Vietech\MagentoFetch;


class MagentoFetch extends Facade {
   protected static function getFacadeAccessor() { return 'magento'; }

}
