<?php
/**
 * W3solver.com
 * @category    W3solver
 * @package     W3solver_Qrcode
 * @Description Get Pdf
 * @author      W3solver
 * @copyright   Copyright (c) 2017 w3solver
 */
namespace W3solver\Qrcode\Model;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Catalog\Model\ProductFactory;
use W3solver\Qrcode\Model\QrcodeListFactory;

class Pdf extends \Magento\Framework\DataObject {

    protected $_fileFactory;
    protected $context;
    protected $_productFactory;
    protected $_qrcodeListFactory;


    public function __construct(\Magento\Backend\App\Action\Context $context, 
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory, 
        ProductFactory $productFactory,
        QrcodeListFactory $qrcodeListFactory
        ) {
        $this->context = $context;
        $this->_fileFactory = $fileFactory;
        $this->_productFactory = $productFactory;
        $this->_qrcodeListFactory = $qrcodeListFactory;

        parent::__construct();
    }

    public function getPdf($collection) {

        $pdf = new \Zend_Pdf();



        foreach ($collection as $data) {
            $product_data = $this->_productFactory->create()->load($data->getData('entity_id'))->getData();
            
            $page = $pdf->newPage(\Zend_Pdf_Page::SIZE_A4);
            $pdf->pages[] = $page;
            $page->setFont(\Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA), 20);  //Set Font

            
            $data = $this->_qrcodeListFactory->create()->load($product_data['entity_id'], 'product_id')->getData();
            $image_path = $this->_qrcodeListFactory->create()->getQrcodeDir($data['image_path']);
            if(is_file($image_path) && file_exists($image_path)) {
                $image = \Zend_Pdf_Image::imageWithPath($this->_qrcodeListFactory->create()->getQrcodeDir($data['image_path']));
                $imgWidthPts = $image->getPixelWidth() * 72 / 96;
                $imgHeightPts = $image->getPixelHeight() * 72 / 96;
                $rate = $imgWidthPts / $page->getWidth();
                $imgWidthPts = $imgWidthPts / $rate;
                $imgHeightPts = $imgHeightPts / $rate;
                $pageHeight = $page->getHeight();
                $page->drawImage($image, 250,500, 570, 700);
                $page->drawText('NAME : ' . $product_data['name'], 31, 700);
                $page->drawText('Product QRcode', 200, 800);
                $page->drawRectangle(25, 750, 570, 500, \Zend_Pdf_Page::FILL_METHOD_NON_ZERO_WINDING);
                $page->drawText('SKU : ' . $product_data['sku'], 31, 660);
                $page->drawText('PRICE : ' . $product_data['price'], 31, 620);
            } 
        }

        $date = $this->context->getObjectManager()->get('Magento\Framework\Stdlib\DateTime\DateTime')->date('Y-m-d_H-i-s');

        return $this->_fileFactory->create(
                        'Invoice' . $date . '.pdf', $pdf->render(), DirectoryList::VAR_DIR, 'application/pdf'
        );
    }

}
