<?php
/*
 * Vendor_Outlet

 * @category   Turiknox
 * @package    Vendor_Outlet
 * @copyright  Copyright (c) 2017 Turiknox
 * @license    https://github.com/turiknox/magento2-sample-imageuploader/blob/master/LICENSE.md
 * @version    1.0.0
 */

namespace Vendor\Outlet\Ui\DataProvider\Image\Form\Modifier;

use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Vendor\Outlet\Model\ResourceModel\Image\CollectionFactory;

class ImageData implements ModifierInterface
{
    /**
     * @var \Vendor\Outlet\Model\ResourceModel\Image\Collection
     */
    protected $collection;

    /**
     * @param CollectionFactory $imageCollectionFactory
     */
    public function __construct(
        CollectionFactory $imageCollectionFactory
    ) {
        $this->collection = $imageCollectionFactory->create();
    }

    /**
     * Modify Meta
     *
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta)
    {
        return $meta;
    }
    /** Modify Data
     *
     * @param array $data
     * @return array|mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function modifyData(array $data)
    {
        $items = $this->collection->getItems();
        /** @var $image \Vendor\Outlet\Model\Image */
        foreach ($items as $image) {
            // print_r($image->getData());
            // exit();
            $_data = $image->getData();
            // print_r($_data);
            $_image = json_decode($image->getImage(), true);
            $imagecount = count($_image);

            if (isset($_data['image'])) {

                $_image = json_decode($image->getImage(), true);
                $imagecount = count($_image);

                $imageArr = [];
                $imagebaseurl = $image->getImagBaseUrl();

                $imageArr[0]['name'] = 'Image';
                $i = 0;
                foreach ($_image as $ima) {
                    if ($i <= $imagecount) {
                        $imageArr[$i]['url'] = $imagebaseurl . $ima;
                        $i++;
                    }
                }
                // $imageArr[0]['url'] = $image->getImageUrl();
                $_data['image'] = $imageArr;
            }
            $image->setData($_data);
            $data[$image->getId()] = $_data;
        }

        return $data;
    }
}
