<?php

namespace TieuMinh\Loyalty\Ui\Component\DataProvider;

use TieuMinh\Loyalty\Model\ResourceModel\LoyaltyRule\CollectionFactory;

/**
 * Class DataProvider
 * @package TieuMinh\SumUp1\Ui\Component
 */
class LoyaltyListing extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     */
    protected $loadedData;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return mixed
     */
    public function getLoadedData()
    {
        $this->collection->addFieldToFilter($this->getData());
        return $this->loadedData;
    }
}
