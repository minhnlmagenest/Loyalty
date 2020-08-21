<?php

namespace TieuMinh\Loyalty\Block\MyLoyalty;

use Magento\Backend\Block\Template;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Pricing\Helper\Data as priceHelper;
use TieuMinh\Loyalty\Model\ResourceModel\LoyaltyHistory\CollectionFactory;

/**
 * Class View
 * @package TieuMinh\Loyalty\Block\MyLoyalty
 * @author Minhnl
 */
class View extends Template
{
    protected $loyaltyHistoryCollectionFactory;
    protected $priceHepler;

    protected $collection = null;
    /**
     * @var Session
     */
    private $customerSession;
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerFactory;

    /**
     * View constructor.
     * @param Template\Context $context
     * @param Http $request
     * @param CollectionFactory $loyaltyHistoryCollectionFactory
     * @param priceHelper $priceHepler
     * @param Session $customerSession
     * @param CustomerRepositoryInterface $customerFactory
     */
    public function __construct(
        Template\Context $context,
        Http $request,
        CollectionFactory $loyaltyHistoryCollectionFactory,
        priceHelper $priceHepler,
        Session $customerSession,
        CustomerRepositoryInterface $customerFactory
    ) {
        $this->loyaltyHistoryCollectionFactory = $loyaltyHistoryCollectionFactory;
        $this->customerSession = $customerSession;
        parent::__construct($context);
        $this->customerFactory = $customerFactory;
    }

    /**
     * @return $this|View
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->pageConfig->getTitle()->set(__('My Loyalty'));
        $pager = $this->getLayout()->createBlock(
            'Magento\Theme\Block\Html\Pager',
            'custom.history.pager'
        )->setAvailableLimit([5 => 5, 10 => 10, 15 => 15, 20 => 20])
            ->setShowPerPage(true)->setCollection(
                $this->getCustomCollection()
            );
        $this->setChild('pager', $pager);
        return $this;
    }

    /**
     * @return \TieuMinh\Loyalty\Model\ResourceModel\LoyaltyHistory\Collection
     */
    public function getCustomCollection()
    {
        $id = $this->customerSession->getCustomer()->getId();
        if ($this->collection == null) {
            $collection = $this->loyaltyHistoryCollectionFactory->create();
            $collection->addFieldToFilter('customer_id', $id);
            $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
            $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : 5;

            $collection->setPageSize($pageSize);
            $collection->setCurPage($page);
            $this->collection = $collection;
        }
        return $this->collection;
    }

    /**
     * @param $price
     * @return mixed
     */
    public function getFormattedPrice($price)
    {
        return $this->priceHepler->currency(number_format($price, 2), true, false);
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * @return int
     */
    public function totalEarned()
    {
        $customer_id = $this->customerSession->getCustomer()->getId();
        $history = $this->loyaltyHistoryCollectionFactory->create();
        $history->addFieldToFilter('customer_id', $customer_id);
        $total_point = 0;
            foreach ($history as $item) {
            $earn = (int) trim($item->getTotalEarned(), '+');
            $total_point = $earn + $total_point;
        }
        return $total_point;
    }

    /**
     * @return int
     */
    public function totalUsed()
    {
        $customer_id = $this->customerSession->getCustomer()->getId();
        $history = $this->loyaltyHistoryCollectionFactory->create();
        $history->addFieldToFilter('customer_id', $customer_id);
        $total_point = 0;
        foreach ($history as $item) {
            $use = (int) trim($item->getTotalSpent(), '-');
            $total_point = $use + $total_point;
        }
        return $total_point;
    }

    /**
     * @return \Magento\Framework\Api\AttributeInterface
     */
    public function getBalance()
    {
        $customer_id = $this->customerSession->getCustomer()->getId();
        $customer = $this->customerFactory->getById($customer_id);

        return $customer->getCustomAttribute('loyalty_balance')->getValue();
    }
}
