<?php

namespace TieuMinh\Loyalty\Block\Adminhtml\Button\Loyalty;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use TieuMinh\Loyalty\Model\LoyaltyRuleFactory;

class GenericButton
{

    /**
     * @var Context
     */
    private $context;
    protected $loyaltyFactory;

    public function __construct(
        Context $context,
        LoyaltyRuleFactory $loyaltyFactory
    ) {
        $this->context = $context;
        $this->loyaltyFactory = $loyaltyFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
    public function getRuleId()
    {
        try {
            $id = $this->context->getRequest()->getParam('rule_id');
            return $id;
        } catch (NoSuchEntityException $e) {
        }
        return null;
    }
}
