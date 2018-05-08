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

class Sku extends \Magento\Ui\Component\Listing\Columns\Column 
{

    protected $_productFactory;
    protected $_urlBuilder;

    /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory,
     * @param \Magento\Framework\UrlInterface $productFactory,
     * @param array $components
     * @param array $data
     */
    public function __construct(
    \Magento\Framework\View\Element\UiComponent\ContextInterface $context, 
    \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory, 
    \Magento\Catalog\Model\ProductFactory $productFactory, 
    \Magento\Framework\UrlInterface $urlBuilder, 
    array $components = [], 
    array $data = []
    ) {
        $this->_productFactory = $productFactory;
        $this->_urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource) {
        if (isset($dataSource['data']['items'])) {

            foreach ($dataSource['data']['items'] as & $item) {
                if (!empty($item['entity_id'])) {
                    $url = $this->_urlBuilder->getUrl("catalog/product/edit", ['id' => $item['entity_id']]);
                    $item['sku'] = html_entity_decode("<a href=\"$url\" target=\"_blank\">" . $item['sku'] . "</a>");
                }
            }
        }

        return $dataSource;
    }
}
