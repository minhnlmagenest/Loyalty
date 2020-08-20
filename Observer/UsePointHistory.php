<?php
namespace TieuMinh\Loyalty\Observer;

use Magento\Customer\Model\CustomerFactory;
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
class UsePointHistory implements ObserverInterface
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
     * @var CustomerFactory
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
     * @param CustomerFactory $customerFactory
     * @param LoyaltyHistoryFactory $loyaltyHistoryFactory
     */
    public function __construct(
        LoyaltyRuleFactory $loyaltyRuleFactory,
        LoyaltyCustomerFactory $loyaltyCustomerFactory,
        CustomerFactory $customerFactory,
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
        $customer = $this->customerFactory->create();
        $history = $this->loyaltyHistoryFactory->create();

        $point_used = $order->getData('point_used');

        if ($point_used == 0) {
            return $this;
        } elseif ($order->getData('state') == 'new') {
            $order_increment_id = $order->getData('increment_id');
            $customer_id = $order->getData('customer_id');
            date_default_timezone_set("Asia/Ho_Chi_Minh");
            $current_date = date("Y-m-d H:i:s");
            $history->setData('sales_order_id', $order_increment_id)
                    ->setData('total_spent', "-$point_used")
                ->setData('customer_id', $customer_id)
                ->setData('created_at', $current_date)
                ->save();
        }

        return $this;
    }
}
