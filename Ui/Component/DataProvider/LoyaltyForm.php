<?php

namespace TieuMinh\Loyalty\Ui\Component\DataProvider;

use TieuMinh\Loyalty\Model\ResourceModel\LoyaltyForm\CollectionFactory;

class LoyaltyForm extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $collection;
    protected $loadedData;
    private $dataPersistor;

    /**
     * LoyaltyForm constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $item) {
            $this->loadedData[$item->getId()] = $item->getData();
        }
        return $this->loadedData;
    }
}
