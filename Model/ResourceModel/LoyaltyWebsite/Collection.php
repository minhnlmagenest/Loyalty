<?php

namespace TieuMinh\Loyalty\Model\ResourceModel\LoyaltyWebsite;

use TieuMinh\Loyalty\Model\LoyaltyWebsite;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(LoyaltyWebsite::class, \TieuMinh\Loyalty\Model\ResourceModel\LoyaltyWebsite::class);
    }

}
