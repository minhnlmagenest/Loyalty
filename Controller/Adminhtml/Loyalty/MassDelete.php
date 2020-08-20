<?php
namespace TieuMinh\Loyalty\Controller\Adminhtml\Loyalty;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use TieuMinh\Loyalty\Model\LoyaltyCustomerFactory;
use TieuMinh\Loyalty\Model\LoyaltyWebsiteFactory;
use TieuMinh\Loyalty\Model\ResourceModel\LoyaltyRule\CollectionFactory;

/**
 * Class MassDelete
 * @package TieuMinh\Loyalty\Controller\Adminhtml\Loyalty
 */
class MassDelete extends Action
{
    protected $filter;

    protected $loyaltyRuleFactory;
    private $_relatedProduct;
    /**
     * @var LoyaltyCustomerFactory
     */
    private $loyaltyCustomerFactory;
    /**
     * @var LoyaltyWebsiteFactory
     */
    private $loyaltyWebsiteFactory;

    /**
     * MassDelete constructor.
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $loyaltyRuleFactory
     * @param LoyaltyCustomerFactory $loyaltyCustomerFactory
     * @param LoyaltyWebsiteFactory $loyaltyWebsiteFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $loyaltyRuleFactory,
        LoyaltyCustomerFactory $loyaltyCustomerFactory,
        LoyaltyWebsiteFactory $loyaltyWebsiteFactory
    ) {
        parent::__construct($context);
        $this->filter            = $filter;
        $this->loyaltyRuleFactory = $loyaltyRuleFactory;
        $this->loyaltyCustomerFactory = $loyaltyCustomerFactory;
        $this->loyaltyWebsiteFactory = $loyaltyWebsiteFactory;
    }

    public function execute()
    {
        $collection     = $this->filter->getCollection($this->loyaltyRuleFactory->create());
        $collectionSize = $collection->getSize();
        $loyaltyCustomer = $this->loyaltyCustomerFactory->create();
        $loyatlyWebsite = $this->loyaltyWebsiteFactory->create();
        try {
            foreach ($collection as $item) {
                $customer_record =  $loyaltyCustomer->getCollection()->addFieldToFilter('loyalty_id', $item->getId());
                foreach ($customer_record as $cr) {
                    $cr->delete();
                }
                $website_record = $loyatlyWebsite->getCollection()->addFieldToFilter('loyalty_id', $item->getId());
                foreach ($website_record as $wr) {
                    $wr->delete();
                }
                $item->delete();
            }
            $this->messageManager->addSuccess(__('A total of %1 post(s) have been deleted.', $collectionSize));
            /**
             * @var Redirect $resultRedirect
             */
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            return $resultRedirect->setPath('loyalty/loyalty/index');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage(__('Can delete %1 items'));
            return $this->_redirect($this->getUrl('loyalty/loyalty/index'));
        }
    }
}
