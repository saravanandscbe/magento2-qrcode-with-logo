<?php
/**
 * W3solver.com
 * @category    W3solver
 * @package     W3solver_Qrcode
 * @Description Block to show qrcode on product detail page
 * @author      W3solver
 * @copyright   Copyright (c) 2017 w3solver
 */
namespace W3solver\Qrcode\Block\Product;

use Magento\Framework\View\Element\Template;
use W3solver\Qrcode\Model\QrcodeListFactory;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;

/**
 * Product Qrcode tab
 */
class Qrcode extends Template 
{

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_qrcodeListFactory;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \W3solver\Qrcode\Model\QrcodeListFactory $qrcodeListFactory
     * @param array $data
     */
    public function __construct(
    Context $context, Registry $registry, QrcodeListFactory $qrcodeListFactory, array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_qrcodeListFactory = $qrcodeListFactory;
        parent::__construct($context, $data);

        $this->setTabTitle();
    }

    /**
     * get Qrode Image
     * @return string
     */
    public function getQrcodeImage() {
        $qrcodeObj = $this->_qrcodeListFactory->create();
        $qrcode = $qrcodeObj->load($this->getProductId(), 'product_id')->getData();

        if (isset($qrcode['image_path'])) {

            $image = $qrcodeObj->getQrcodeUrl($qrcode['image_path']);

            if ($image) {
                return $image;
            }
        }

        return false;
    }

    /**
     * get current product Id
     * @return int
     */
    public function getProductId() {
        $product = $this->_coreRegistry->registry('product');
        return $product ? $product->getId() : null;
    }

    /**
     * Set tab title
     *
     * @return void
     */
    public function setTabTitle() {
        $this->setTitle("Qrcode");
    }

}
