<?php
/**
 * W3solver.com
 * @category    W3solver
 * @package     W3solver_Qrcode
 * @Description Qrcode resource model
 * @author      W3solver
 * @copyright   Copyright (c) 2017 w3solver
 */
namespace W3solver\Qrcode\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class QrcodeList extends AbstractDb {

    protected function _construct() {
        $this->_init('w3solver_qrcode', 'qrcode_id');
    }

}
