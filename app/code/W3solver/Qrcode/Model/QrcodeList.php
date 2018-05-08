<?php

/**
 * W3solver.com
 *
 * @category    W3solver
 * @package     W3solver_Qrcode
 * @Description Qrcodelist model
 * @author      W3solver
 */

namespace W3solver\Qrcode\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\UrlRewrite\Model\UrlRewriteFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Directory\WriteFactory;
use Magento\Framework\Filesystem\Io\File;

require_once("lib/qrcode/qrlib.php");

class QrcodeList extends AbstractModel 
{

    protected $_resourceModel;
    protected $data;
    protected $_urlRewriteFactory;
    protected $_StoreManagerInterface;
    protected $_fileSystem;
    protected $_directoryList;
    protected $_fileWriteFactory;
    protected $_io;
    protected $_curl;
    protected $_resource;

    function __construct(
    \Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\App\ResourceConnection $resource, UrlRewriteFactory $urlRewriteFactory, StoreManagerInterface $StoreManagerInterface, Filesystem $fileSystem, DirectoryList $directoryList, WriteFactory $WriteFactory, File $io, \Magento\Framework\HTTP\Client\Curl $curl
    ) {
        $this->_urlRewriteFactory = $urlRewriteFactory;
        $this->_StoreManagerInterface = $StoreManagerInterface;
        $this->_fileSystem = $fileSystem;
        $this->_directoryList = $directoryList;
        $this->_fileWriteFactory = $WriteFactory;
        $this->_io = $io;
        $this->_curl = $curl;
        $this->_resource = $resource;

        parent::__construct($context, $registry);
    }

    protected function _construct() {

        $this->_init('W3solver\Qrcode\Model\ResourceModel\QrcodeList');
    }

    /*
    * Function to Generate QR Code
    * Calls the generateQrCode function
    * @params ProductData $product_data
    * @return Generated QRCode
    */

    function getQrCode($product_data) {

        $this->data = $product_data;
        $QRTableEntry = $this->_urlRewriteFactory->create()->load('catalog/product/view/id/' . $product_data['entity_id'], 'target_path');
        $URL = $this->_StoreManagerInterface->getStore()->getBaseUrl() . $QRTableEntry['request_path'];
        $sku = $product_data['sku'];
        return $this->generateQrCode($URL, $sku ,$product_data['entity_id']);
    }

    //Function to generate QR code for product
    public function generateQrCode($url, $sku, $FileName) {
        $base_dir = $this->_directoryList->getPath('base');
        $PNG_DIR = $base_dir . '/pub/media/catalog/product/Qrcode/images/';

        /* Check and create directory */
        $this->_io->checkAndCreateFolder($PNG_DIR, 777);

        $productID = $FileName;
        $productUrl = $url;
        $size = '500x500';
        $content = $productUrl;
        $correction = 'L';
        $encoding = 'UTF-8';
        $filename = $FileName . '.png';
        //Generate QR Code Using Google Api
        $rootUrl = "http://chart.googleapis.com/chart?cht=qr&chs=$size&chl=$content&choe=$encoding&chld=$correction";

        //Function to write Image files in Specified Directory
        if (function_exists("curl_init")) {
            $this->_curl->setOptions(array(CURLOPT_CONNECTTIMEOUT => 2, CURLOPT_SSL_VERIFYPEER => 0, CURLOPT_RETURNTRANSFER => 1));
            $this->_curl->get($rootUrl);
            $get_image = $this->_curl->getBody();

            $image_to_fetch = $get_image;
            $image_path_qr = $PNG_DIR . $filename;

            $qr_logo_path = $base_dir . '/pub/media/catalog/product/Qrcode/logo/';
            $this->_io->checkAndCreateFolder($qr_logo_path, 777);            

            $local_image_file = fopen($image_path_qr, 'w');
            fwrite($local_image_file, $image_to_fetch);
            fclose($local_image_file);
            $jpeg = imagecreatefrompng("$qr_logo_path/white.png");
            $png = imagecreatefrompng("$image_path_qr");
            $logoImg = imagecreatefrompng($qr_logo_path. 'qr_logo.png');

            list($width, $height) = getimagesize("$qr_logo_path/white.png");
            list($newwidth, $newheight) = getimagesize($image_path_qr);
            list($logowidth, $logoheight) = getimagesize($qr_logo_path. 'qr_logo.png');
            $out = imagecreatetruecolor($width, $height);

            imagecopyresampled($out, $jpeg, 0, 0, 0, 0,$width, $height, $width, $height);
            imagecopyresampled($out, $png, 0,0, 0, 0,$newwidth, $newheight ,$newwidth, $newheight);
            imagecopyresampled($out, $logoImg, "475", "120", 0, 0, $logowidth, $logoheight ,270, 220);
            
            //Specifying the X and Y Positions
            $xPosition = 500 + (125 - (strlen($sku)*(8)));
            $yPosition = 360;

            //set the Placeholder Image, Font, Color and text(SKU) to the QRCode.
            $fontColor = imagecolorallocate($out, 12, 77, 162);
            $font= "lib/web/fonts/opensans/semibold/opensans-600.ttf";
            $fontsize="18";
            imagettftext($out, $fontsize, 0, $xPosition, $yPosition, $fontColor, $font, $sku);
            header("Content-type:image/png");
            imagepng($out, "$PNG_DIR/$filename");

            $qrcode_data = $this->load($productID, 'product_id');
            if (is_array($qrcode_data)) {
                $this->setData('product_id', $productID);
                $this->setData('image_path', $filename);
                $style = (isset($qrcode_data['style'])) ? $qrcode_data['style'] : 'default';
                $this->setData('style', $style);
                $this->setData('qrcode_id', $qrcode_data['qrcode_id']);
                $this->save();

                return $filename;
            } else {
                $this->setData('product_id', $productID);
                $this->setData('image_path', $filename);
                $this->setData('style', 'default');
                $this->save();

                return $filename;
            }
        }
    }

    function getQrcodeDir($filename = null) {
        $base_dir = $this->_directoryList->getPath('base');
        $PNG_DIR = $base_dir . '/pub/media/catalog/product/Qrcode/images/';

        if (!is_dir($PNG_DIR . $filename) && file_exists($PNG_DIR . $filename)) {

            return $PNG_DIR . $filename;
        }

        return false;
    }

    function getQrcodeUrl($filename = null) {
        $base_url = $this->_StoreManagerInterface->getStore()->getBaseUrl();
        $png_file_path = $base_url . '/pub/media/catalog/product/Qrcode/images/';

        if ($this->getQrcodeDir($filename)) {

            return $png_file_path . $filename;
        }

        return false;
    }

}
