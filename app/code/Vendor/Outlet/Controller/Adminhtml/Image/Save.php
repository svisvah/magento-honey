<?php
/*
 * Vendor_Outlet


 * @category   Turiknox
 * @package    Vendor_Outlet
 * @copyright  Copyright (c) 2017 Turiknox
 * @license    https://github.com/turiknox/magento2-sample-imageuploader/blob/master/LICENSE.md
 * @version    1.0.0
 */
namespace Vendor\Outlet\Controller\Adminhtml\Image;


use Magento\Backend\App\Action\Context;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Message\Manager;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\Filter\Date;
use Magento\Framework\View\Result\PageFactory;
use Vendor\Outlet\Api\ImageRepositoryInterface;
use Vendor\Outlet\Api\Data\ImageInterface;
use Vendor\Outlet\Api\Data\ImageInterfaceFactory;
use Vendor\Outlet\Controller\Adminhtml\Image;
use Vendor\Outlet\Model\Uploader;
use Vendor\Outlet\Model\UploaderPool;


class Save extends Image
{
    /**
     * @var Manager
     */
    protected $messageManager;


    /**
     * @var ImageRepositoryInterface
     */
    protected $imageRepository;


    /**
     * @var ImageInterfaceFactory
     */
    protected $imageFactory;


    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;


    /**
     * @var UploaderPool
     */
    protected $uploaderPool;


    /**
     * Save constructor.
     *
     * @param Registry $registry
     * @param ImageRepositoryInterface $imageRepository
     * @param PageFactory $resultPageFactory
     * @param Date $dateFilter
     * @param Manager $messageManager
     * @param ImageInterfaceFactory $imageFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param UploaderPool $uploaderPool
     * @param Context $context
     */
    public function __construct(
        Registry $registry,
        ImageRepositoryInterface $imageRepository,
        PageFactory $resultPageFactory,
        Date $dateFilter,
        Manager $messageManager,
        ImageInterfaceFactory $imageFactory,
        DataObjectHelper $dataObjectHelper,
        UploaderPool $uploaderPool,
        Context $context
    ) {
        parent::__construct($registry, $imageRepository, $resultPageFactory, $dateFilter, $context);
        $this->messageManager   = $messageManager;
        $this->imageFactory      = $imageFactory;
        $this->imageRepository   = $imageRepository;
        $this->dataObjectHelper  = $dataObjectHelper;
        $this->uploaderPool = $uploaderPool;
    }


    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        $descriptions = $this->getRequest()->getPost('descriptions');

        // Output descriptions for testing
        echo "Descriptons";
        print_r($descriptions);
        


        print_r($data);
        exit();


        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $id = $this->getRequest()->getParam('image_id');
            if ($id) {
                $model = $this->imageRepository->getById($id);
            } else {
                unset($data['image_id']);
                $model = $this->imageFactory->create();
            }


            try {
               


                $image = $this->getUploader('image')->uploadFileAndGetName('image', $data);
                $imageJSON=json_encode($image);
               $data['image']=$imageJSON;
             
               
                $this->dataObjectHelper->populateWithArray($model, $data, ImageInterface::class);
                $this->imageRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved this image.'));
                $this->_getSession()->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['image_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException(
                    $e,
                    __('Something went wrong while saving the image:' . $e->getMessage())
                );
            }


            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['image_id' => $this->getRequest()->getParam('image_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }


    /**
     * @param $type
     * @return Uploader
     * @throws \Exception
     */
    protected function getUploader($type)
    {
        return $this->uploaderPool->getUploader($type);
    }
}





