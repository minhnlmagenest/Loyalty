<?php
namespace TieuMinh\Loyalty\Observer;

use TieuMinh\Loyalty\Model\CustomerEntityFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use TieuMinh\Loyalty\Model\LoyaltyCustomerFactory;
use TieuMinh\Loyalty\Model\LoyaltyHistoryFactory;
use TieuMinh\Loyalty\Model\LoyaltyRuleFactory;

/**
 * Class SalesEventQuoteSubmitBeforeObserver
 * @package TieuMinh\Loyalty\Observer
 * @author Minhnl
 */
class AddPointToBalance implements ObserverInterface
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
     * @var LoyaltyHistoryFactory
     */
    private $loyaltyHistoryFactory;

    /**
     * SalesEventQuoteSubmitBeforeObserver constructor.
     * @param LoyaltyRuleFactory $loyaltyRuleFactory
     * @param LoyaltyCustomerFactory $loyaltyCustomerFactory
     * @param CustomerEntityFactory $customerFactory
     * @param LoyaltyHistoryFactory $loyaltyHistoryFactory
     */
    public function __construct(
        LoyaltyRuleFactory $loyaltyRuleFactory,
        LoyaltyCustomerFactory $loyaltyCustomerFactory,
        CustomerEntityFactory $customerFactory,
        LoyaltyHistoryFactory $loyaltyHistoryFactory
    ) {
        $this->loyaltyRuleFactory = $loyaltyRuleFactory;
        $this->loyaltyCustomerFactory = $loyaltyCustomerFactory;
        $this->customerFactory = $customerFactory;
        $this->loyaltyHistoryFactory = $loyaltyHistoryFactory;
    }

    /**
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        $base_total_paid = $order->getData('base_total_paid');
        $base_grand_total = $order->getData('base_grand_total');

        if ($base_total_paid == null || $base_total_paid == 0 || $base_total_paid < $base_grand_total) {
            return $this;
        }
        if ($base_grand_total == $base_total_paid) {
            $customer = $this->customerFactory->create();
            $customer_id = $order->getData('customer_id');
            $balance = $customer->getCollection()->getBalance($customer_id) * 1;
            $balance= (int) $balance;
            $point_earn = $order->getData('point_earn');
            $point_earn = (int) $point_earn;
            $new_balance = $balance + $point_earn;
            $customer->getCollection()->updateBalance($new_balance, $customer_id);
            $history = $this->loyaltyHistoryFactory->create();
            $order_increment_id = $order->getData('increment_id');

            date_default_timezone_set("Asia/Ho_Chi_Minh");
            $current_date = date("Y-m-d H:i:s");

            $history->setData('sales_order_id', $order_increment_id)
                ->setData('total_earned', "+$point_earn")
                ->setData('customer_id', $customer_id)
                ->setData('created_at', $current_date)
                ->save();
        }
        return $this;
    }
}
