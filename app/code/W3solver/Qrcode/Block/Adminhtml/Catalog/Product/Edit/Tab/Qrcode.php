<?php
/**
 * W3solver.com
 * @category    W3solver
 * @package     W3solver_Qrcode
 * @Description Block to add qrcode tab to product edit page
 * @author      W3solver
 * @copyright   Copyright (c) 2017 w3solver
 */
namespace W3solver\Qrcode\Block\Adminhtml\Catalog\Product\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use W3solver\Qrcode\Model\QrcodeListFactory;

class Qrcode extends \Magento\Framework\View\Element\Template {

    protected $_template = 'product/edit/qrcode.phtml';
    protected $_qrcodeListFactory;
    protected $fileName;

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param Magento\Backend\Block\Template\Context
     * @param Magento\Framework\Registry
     * @param W3solver\Qrcode\Model\QrcodeListFactory
     * @param Magento\Store\Model\StoreManagerInterface
     */
    public function __construct(
    Context $context, Registry $registry, QrcodeListFactory $qrcodeListFactory, array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_qrcodeListFactory = $qrcodeListFactory;
        
        parent::__construct($context, $data);
    }

    /**
     * Retrieve product
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct() {
        return $this->_coreRegistry->registry('current_product');
    }

    /**
     * get qrcode image
     * @return string
     */
    public function getQrcodeImage() {
        $product_id = $this->getProduct()->getData('entity_id');
        $qrcode_data = $this->_qrcodeListFactory->create()->load($product_id, 'product_id');
        $image_path_qr = $this->_qrcodeListFactory->create()->getQrcodeDir($qrcode_data['image_path']);

        $this->fileName = $qrcode_data['image_path'];

        return $image_path_qr;
    }

    /**
     * get qrcodeurl
     * @return string
     */
    public function getQrcodeUrl() {
        return $this->_qrcodeListFactory->create()->getQrcodeUrl($this->fileName);
    }

    /**
     * Generate Qrcode
     * @return string
     */
    public function generateQrcodeUrl() {
        return $this->_storeManager->getStore()->getBaseUrl(
                        \Magento\Framework\UrlInterface::DEFAULT_URL_TYPE
                ) . 'admin_4061/qrcode/index/generate/?id=' . $this->getProduct()->getData('entity_id');
    }

}