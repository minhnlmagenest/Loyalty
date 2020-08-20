<?php

namespace TieuMinh\Loyalty\Model\ResourceModel\LoyaltyHistory;

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
        $this->_init('TieuMinh\Loyalty\Model\LoyaltyHistory', 'TieuMinh\Loyalty\Model\ResourceModel\LoyaltyHistory');
    }

    public function isCustomerIdExist($customer_id)
    {
        $select = "SELECT * FROM loyalty_history WHERE customer_id = $customer_id";

        $result =  $this->getConnection()->fetchAll($select);
        return $result;
    }
}
