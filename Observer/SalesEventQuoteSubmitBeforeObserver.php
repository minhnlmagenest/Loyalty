<?php

namespace TieuMinh\Loyalty\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use TieuMinh\Loyalty\Model\CustomerEntityFactory;
use TieuMinh\Loyalty\Model\LoyaltyCustomerFactory;
use TieuMinh\Loyalty\Model\LoyaltyRuleFactory;

/**
 * Class SalesEventQuoteSubmitBeforeObserver
 * @package TieuMinh\Loyalty\Observer
 * @author Minhnl
 */
class SalesEventQuoteSubmitBeforeObserver implements ObserverInterface
{
    /**
     * @var LoyaltyRuleFactory
     */
    private $loyaltyRuleFactory;
    /**
     * @var LoyaltyCustomerFactory
     */
    private $loyaltyCustomerFactory;
    /**
     * @var CustomerEntityFactory
     */
    private $customerFactory;

    /**
     * SalesEventQuoteSubmitBeforeObserver constructor.
     * @param LoyaltyRuleFactory $loyaltyRuleFactory
     * @param LoyaltyCustomerFactory $loyaltyCustomerFactory
     * @param CustomerEntityFactory $customerFactory
     */
    public function __construct(
        LoyaltyRuleFactory $loyaltyRuleFactory,
        LoyaltyCustomerFactory $loyaltyCustomerFactory,
        CustomerEntityFactory $customerFactory
    ) {
        $this->loyaltyRuleFactory = $loyaltyRuleFactory;
        $this->loyaltyCustomerFactory = $loyaltyCustomerFactory;
        $this->customerFactory = $customerFactory;
    }

    /**
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $data = [];
        $order = $observer->getEvent()->getOrder();
        $quote = $observer->getEvent()->getQuote();
        $loyaltyRuleFactory = $this->loyaltyRuleFactory->create();
        $loyaltyCustomerFactory = $this->loyaltyCustomerFactory->create();

        $customer_group_id = $quote->getData('customer_group_id');
        $hasCustomerGroupId = $loyaltyCustomerFactory->getCollection()->fetchCustomerGroup($customer_group_id);


        if ($hasCustomerGroupId != []) {
            foreach ($hasCustomerGroupId as $item) {
                $data['ruleid'] = $item['loyalty_id'];

                if (!empty($data['ruleid'])) {
                    $rule_infor = $loyaltyRuleFactory->getCollection()->fetchRuleId($data['ruleid']);
                }
                //minimum amount condition
                $subtotal = (int) round($quote->getData('subtotal'));
                $condition = $subtotal - $rule_infor[0]['minimum_amount'];
                //apply from <= current date <= apply to
                date_default_timezone_set("Asia/Ho_Chi_Minh");
                $current_date = strtotime(date("Y-m-d H:i:s"));
                $apply_from = strtotime($rule_infor[0]['apply_from']);
                $apply_to = strtotime($rule_infor[0]['apply_to']);

                $apply_from_condition = $current_date - $apply_from;
                $apply_to_condition = $current_date - $apply_to;

                //check condition for minimum amount and time in force
                if ($apply_from_condition >= 0 && $apply_to_condition <= 0 && $condition >= 0 && $rule_infor[0]['status'] == 1) {
                    //check rule fixed point
                    if ($rule_infor[0]['rule'] == 'by_fixed') {
                        $amount = $order->setData('point_earn', $rule_infor[0]['rule_amount']);
                        break;
                    //check rule rate point
                    } elseif ($rule_infor[0]['rule'] == 'by_percent') {
                        $amount = floor(($subtotal * $rule_infor[0]['rule_rate']) / 100);
                        $order->setData('point_earn', $amount);
                        break;
                    //check rule point by step
                    } elseif ($rule_infor[0]['rule'] == 'by_step') {

                        $amount = floor(($rule_infor[0]['rule_step'] / $rule_infor[0]['price_step']) * $subtotal);
                        $order->setData('point_earn', $amount);
                        break;
                    }
                }
            }
            //update balance
            $customer = $this->customerFactory->create();
            $customer_id = $quote->getData('customer_id');
            $balance = $customer->getCollection()->getBalance($customer_id) * 1;
            $balance = (int) $balance;
            $point_used = $quote->getData('point_used');
            $point_used = (int) $point_used;
            $new_balance = $balance - $point_used;
            $customer->getCollection()->updateBalance($new_balance, $customer_id);
        }
        //set point use to order table
        $order->setData('point_used', $quote->getData('point_used'));
        return $this;
    }
}
