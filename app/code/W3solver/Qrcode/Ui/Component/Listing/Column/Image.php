<?php

/**
 * W3solver.com
 *
 * @category    W3solver
 * @package     W3solver_Qrcode
 * @Description component file to get grid image
 * @author      Arushi
 */

namespace W3solver\Qrcode\Ui\Component\Listing\Column;

use W3solver\Qrcode\Model\QrcodeListFactory;

class Image extends \Magento\Ui\Component\Listing\Columns\Column 
{

    protected $_productFactory;
    protected $_urlBuilder;
    protected $_storeManagerInterface;
    protected $_directoryList;
    protected $_qrcodeFactory;

    const ALT_FIELD = "No Image";

    /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param \Magento\Directory\Api\CountryInformationAcquirerInterface $countryInformation
     * @param array $components
     * @param array $data
     */
    public function __construct(
    \Magento\Framework\View\Element\UiComponent\ContextInterface $context, \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory, \Magento\Catalog\Model\ProductFactory $productFactory, \Magento\Framework\UrlInterface $urlBuilder, \Magento\Store\Model\StoreManagerInterface $storeManagerInterface, \Magento\Framework\App\Filesystem\DirectoryList $directoryList, QrcodeListFactory $qrcodeFactory, array $components = [], array $data = []
    ) {
        $this->_productFactory = $productFactory;
        $this->_urlBuilder = $urlBuilder;
        $this->_storeManagerInterface = $storeManagerInterface;
        $this->_directoryList = $directoryList;

        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource) {
        $base_dir = $this->_directoryList->getPath('base');
        $PNG_DIR = $base_dir . '/pub/media/catalog/product/Qrcode/images/';

        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $url = '';
                if ($item[$fieldName] != '' && file_exists($PNG_DIR . $item[$fieldName])) {
                    $url = $this->_storeManagerInterface->getStore()->getBaseUrl(
                                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                            ) . 'catalog/product/Qrcode/images/' . $item[$fieldName];

                    $item[$fieldName . '_src'] = $url;
                    $item[$fieldName . '_alt'] = $this->getAlt($item) ? : '';
                    $item[$fieldName . '_orig_src'] = $url;
                } else {
                    $item[$fieldName . '_src'] = $url;
                    $item[$fieldName . '_alt'] = 'No Image';
                    $item[$fieldName . '_orig_src'] = $url;
                }
            }
        }
        return $dataSource;
    }
}
