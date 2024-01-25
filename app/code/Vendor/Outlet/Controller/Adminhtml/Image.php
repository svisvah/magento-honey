<?php
/*
 * Vendor_Outlet

 * @category   Turiknox
 * @package    Vendor_Outlet
 * @copyright  Copyright (c) 2017 Turiknox
 * @license    https://github.com/turiknox/magento2-sample-imageuploader/blob/master/LICENSE.md
 * @version    1.0.0
 */
namespace Vendor\Outlet\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Stdlib\DateTime\Filter\Date;
use Vendor\Outlet\Api\ImageRepositoryInterface;

abstract class Image extends Action
{
    /**
     * @var string
     */
    public const ACTION_RESOURCE = 'Vendor_Outlet::image';

    /**
     * @var ImageRepositoryInterface
     */
    protected $imageRepository;

    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Date
     */
    protected $dateFilter;

    /**
     * Sliders constructor.
     *
     * @param Registry $registry
     * @param ImageRepositoryInterface $imageRepository
     * @param PageFactory $resultPageFactory
     * @param Date $dateFilter
     * @param Context $context
     */
    public function __construct(
        Registry $registry,
        ImageRepositoryInterface $imageRepository,
        PageFactory $resultPageFactory,
        Date $dateFilter,
        Context $context
    ) {
        parent::__construct($context);
        $this->coreRegistry         = $registry;
        $this->imageRepository      = $imageRepository;
        $this->resultPageFactory    = $resultPageFactory;
        $this->dateFilter = $dateFilter;
    }
}
