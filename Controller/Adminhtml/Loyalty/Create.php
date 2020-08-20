<?php

namespace TieuMinh\Loyalty\Controller\Adminhtml\Loyalty;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\Result\PageFactory;
use TieuMinh\Loyalty\Model\LoyaltyFormFactory;

/**
 * Class Index
 */
class Create extends Action implements HttpGetActionInterface
{

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var Context
     */
    private $context;
    /**
     * @var LoyaltyFormFactory
     */
    private $collectionFactory;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param LoyaltyFormFactory $loyaltyFormFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        LoyaltyFormFactory $loyaltyFormFactory
    )
    {
        parent::__construct($context);

        $this->resultPageFactory = $resultPageFactory;
        $this->context = $context;
        $this->collectionFactory = $loyaltyFormFactory;
    }

    /**
     * @return ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend('Add New Loyalty Rule');

        return $resultPage;
    }
}
