<?php

namespace TieuMinh\Loyalty\Model\ResourceModel\LoyaltyRule;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'rule_id';
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('TieuMinh\Loyalty\Model\LoyaltyRule', 'TieuMinh\Loyalty\Model\ResourceModel\LoyaltyRule');
    }

    /**
     * @param $loyaltyRuleId
     * @return array
     */
    public function fetchRuleId($loyaltyRuleId)
    {
        $sql = "SELECT * FROM loyalty_rule WHERE rule_id = $loyaltyRuleId";
        return $this->getConnection()->fetchAll($sql);
    }
}
