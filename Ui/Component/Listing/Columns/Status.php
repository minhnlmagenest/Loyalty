<?php

namespace TieuMinh\Loyalty\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use TieuMinh\Loyalty\Model\LoyaltyRuleFactory;

class Status extends Column
{
    private $loyaltyRuleFactory;

    /**
     * Category constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param LoyaltyRuleFactory $loyaltyRuleFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        LoyaltyRuleFactory $loyaltyRuleFactory,
        array $components = [],
        array $data = []
    ) {
        $this->loyaltyRuleFactory = $loyaltyRuleFactory;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        $data = $dataSource['data']['items'];

        for ($i = 0; $i < count($data); $i++) {
            $status = $data[$i]['status'];
            switch ($status) {
                case 1:
                    $dataSource['data']['items'][$i]['status'] = 'Enable';
                    break;
                default:
                    $dataSource['data']['items'][$i]['status'] = 'Disable';
            }
        }
        return $dataSource;
    }

}
