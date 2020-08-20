<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace TieuMinh\Loyalty\Quote\Model;

use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\Session;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote\Address\Total;
use TieuMinh\Loyalty\Quote\Api\LoyaltyPointInterface;

/**
 * Coupon management object.
 */
class LoyaltyPointManagement implements LoyaltyPointInterface
{
    /**
     * Quote repository.
     *
     * @var CartRepositoryInterface
     */
    protected $quoteRepository;
    /**
     * @var Customer
     */
    private $customer;
    /**
     * @var CustomerCart
     */
    private $cart;
    /**
     * @var Total
     */
    private $total;
    /**
     * @var Session
     */
    private $customerSession;
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerFactory;

    /**
     * Constructs a coupon read service object.
     *
     * @param CartRepositoryInterface $quoteRepository Quote repository.
     * @param Customer $customer
     * @param CustomerCart $cart
     * @param Total $total
     * @param Session $customerSession
     * @param CustomerRepositoryInterface $customerFactory
     */
    public function __construct(
        CartRepositoryInterface $quoteRepository,
        Customer $customer,
        CustomerCart $cart,
        Total $total,
        Session $customerSession,
        CustomerRepositoryInterface $customerFactory
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->customer = $customer;
        $this->cart = $cart;
        $this->total = $total;
        $this->customerSession = $customerSession;
        $this->customerFactory = $customerFactory;
    }

    /**
     * @inheritDoc
     */
    public function get($cartId)
    {
        /** @var  \Magento\Quote\Model\Quote $quote */
        $quote = $this->quoteRepository->getActive($cartId);
        return $quote->getCouponCode();
    }

    /**
     * @inheritDoc
     */
    public function set($cartId, $point)
    {
        /** @var  \Magento\Quote\Model\Quote $quote */
        $quoteCart = $this->quoteRepository->getActive($cartId);

        if (!$quoteCart->getItemsCount()) {
            throw new NoSuchEntityException(__('The "%1" Cart doesn\'t contain products.', $cartId));
        }
        if (!$quoteCart->getStoreId()) {
            throw new NoSuchEntityException(__('Cart isn\'t assigned to correct store'));
        }
        $quoteCart->getShippingAddress()->setCollectShippingRates(true);

        try {
            $customer_id = $this->customerSession->getCustomer()->getId();
            $customer = $this->customerFactory->getById($customer_id);
            $balance = (int) $customer->getCustomAttribute('loyalty_balance')->getValue();

            if ($balance < $point) {
                throw new CouldNotSaveException(__('You do not have enough point !!! '));
            }

            $quoteCart = $this->cart->getQuote();

            $subTotal = $quoteCart->getData('grand_total');

            if ($subTotal <= $point) {
                $point = $subTotal;
            }
            $point = round($point);

            $quoteCart->setData('point_used', $point);
            $this->quoteRepository->save($quoteCart->collectTotals());
        } catch (LocalizedException $e) {
            throw new CouldNotSaveException(__('The loyalty point couldn\'t be applied: ' . $e->getMessage()), $e);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __("The loyalty point couldn't be applied. Verify the loyalty point and try again."),
                $e
            );
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function remove($cartId)
    {
        /** @var  \Magento\Quote\Model\Quote $quote */
        $quote = $this->quoteRepository->getActive($cartId);
        if (!$quote->getItemsCount()) {
            throw new NoSuchEntityException(__('The "%1" Cart doesn\'t contain products.', $cartId));
        }
        $quote->getShippingAddress()->setCollectShippingRates(true);
        try {
            $quoteCart = $this->cart->getQuote();
            $quoteCart->collectTotals();
            $quoteCart->setData('point_used', 0);
            $this->quoteRepository->save($quoteCart);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(
                __("The point couldn't be canceled. Verify the loyalty point and try again.")
            );
        }
        return true;
    }
}
