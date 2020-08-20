<?php
namespace TieuMinh\Loyalty\Model;

class CustomerEntity extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('TieuMinh\Loyalty\Model\ResourceModel\CustomerEntity');
    }

}
