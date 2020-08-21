<?php
namespace TieuMinh\Loyalty\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use TieuMinh\Loyalty\Model\CustomerEntityFactory;
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

        $total_qty_order = $order->getData('total_qty_ordered');
        $base_grand_total = $order->getData('base_grand_total');
        $base_total_paid = $order->getData('base_total_paid');
        $status = $order->getData('status');
        $one_item_price = round($base_grand_total / $total_qty_order);
        $number_item_invoiced = round($base_total_paid / $one_item_price);


        if ($total_qty_order - $number_item_invoiced >= 0 && $status != 'pending') {
            $customer = $this->customerFactory->create();
            $customer_id = $order->getData('customer_id');

            $filter_customer = $customer->getCollection()->addFieldToFilter('entity_id', $customer_id);

            foreach ($filter_customer as $value) {
                $balance = $value->getData('value');
            }

            $point_earn = $order->getData('point_earn');
            $each_item_point = round($point_earn / $total_qty_order);
            $new_balance = $balance + ($number_item_invoiced * $each_item_point);
            $customer->getCollection()->upDateBalance($new_balance, $customer_id);

            $history = $this->loyaltyHistoryFactory->create();
            $order_increment_id = $order->getData('increment_id');

            date_default_timezone_set("Asia/Ho_Chi_Minh");
            $current_date = date("Y-m-d H:i:s");
            $filter_history = $history->getCollection()->addFieldToFilter('sales_order_id', $order_increment_id);
            if ($filter_history->getItems() != null) {
                foreach ($filter_history as $value) {
                    $total_earn = $history->getData('total_earned') + $number_item_invoiced * $each_item_point;
                    $value->setData('sales_order_id', $order_increment_id)
                    ->setData('total_earned', "+$total_earn")
                    ->setData('customer_id', $customer_id)
                    ->setData('created_at', $current_date)
                    ->save();
                }
            } else {
                $total_earn = $number_item_invoiced * $each_item_point;
                $history->setData('sales_order_id', $order_increment_id)
                    ->setData('total_earned', "+$total_earn")
                    ->setData('customer_id', $customer_id)
                    ->setData('created_at', $current_date)
                    ->save();
            }
        }
        return $this;
    }
}
