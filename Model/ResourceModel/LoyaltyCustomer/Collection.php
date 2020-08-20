<?php

namespace TieuMinh\Loyalty\Model\ResourceModel\LoyaltyCustomer;

use TieuMinh\Loyalty\Model\LoyaltyCustomer;

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
        $this->_init(LoyaltyCustomer::class, \TieuMinh\Loyalty\Model\ResourceModel\LoyaltyCustomer::class);
    }

    /**
     * @param $customer_group_id
     * @return array
     */
    public function fetchCustomerGroup($customer_group_id)
    {
        $sql = "SELECT * FROM loyalty_customer_group WHERE customer_group_id = $customer_group_id";

        return $this->getConnection()->fetchAll($sql);
    }

}
