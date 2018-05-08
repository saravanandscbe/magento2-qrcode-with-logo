<?php
/**
 * W3solver.com
 * @category    W3solver
 * @package     W3solver_Qrcode
 * @Description Print product details and qrcode
 * @author      W3solver
 * @copyright   Copyright (c) 2017 w3solver
 */
namespace W3solver\Qrcode\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;

class PrintAction extends \Magento\Backend\App\Action 
{
    /**
     * @param _request $_request
     * @param _filter $_filter
     * @param _pdfFactory $_pdfFactory
     * @param resultForwardFactory $resultForwardFactory
     * @param CollectionFactory $collectionFactory
     */
    protected $resultForwardFactory;
    protected $_request;
    protected $_filter;
    protected $_pdfFactory;
    protected $collectionFactory;

    public function __construct(
    Context $context, \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory, \Magento\Framework\App\Request\Http $request, \Magento\Ui\Component\MassAction\Filter $filter, \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory, \W3solver\Qrcode\Model\PdfFactory $pdfFactory
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        $this->_request = $request;
        $this->_filter = $filter;
        $this->_pdfFactory = $pdfFactory;
        $this->collectionFactory = $collectionFactory;

        parent::__construct($context);
    }

    /**
     * @return  \Magento\Framework\Controller\ResultFactory
     */
    public function execute() {
        if (!$this->_request->isAjax()) {
            $collection = $this->_filter->getCollection($this->collectionFactory->create());

            $this->_pdfFactory->create()->getPdf($collection);

            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('qrcode/index/qrcodelist');

        }
    }

}