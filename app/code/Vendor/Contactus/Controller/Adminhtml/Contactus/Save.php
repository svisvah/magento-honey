<?php

namespace Vendor\Contactus\Controller\Adminhtml\Contactus;

use Magento\Backend\App\Action;
use Vendor\Contactus\Model\Contactus;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Save
 * @package Vendor\Contactus\Controller\Adminhtml\Contactus
 */

class Save extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Vendor_Contactus::save';
    /**
     * @var PostDataProcessor
     */
    protected $dataProcessor;
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;
    /**
     * @var Contactus
     */
    protected $model;

    /**
     * @param Action\Context $context
     * @param PostDataProcessor $dataProcessor
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Action\Context $context,
        PostDataProcessor $dataProcessor,
        Contactus $model,
        DataPersistorInterface $dataPersistor
    ) {
        $this->dataProcessor = $dataProcessor;
        $this->dataPersistor = $dataPersistor;
        $this->model = $model;
        parent::__construct($context);
    }
    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $data = $this->dataProcessor->filter($data);
           
            if (empty($data['id'])) {
                $data['id'] = null;
            }
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $this->model->load($id);
            }
            $this->model->setData($data);
			
            $this->_eventManager->dispatch(
                'mladmincontactus_contactus_prepare_save',
                ['Contactus' => $this->model, 'request' => $this->getRequest()]
            );

            if (!$this->dataProcessor->validate($data)) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $this->model->getId(), '_current' => true]);
            }
            try {
                $this->model->save();
                $this->messageManager->addSuccess(__('You saved the record.'));
                $this->dataPersistor->clear('mladmincontactus');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath(
                        '*/*/edit',
                        ['id' => $this->model->getId(),
                         '_current' => true]
                    );
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the record.'));
            }

            $this->dataPersistor->set('mladmincontactus', $data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}