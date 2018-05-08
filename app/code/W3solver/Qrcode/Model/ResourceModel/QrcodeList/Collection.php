<?php
/**
 * W3solver.com
 * @category    W3solver
 * @package     W3solver_Qrcode
 * @Description Qrcode collection model
 * @author      W3solver
 * @copyright   Copyright (c) 2017 w3solver
 */

namespace W3solver\Qrcode\Model\ResourceModel\QrcodeList;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection 
{

    protected $_idFieldName = 'qrcode_id';

    protected function _construct() {
        $this->_init(
                'W3solver\Qrcode\Model\QrcodeList', 'W3solver\Qrcode\Model\ResourceModel\QrcodeList'
        );
    }

    protected function _initSelect() {
        parent::_initSelect();

        $this->getSelect()->joinRight(
                ['product' => $this->getTable('catalog_product_entity')], 
                'main_table.product_id = product.entity_id', 
                '*' 
        );
    }

}
