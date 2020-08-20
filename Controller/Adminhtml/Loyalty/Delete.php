<?php
namespace TieuMinh\Loyalty\Controller\Adminhtml\Loyalty;

use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Result\PageFactory;
use TieuMinh\Loyalty\Model\LoyaltyCustomerFactory;
use TieuMinh\Loyalty\Model\LoyaltyRuleFactory;
use TieuMinh\Loyalty\Model\LoyaltyWebsiteFactory;

/**
 * Class Delete
 * @package TieuMinh\Loyalty\Controller\Adminhtml\Loyalty
 */
class Delete extends Action
{
    /**
     * @var LoyaltyRuleFactory
     */
    protected $_loyaltyFactory;
    /**
     * @var PageFactory
     */
    protected $_pageFactory;
    /**
     * @var LoyaltyWebsiteFactory
     */
    private $loyaltyWebsiteFactory;
    /**
     * @var Action\Context
     */
    private $context;
    /**
     * @var LoyaltyCustomerFactory
     */
    private $loyaltyCustomerFactory;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param PageFactory $pageFactory
     * @param LoyaltyRuleFactory $loyaltyFactory
     * @param LoyaltyWebsiteFactory $loyaltyWebsiteFactory
     * @param LoyaltyCustomerFactory $loyaltyCustomerFactory
     */
    public function __construct(
        Action\Context $context,
        PageFactory $pageFactory,
        LoyaltyRuleFactory $loyaltyFactory,
        LoyaltyWebsiteFactory $loyaltyWebsiteFactory,
        LoyaltyCustomerFactory $loyaltyCustomerFactory
    ) {
        $this->_loyaltyFactory = $loyaltyFactory;
        $this->_pageFactory = $pageFactory;
        $this->loyaltyWebsiteFactory = $loyaltyWebsiteFactory;
        $this->context = $context;
        $this->loyaltyCustomerFactory = $loyaltyCustomerFactory;

        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Exception
     */
    public function execute()
    {
        $loyaltyId = $this->getRequest()->getParam('rule_id');
        $LoyaltyWebsiteFactory = $this->_loyaltyFactory->create();
        $entity = $LoyaltyWebsiteFactory->load($loyaltyId);
        try {
            $listWebsite = $this->loyaltyWebsiteFactory
                ->create()
                ->getCollection()
                ->addFieldToFilter('loyalty_id', ['eq' => $loyaltyId]);
            foreach ($listWebsite as $website) {
                $website->delete();
            }
            $this->loyaltyCustomerFactory->create()->getCollection()->deleteCustomer($loyaltyId);
            $entity->delete();
            $this->messageManager->addSuccessMessage(__('Record have been Delete.'));
            return $this->_redirect($this->getUrl("loyalty/loyalty/index"));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage(__('Error!.'));
            return $this->_redirect($this->getUrl("loyalty/loyalty/index"));
        }
    }
}
