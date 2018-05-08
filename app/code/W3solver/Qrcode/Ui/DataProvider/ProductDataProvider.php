<?php
/**
 * W3solver.com
 *
 * @category    W3solver
 * @package     W3solver_Qrcode
 * @Description Ui component data provider
 * @author      Arushi
 */

namespace W3solver\Qrcode\Ui\DataProvider;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

/**
 * Class ProductDataProvider
 */
class ProductDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider 
{

    /**
     * Product collection
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected $collection;

    /**
     * @var \Magento\Ui\DataProvider\AddFieldToCollectionInterface[]
     */
    protected $addFieldStrategies;

    /**
     * @var \Magento\Ui\DataProvider\AddFilterToCollectionInterface[]
     */
    protected $addFilterStrategies;
    protected $_resource;

    /**
     * Construct
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param \Magento\Ui\DataProvider\AddFieldToCollectionInterface[] $addFieldStrategies
     * @param \Magento\Ui\DataProvider\AddFilterToCollectionInterface[] $addFilterStrategies
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name, 
        $primaryFieldName, 
        $requestFieldName, 
        CollectionFactory $collectionFactory, 
        \Magento\Framework\App\ResourceConnection $Resource, 
        array $addFieldStrategies = [], 
        array $addFilterStrategies = [], 
        array $meta = [], 
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->addFieldStrategies = $addFieldStrategies;
        $this->addFilterStrategies = $addFilterStrategies;
        $this->_resource = $Resource;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData() {
        if (!$this->getCollection()->isLoaded()) {
            $this->getCollection()->load();
        }

        $this->getCollection()->addAttributeToSelect('name', 'inner');
        
        $this->getCollection()->getSelect()->joinLeft(array('qrcode' => $this->_resource->getTableName('w3solver_qrcode')), 'e.entity_id = qrcode.product_id');

        $collections = $this->getCollection()->getData();

        return [
            'totalRecords' => $this->getCollection()->getSize(),
            'items' => array_values($collections),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function addFilter(\Magento\Framework\Api\Filter $filter) {
        if (isset($this->addFilterStrategies[$filter->getField()])) {
            $this->addFilterStrategies[$filter->getField()]
                    ->addFilter(
                            $this->getCollection(), $filter->getField(), [$filter->getConditionType() => $filter->getValue()]
            );
        } else {
            parent::addFilter($filter);
        }
    }
}

