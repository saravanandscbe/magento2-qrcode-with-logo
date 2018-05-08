<?php
/**
 * W3solver.com
 * @category    W3solver
 * @package     W3solver_Qrcode
 * @Description Get qrcode grid list
 * @author      W3solver
 * @copyright   Copyright (c) 2017 w3solver
 */
namespace W3solver\Qrcode\Controller\Adminhtml\Index;

class Qrcodelist extends \Magento\Backend\App\Action 
{
    /**
     * @param _resultPageFactory false
     */
    protected $_resultPageFactory = false;

    public function __construct(
    \Magento\Backend\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
    }
    
    /**
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute() {
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('W3solver_Qrcode::Qrcode_List');
        $resultPage->addBreadcrumb(__('Qrcode'), __('W3solver'));
        $resultPage->addBreadcrumb(__('Qrcode'), __('qrcode'));
        $resultPage->getConfig()->getTitle()->prepend(__('QR Code'));
        return $resultPage;
    }

}