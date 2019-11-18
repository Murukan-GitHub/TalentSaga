<?php

namespace App\Http\Controllers\Backend;

use Input;
use Route;
use View;
use App\Config\BaseConfig;
use Box\Spout\Common\Type;
use Suitcore\Models\SuitModel;
use Suitcore\Repositories\SuitRepository;

/**
 * Extend from SuitCore Backend Controller.
 **/
class BackendController extends \Suitcore\Controllers\BackendController
{
    const UNIT_MEASURE_PERCENTAGE = '%';
    const UNIT_MEASURE_PIXEL = 'px';

    protected $pageIcon;
    protected $columnProperties = [];

    // ACTION
    /**
     * Default Constructor
     * @param  SuitRepository $_baseRepo
     * @param  SuitModel $_baseModel
     * @return void
     */
    public function __construct(SuitRepository $_baseRepo, $_baseModel, $_topLevelRelationModelString = null)
    {
        parent::__construct($_baseRepo, $_baseModel, $_topLevelRelationModelString);

        $this->getColumnProperties();

        app()->setLocale('en'); // force-to-use-english
    }

    protected function getColumnProperties($baseValue = 100, $unit = self::UNIT_MEASURE_PERCENTAGE)
    {
        $size = sizeof($this->baseModel->getBufferedAttributeSettings()) + 1;
        foreach ($this->baseModel->getBufferedAttributeSettings() as $key => $value) {
            $this->columnProperties[$key]['width'] = ($baseValue/$size) . $unit;
        }
        $this->columnProperties['action']['width'] = ($baseValue/$size) . $unit;
    }

    /**
     * Set ID Page
     * Set Icon Page
     *
     * @param string $pageId
     */
    protected function setID($pageId)
    {
        $this->pageId = $pageId;
        $this->pageIcon = $this->getPageIcon();
        View::share('pageId', $this->pageId);
        View::share('pageIcon', $this->pageIcon);
    }

    /**
     * Get Icon Page from Base Config
     *
     * @return string
     */
    private function getPageIcon()
    {
        if (strlen($this->pageId) > 0)
        {
            if (strlen($this->pageId) > 1)
                if (isset(BaseConfig::$data['pageId'][$this->pageId])) {
                    return BaseConfig::$data['pageId'][$this->pageId]['icon'];
                }
                return array_key_exists($this->pageId, BaseConfig::$data['pageId'][(string)$this->pageId[0]]['submenu'])
                    ? BaseConfig::$data['pageId'][$this->pageId[0]]['submenu'][$this->pageId]['icon'] :'';

            return array_key_exists($this->pageId, BaseConfig::$data['pageId'])
                ? BaseConfig::$data['pageId'][$this->pageId]['icon'] : '';
        }
    }
}
