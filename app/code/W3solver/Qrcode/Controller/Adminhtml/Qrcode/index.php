<?php
/**
 * W3solver.com
 * @category    W3solver
 * @package     W3solver_Qrcode
 * @Description Get qrcode grid list
 * @author      W3solver
 * @copyright   Copyright (c) 2017 w3solver
 */
namespace W3solver\Qrcode\Controller\Adminhtml\Qrcode;

use \Magento\Framework\View\Result\PageFactory;
use \Magento\Backend\App\Action\Context;

class Index extends \Magento\Backend\App\Action 
{
    /*
    * @param resultPageFactory $resultPageFactory
    */
    protected $resultPageFactory = false;

    public function __construct(
    Context $context, PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    
    /*
    * Function to Initialize the index Controller
    * @return Result Page
    */
    
    public function execute() {
        $this->_setPageData();
        return $this->getResultPage();
    }

    /*
    * Function to get the Result Page
    * @return Result Page
    */
    public function getResultPage() {
        if (is_null($this->_resultPage)) {
            $this->_resultPage = $this->_resultPageFactory->create();
        }
        return $this->_resultPage;
    }
    
    /*
    * Function to set the Data to Result Page
    * @return Current Page Data with Breadcrump
    */
    
    protected function _setPageData() {
        $resultPage = $this->getResultPage();
        $resultPage->setActiveMenu('W3solver_Qrcode::Qrcode');
        $resultPage->getConfig()->getTitle()->prepend((__('QR Code')));

        //Add bread crumb
        $resultPage->addBreadcrumb(__('W3solver'), __('W3solver'));
        $resultPage->addBreadcrumb(__('Qrcode'), __('Manage Blogs'));

        return $this;
    }

}