<?php
 
namespace Vendor\Contactus\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Vendor\Contactus\Block\Adminhtml\Contactus\Grid\Renderer\Action\UrlBuilder;
use Magento\Framework\UrlInterface;

class Actions extends Column
{
    /** Url path */
    const CONTACTUS_URL_PATH_EDIT = 'mladmincontactus/contactus/edit';
    const CONTACTUS_URL_PATH_DELETE = 'mladmincontactus/contactus/delete';

    /** @var UrlBuilder */
    protected $actionUrlBuilder;
    /** @var UrlInterface */
    protected $urlBuilder;

    /**
     * @var string
     */
    private $editUrl;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlBuilder $actionUrlBuilder
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     * @param string $editUrl
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlBuilder $actionUrlBuilder,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        $editUrl = self::CONTACTUS_URL_PATH_EDIT
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->actionUrlBuilder = $actionUrlBuilder;
        $this->editUrl = $editUrl;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['id'])) {
                    $item[$name]['edit'] = [
                        'href' => $this->urlBuilder->getUrl($this->editUrl, ['id' => $item['id']]),
                        'label' => __('Edit')
                    ];
                    $item[$name]['delete'] = [
                        'href' => $this->urlBuilder->getUrl(
                            self::CONTACTUS_URL_PATH_DELETE,
                            ['id' => $item['id']]
                        ),
                       'label' => __('Delete'),
                        'confirm' => [
                            'title' => __('Delete %1',$item['id']),
                            'message' => __('Are you sure you wan\'t to delete a %1 record ?',$item['id'])
                        ]
                    ];
                }
            }
        }
        return $dataSource;
    }
}