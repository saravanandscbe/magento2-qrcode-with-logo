<?php
/**
 * W3solver.com
 * @category    W3solver
 * @package     W3solver_Qrcode
 * @Description Qrcode Grid Container
 * @author      W3solver
 * @copyright   Copyright (c) 2017 w3solver
 */
namespace W3solver\Qrcode\Block\Adminhtml;

class Qrcode extends \Magento\Backend\Block\Widget\Grid\Container 
{

    /**
     * constructor
     *
     * @return void
     */
    protected function _construct() {
        $this->_controller = 'adminhtml_qrcode';
        $this->_blockGroup = 'W3solver_Qrcode';
        $this->_headerText = __('QR Code');
        $this->_addButtonLabel = __('Generate QR Code');
        parent::_construct();
    }
}

