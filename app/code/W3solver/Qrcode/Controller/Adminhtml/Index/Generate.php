<?php
/**
 * W3solver.com
 * @category    W3solver
 * @package     W3solver_Qrcode
 * @Description Generate qrcode controller
 * @author      W3solver
 * @copyright   Copyright (c) 2017 w3solver
 */
namespace W3solver\Qrcode\Controller\Adminhtml\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Catalog\Controller\Adminhtml\Product\Builder;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use W3solver\Qrcode\Model\QrcodeListFactory;
use Magento\Framework\App\Request\Http;
use \Magento\Store\Model\StoreManagerInterface;

class Generate extends \Magento\Backend\App\Action 
{

    /**
     * Massactions filter
     *
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;
    protected $qrcodeFactory;
    protected $_request;
    protected $_storeManagerInterface;

    /**
     * @param Context $context
     * @param Builder $productBuilder
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param Magento\Framework\App\Request\Http $request
     * @param \Magento\Store\Model\StoreManagerInterface $StoreManagerInterface
     * @param W3solver\Qrcode\Model\QrcodeListFactory $qrcodeFactory
     */
    public function __construct(
    Context $context, Builder $productBuilder, Filter $filter, CollectionFactory $collectionFactory, Http $request, StoreManagerInterface $StoreManagerInterface, QrcodeListFactory $qrcodeFactory
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->qrcodeFactory = $qrcodeFactory;
        $this->_request = $request;
        $this->_storeManagerInterface = $StoreManagerInterface;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute() {
        if (!$this->_request->isAjax()) {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $qrCodeGenerated = 0;

            foreach ($collection->getItems() as $product) {

                $this->qrcodeFactory->create()->getQrCode($product->getData());
                $qrCodeGenerated++;
            }
            $this->messageManager->addSuccess(
                    __('A total of %1 record(s) of QR Code(s) is/are generated', $qrCodeGenerated)
            );

            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('qrcode/index/qrcodelist');
        } else {
            $product_id = $this->_request->getParam('id');
            $product_data = $this->collectionFactory->create()->addFieldToFilter('entity_id', $product_id)->getFirstItem()->getData();
            $filename = $this->qrcodeFactory->create()->getQrCode($product_data);

            echo $this->_storeManagerInterface->getStore()->getBaseUrl(
                    \Magento\Framework\UrlInterface::DEFAULT_URL_TYPE
            ) . 'pub/media/catalog/product/Qrcode/images/' . $filename;
        }
    }

}
