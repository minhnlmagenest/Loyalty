<?php

namespace TieuMinh\Loyalty\Model\ResourceModel\LoyaltyForm;

use TieuMinh\Loyalty\Model\LoyaltyForm;

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
        $this->_init(LoyaltyForm::class, \TieuMinh\Loyalty\Model\ResourceModel\LoyaltyForm::class);
    }

    /**
     * @return Collection|void
     */
    public function _initSelect()
    {
        parent::_initSelect();
        $this->fetchWebsite();
        $this->fetchCustomer();
    }

    /**
     * @return $this
     */
    public function fetchWebsite()
    {
        $loyaltyWebsite = [];
        foreach ($this as $loyalty) {
            $loyaltyWebsite[$loyalty->getId()] = [];
        }
        if (!empty($loyaltyWebsite)) {
            $select = $this->getConnection()->select()->from(
                ['lw' => 'loyalty_website']
            )->join(
                ['sw' => $this->getResource()->getTable('store_website')],
                'lw.website_id = sw.website_id',
                'sw.name'
            )->where(
                'lw.loyalty_id IN (?)',
                array_keys($loyaltyWebsite)
            )->where(
                'lw.loyalty_id > ?',
                0
            );

            $data = $this->getConnection()->fetchAll($select);
            foreach ($data as $row) {
                $loyaltyWebsite[$row['loyalty_id']][] = $row['website_id'];
            }
        }
        foreach ($this as $loyalty) {
            if (isset($loyaltyWebsite[$loyalty->getId()])) {
                $loyalty->setData('website_ids', $loyaltyWebsite[$loyalty->getId()]);
            }
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function fetchCustomer()
    {
        $customerGroup = [];
        foreach ($this as $loyalty) {
            $customerGroup[$loyalty->getId()] = [];
        }
        if (!empty($customerGroup)) {
            $select = $this->getConnection()->select()->from(
                ['lcg' => 'loyalty_customer_group']
            )->join(
                ['cg' => $this->getResource()->getTable('customer_group')],
                'lcg.customer_group_id = cg.customer_group_id',
                'cg.customer_group_code'
            )->where(
                'lcg.loyalty_id IN (?)',
                array_keys($customerGroup)
            )->where(
                'lcg.loyalty_id > ?',
                0
            );

            $data = $this->getConnection()->fetchAll($select);
            foreach ($data as $row) {
                $customerGroup[$row['loyalty_id']][] = $row['customer_group_id'];
            }
        }
        foreach ($this as $loyalty) {
            if (isset($customerGroup[$loyalty->getId()])) {
                $loyalty->setData('customer_group_ids', $customerGroup[$loyalty->getId()]);
            }
        }
        return $this;
    }
}
