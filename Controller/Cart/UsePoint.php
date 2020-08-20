<?php
namespace TieuMinh\Loyalty\Controller\Cart;

use Magento\Checkout\Controller\Cart;
use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Checkout\Model\Session;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;

class UsePoint extends Cart implements HttpPostActionInterface
{
    /**
     * Sales quote repository
     *
     * @var CartRepositoryInterface
     */
    protected $quoteRepository;
    /**
     * @var Context
     */
    private $context;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var Session
     */
    private $checkoutSession;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var Validator
     */
    private $formKeyValidator;
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerFactory;

    /**
     * UsePoint constructor.
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param Session $checkoutSession
     * @param StoreManagerInterface $storeManager
     * @param Validator $formKeyValidator
     * @param CartRepositoryInterface $quoteRepository
     * @param CustomerCart $cart
     * @param \Magento\Customer\Model\Session $customerSession
     * @param CustomerRepositoryInterface $customerFactory
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        Session $checkoutSession,
        StoreManagerInterface $storeManager,
        Validator $formKeyValidator,
        CartRepositoryInterface $quoteRepository,
        CustomerCart $cart,
        \Magento\Customer\Model\Session $customerSession,
        CustomerRepositoryInterface $customerFactory
    ) {
        parent::__construct($context, $scopeConfig, $checkoutSession, $storeManager, $formKeyValidator, $cart);
        $this->context = $context;
        $this->scopeConfig = $scopeConfig;
        $this->checkoutSession = $checkoutSession;
        $this->storeManager = $storeManager;
        $this->formKeyValidator = $formKeyValidator;
        $this->cart = $cart;
        $this->quoteRepository = $quoteRepository;
        $this->customerSession = $customerSession;
        $this->customerFactory = $customerFactory;
    }

    public function execute()
    {
        $point_exchange = $this->getRequest()->getParam('remove') == 1
            ? ''
            : trim($this->getRequest()->getParam('point'));
        try {
            $cartQuote = $this->cart->getQuote();
            $itemsCount = $cartQuote->getItemsCount();

            $customer_id = $this->customerSession->getCustomer()->getId();
            $customer = $this->customerFactory->getById($customer_id);
            $balance = (int) $customer->getCustomAttribute('loyalty_balance')->getValue();
            if ($balance < $point_exchange) {
                $this->messageManager->addErrorMessage('you do not have enough points !!!');
                return $this->_goBack();
            }
            if (!$itemsCount) {
                $this->messageManager->addErrorMessage('your cart is empty !!!');
                return $this->_goBack();
            } else {
                $grand_total = $cartQuote->getBaseGrandTotal();
                if ($grand_total <= $point_exchange) {
                    $point_exchange = $grand_total;
                }
                $point_exchange = round($point_exchange);
                $cartQuote->collectTotals();
                $cartQuote->setData('point_used', $point_exchange);
                $this->quoteRepository->save($cartQuote);
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        return $this->_goBack();
    }
}
