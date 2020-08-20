<?php

namespace TieuMinh\Loyalty\Block\Adminhtml\Button\Loyalty;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save',
                    'target' => '#tieuminh_loyalty_form',
                    'eventData' => ['action' => 'loyalty/loyalty/save']]
                ],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];
    }
}
