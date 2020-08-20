<?php
namespace TieuMinh\Loyalty\Controller\MyLoyalty;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Sales\Controller\OrderInterface;

/**
 * Class Index
 * @package TieuMinh\Loyalty\Controller\Customer
 * @author Minhnl
 */
class Index extends Action implements OrderInterface, HttpGetActionInterface
{
    /** @var  Page */
    protected $resultPageFactory;
    /**
     * @var Session
     */
    private $customerSession;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Session $customerSession
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Session $customerSession
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->customerSession = $customerSession;
        parent::__construct($context);
    }
    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        /** @var \Magento\Framework\View\Result\Page $resultPage */

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('My Loyalty'));
        return $resultPage;
    }
}
