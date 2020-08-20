<?php
namespace TieuMinh\Loyalty\Controller\Adminhtml\Loyalty;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use TieuMinh\Loyalty\Model\ResourceModel\LoyaltyRule\CollectionFactory;

/**
 * Class LoyaltyStatus
 * @package TieuMinh\Loyalty\Controller\Adminhtml\Loyalty
 * @author Minhnl
 */
class LoyaltyStatus extends Action
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $loyaltyRuleCollectionFactory;

    /**
     * LoyaltyStatus constructor.
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {
        $this->filter = $filter;
        $this->loyaltyRuleCollectionFactory = $collectionFactory;
        parent::__construct($context);
    }


    public function execute()
    {
        $loyaltyRuleCollectionFactory = $this->filter->getCollection($this->loyaltyRuleCollectionFactory->create());
        $status = $this->getRequest()->getParam('status');
        $collectionSize = $loyaltyRuleCollectionFactory->getSize();
        foreach ($loyaltyRuleCollectionFactory as $item) {
            $item->setData('status', $status);
            $item->save();
        }
        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been update.', $collectionSize));
        return $this->_redirect($this->getUrl("loyalty/loyalty/index"));
    }
}
