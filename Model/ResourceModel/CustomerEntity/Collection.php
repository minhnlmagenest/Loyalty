<?php

namespace TieuMinh\Loyalty\Model\ResourceModel\CustomerEntity;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'value_id';
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('TieuMinh\Loyalty\Model\CustomerEntity', 'TieuMinh\Loyalty\Model\ResourceModel\CustomerEntity');
    }

    /**
     * @param $customer_id
     * @return string
     */
    public function getBalance($customer_id)
    {
        $sql = "SELECT value FROM customer_entity_int WHERE entity_id = $customer_id";

        return $this->getConnection()->fetchOne($sql);
    }

    /**
     * @param $new_balance
     * @param $customer_id
     */
    public function updateBalance($new_balance, $customer_id)
    {
        $sql = "UPDATE customer_entity_int SET value = $new_balance WHERE entity_id = $customer_id";

        return $this->getConnection()->query($sql);
    }
}
