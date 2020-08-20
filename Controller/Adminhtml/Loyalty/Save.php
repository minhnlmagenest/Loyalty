<?php
namespace TieuMinh\Loyalty\Controller\Adminhtml\Loyalty;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\View\Result\PageFactory;
use TieuMinh\Loyalty\Model\LoyaltyCustomerFactory;
use TieuMinh\Loyalty\Model\LoyaltyRuleFactory;
use TieuMinh\Loyalty\Model\LoyaltyWebsiteFactory;

/**
 * Class Save
 * @package TieuMinh\Loyalty\Controller\Adminhtml\Loyalty
 * @author Minhnl
 */
class Save extends Action
{
    /**
     * @var PageFactory
     */
    protected $_pageFactory;
    /**
     * @var LoyaltyRuleFactory
     */
    protected $loyaltyRuleFactory;
    /**
     * @var ManagerInterface
     */
    protected $_messageManager;
    /**
     * @var Context
     */
    private $context;
    /**
     * @var LoyaltyWebsiteFactory
     */
    private $loyaltyWebsiteFactory;
    /**
     * @var LoyaltyCustomerFactory
     */
    private $loyaltyCustomerFactory;

    /**
     * Save constructor.
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param LoyaltyRuleFactory $loyaltyRuleFactory
     * @param ManagerInterface $messageManager
     * @param LoyaltyWebsiteFactory $loyaltyWebsiteFactory
     * @param LoyaltyCustomerFactory $loyaltyCustomerFactory
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        LoyaltyRuleFactory $loyaltyRuleFactory,
        ManagerInterface $messageManager,
        LoyaltyWebsiteFactory $loyaltyWebsiteFactory,
        LoyaltyCustomerFactory $loyaltyCustomerFactory
    ) {
        $this->_pageFactory = $pageFactory;
        $this->loyaltyRuleFactory = $loyaltyRuleFactory;
        $this->_messageManager = $messageManager;
        $this->context = $context;
        $this->loyaltyWebsiteFactory = $loyaltyWebsiteFactory;
        $this->loyaltyCustomerFactory = $loyaltyCustomerFactory;
        return parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface
     * @throws Exception
     */
    public function execute()
    {
        $data = $this->handleData();
        $loyaltyFactory = $this->loyaltyRuleFactory->create();
        $loyaltyWebsiteFactory = $this->loyaltyWebsiteFactory->create();
        $loyaltyCustomerFactory = $this->loyaltyCustomerFactory->create();

        if (isset($data)) {
            $ruleId = $loyaltyFactory->addData($data['loyalty'])->save()->getId();

            $filter_websites = $loyaltyWebsiteFactory->getCollection()->addFieldToFilter('loyalty_id', $ruleId);
            $filter_customers = $loyaltyCustomerFactory->getCollection()->addFieldToFilter('loyalty_id', $ruleId);

            if (!empty($filter_websites && $filter_customers)) {
                foreach ($filter_websites as $item) {
                    $item->delete();
                }
                foreach ($filter_customers as $item) {
                    $item->delete();
                }
            }
            if (!empty($data['website']) && !empty($data['customer'])) {
                foreach ($data['website'] as $item) {
                    $arr = [
                        'loyalty_id' => $ruleId,
                        'website_id' => $item
                    ];
                    $loyaltyWebsiteFactory->setData($arr)->save();
                }
                foreach ($data['customer'] as $item) {
                    $arr = [
                        'loyalty_id' => $ruleId,
                        'customer_group_id' => $item
                    ];
                    $loyaltyCustomerFactory->setData($arr)->save();
                }
            }

            $this->messageManager->addSuccessMessage(__('Record have been Saved.'));
        } else {
            $this->messageManager->addErrorMessage(__('Please fill all field !!!'));
        }

        return $this->_redirect($this->getUrl("loyalty/loyalty/index"));
    }

    /**
     * handle data form
     * @return array
     */
    protected function handleData()
    {
        $result = [];
        $data = $this->getRequest()->getParams();
        $result['website'] = $data["website_ids"];
        $result['customer'] = $data['customer_group_ids'];

        unset(
            $data["key"],
            $data["website_ids"],
            $data["form_key"],
            $data["customer_group_ids"]
        );
        $result['loyalty'] = $data;
        return $result;
    }
}
