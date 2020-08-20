<?php
namespace TieuMinh\Loyalty\Controller\Adminhtml\Loyalty;

use Magento\Backend\App\Action;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use TieuMinh\Loyalty\Model\ResourceModel\LoyaltyForm\CollectionFactory;

class Edit extends Action
{
    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfiginterface;
    /**
     * @var PageFactory
     */
    protected $_pageFactory;
    protected $loyaltyFactory;

    /**
     * GetConfig constructor.
     * @param Action\Context $context
     * @param PageFactory $pageFactory
     * @param ScopeConfigInterface $scopeConfigInterface
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Action\Context $context,
        PageFactory $pageFactory,
        ScopeConfigInterface $scopeConfigInterface,
        CollectionFactory $collectionFactory
    ) {
        $this->_scopeConfiginterface = $scopeConfigInterface;
        $this->_pageFactory = $pageFactory;
        $this->loyaltyFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface|Page
     */
    public function execute()
    {
        $resultPage = $this->_pageFactory->create();

        $resultPage->getConfig()->getTitle()->prepend(__('Edit Loyalty Rule'));

        return $resultPage;
    }
}
