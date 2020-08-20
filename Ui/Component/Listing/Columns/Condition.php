<?php

namespace TieuMinh\Loyalty\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use TieuMinh\Loyalty\Model\LoyaltyFormFactory;

class Condition implements \Magento\Framework\Data\OptionSourceInterface
{
    private $loyaltyFactory;
    private $option;

    /**
     * Category constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param LoyaltyFormFactory $loyaltyRuleFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        LoyaltyFormFactory $loyaltyRuleFactory,
        array $components = [],
        array $data = []
    ) {
        $this->loyaltyFactory = $loyaltyRuleFactory;
    }


    public function toOptionArray()
    {
//        foreach ($this->loyaltyFactory as $key => $label) {
//           $options[] = ['value' => $key, 'label' => $label];
//            $this->option = ['value' => 'a', 'label' => 'b'];
//        }
        $this->option = ['value' => 'a', 'label' => 'b'];
        return $this->option;
    }

}
