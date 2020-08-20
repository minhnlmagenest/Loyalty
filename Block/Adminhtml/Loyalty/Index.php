<?php

namespace TieuMinh\Loyalty\Block\Adminhtml\Loyalty;

use Magento\Backend\Block\Template;

/**
 * Class Index
 * @package TieuMinh\SumUp1\Block\Adminhtml\Post
 */
class Index extends Template
{
    private $request;

    /**
     * Index constructor.
     * @param Template\Context $context
     * @param \Magento\Framework\App\Request\Http $request
     */
    public function __construct(Template\Context $context, \Magento\Framework\App\Request\Http $request)
    {
        parent::__construct($context);
        $this->request = $request;
    }

    /**
     * @return String
     */
    public function returnPost()
    {
        $post = $this->request->getParam('id');

        echo "your post name is $post";
    }
}
