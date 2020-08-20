<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace TieuMinh\Loyalty\Ui\Component\Listing\Columns;

class SimpleActionOptionsProvider implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'label' => __('Apply as fixed amount'),
                'value' => 'by_fixed'
            ],
            [
                'label' => __('Apply as percent rate'),
                'value' => 'by_percent'
            ],
            [
                'label' => __('Apply by step'),
                'value' => 'by_step'
            ]
        ];
    }
}
