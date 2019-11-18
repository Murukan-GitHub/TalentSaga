<?php

namespace Suitcore\Controllers;

use Suitcore\Repositories\SuitRepository;
use Suitcore\Models\SuitModel;
use App, DB, Input, Redirect, Response, Route, File, View, Form, MessageBag, Exception;
use Excel;
use PHPExcel_Style_NumberFormat;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
use Theme;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * This class define standard backend controller for Suitcore Application Instances
 **/
class BackendController extends Controller
{
    /**
     * Constants for partial view
     **/
    const TABLE_SELECTION = "tableselection";
    const TABLE_MENU = "tablemenu";

    // Partial View Related
    public static $partialView = [
        self::TABLE_SELECTION => 'backend.partials.tableselection',
        self::TABLE_MENU => 'backend.partials.tablemenu',
    ]; // default values

    /**
     * Constants for import from excel action
     **/
    const RESETBY = "resetby";
    const REPLACE = "replace";
    const IGNORE = "ignore";

    /**
     * Constants for notifications
     **/
    const NOTICE_NOTIFICATION = "notice";
    const WARNING_NOTIFICATION = "warning";
    const ERROR_NOTIFICATION = "error";
    const OTHER_NOTIFICATION = "other";

    // PROPERTIES
    /**
     * ID of certain admin page
     **/
    public $pageId;
    public $pageTitle;

    // Default Services / Repository
    protected $baseRepository;
    protected $baseModel;
    protected $topLevelRelationModelString;
    protected $routeBaseName;
    protected $routeDefaultIndex;
    protected $viewBaseClosure;
    protected $viewImportExcelClosure;
    protected $viewInstanceName;

    // ACTION
    /**
     * Default Constructor
     * @param  SuitRepository $_baseRepo
     * @param  SuitModel $_baseModel
     * @return void
     */
    public function __construct(SuitRepository $_baseRepo, $_baseModel, $_topLevelRelationModelString = null)
    {
        // set theme (based on APP_BACKEND_THEME environment variable)
        $manualThemeSet = '';
        $availableThemes = array_keys(config('themes.themes'));
        if (request()->has('temporary_theme')) {
            $requestedThemes = request()->get('temporary_theme');
            if (in_array($requestedThemes, $availableThemes)) {
                session()->put('temporary_theme', $requestedThemes);
            }
        }
        if (session()->has('temporary_theme')) {
            $requestedThemes = session()->get('temporary_theme');
            if (in_array($requestedThemes, $availableThemes)) {
                $manualThemeSet = $requestedThemes;
            }
        }
        Theme::set(!empty($manualThemeSet) ? $manualThemeSet : env('APP_BACKEND_THEME', 'default'));
        // set property
        $this->baseRepository = $_baseRepo;
        $this->baseModel = $_baseModel;
        $this->topLevelRelationModelString = $_topLevelRelationModelString;
        $this->routeBaseName = "backend.generic";
        $this->routeDefaultIndex = null; // sample : "backend.generic.index"
        $this->viewBaseClosure = "backend.admin.generic";
        $this->viewImportExcelClosure = $this->viewBaseClosure . ".template"; // sample :  "backend.admin.generic.template"
        $this->viewInstanceName = 'genericObj';
        // default authenticated user (if any)
        if (auth()->user())
        {
            View::share('currentUser', auth()->user());
        }
        // default page ID
        $this->pageId = 1;
        $this->pageTitle = '';
        View::share('pageId', $this->pageId);
        View::share('pageTitle', $this->baseModel->getLabel());
    }

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if ( ! is_null($this->layout))
        {
            $this->layout = View::make($this->layout);
        }
    }

    /**
     * Display list of baseModel
     * @param
     * @return \Illuminate\View\View
     */
    public function getIndex()
    {
        SuitModel::$isFormGeneratorContext = false;
        $baseObj = $this->baseModel;
        session()->put(class_basename($baseObj) . '_selected_ids', []);
        view()->share('title', 'List');
        return view($this->viewBaseClosure . '.index')->with($this->viewInstanceName, $this->baseModel);
    }

    /**
     * Return json list of contentType
     * @param
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postIndexJson() {
        // Parameter
        $baseObj = $this->baseModel;
        $param = Input::all();
        // specific filter if any
        /*
        $baseObj = $this->baseModel;
        $allAttribute = $baseObj->getAttributeSettings();
        $specificFilterFromRequest = [];
        foreach ($allAttribute as $attr => $config) {
            if (isset($config['visible']) &&
                $config['visible'] &&
                isset($param[$attr]) &&
                !empty($param[$attr])) {
                $specificFilterFromRequest[$baseObj->getTable().'.'.$attr] = $param[$attr];
            }
        }
        dd($specificFilterFromRequest);
        */
        // Return
        $customRender = [];
        $selectedIds = session()->get(class_basename($baseObj) . '_selected_ids', []);
        if (Route::has($this->routeBaseName . '.select')) {
            $selectionSetting = [
                'session_token' => csrf_token(),
                'url_selection' => (Route::has($this->routeBaseName . '.select') ? route($this->routeBaseName . '.select',["id"=>"#id#"]) : '')
            ];
            $renderedSelection = View::make(self::$partialView[self::TABLE_SELECTION], ['selectionSetting' => $selectionSetting])->render();
            $customRender['selection'] = $renderedSelection;
            $customRender['selectedIds'] = $selectedIds;
        }
        $menuSetting = [
            'session_token' => csrf_token(),
            'url_detail' => (Route::has($this->routeBaseName . '.show') ? route($this->routeBaseName . '.show',["id"=>"#id#"]) : ''),
            'url_edit' => (Route::has($this->routeBaseName . '.edit') ? route($this->routeBaseName . '.edit',["id"=>"#id#"]) : ''),
            'url_delete' => (Route::has($this->routeBaseName . '.destroy') ? route($this->routeBaseName . '.destroy', ['id' => "#id#"]) : ''),
        ];
        $renderedMenu = View::make(self::$partialView[self::TABLE_MENU], ['menuSetting' => $menuSetting])->render();
        $customRender['menu'] = $renderedMenu;
        session()->put('datatablePageId', $this->pageId);
        return $this->baseRepository->jsonDatatable(
            $param,
            $customRender
            //, $specificFilterFromRequest
        );
    }

    /**
     * Return json list of contentType for select2 purpose
     * @param
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getListJson() {
        // Parameter
        $q = Input::get('q', '');
        // Process
        $baseObj = $this->baseModel;
        $localColumnSearch = $baseObj->getFormattedValueColumn();
        $firstField = null;
        if ($localColumnSearch) {
            foreach ($localColumnSearch as $idx => $field) {
                if (!$firstField) {
                    $baseObj = $baseObj->where($field,"like","%".$q."%");
                    $firstField = $field;
                } else {
                    $baseObj = $baseObj->orWhere($field,"like","%".$q."%");
                }
            }
        }
        if ($firstField) {
            $baseObj = $baseObj->orderBy($firstField, 'asc');
        }
        $baseObj = $baseObj->paginate(10); // ->take(10)->get();
        if ($baseObj) {
            $data['items'] = [];
            foreach ($baseObj as $obj) { 
                $data['items'][] = [
                    "id" => $obj->id,
                    "value" => $obj->id,
                    "text" => $obj->getFormattedValue()
                ];
            }  
            $data['total_count'] = $baseObj->total();
            return Response::json($data);
        }     
        return Response::json([]);
    }

    /**
     * Display baseModel detail
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function getView($id)
    {
        SuitModel::$isFormGeneratorContext = false;
        // Fetch
        view()->share('title', 'Detail');
        $fetchedData = $this->baseRepository->get($id);
        if (!$fetchedData['object']) {
            return abort(404);
        }
        // Return
        $baseObj = $this->beforeView($fetchedData['object']);
        return view($this->viewBaseClosure . '.show')->with($this->viewInstanceName, $baseObj);
    }

    protected function processRequest($request)
    {
        $request = array_filter($request, function($var) {
            return (!empty($var) || ($var == '0') || ($var == '0.0'));
        });
        return $request;
    }

    protected function beforeView($baseObj)
    {
        return $baseObj;
    }

    protected function afterSave($result, $param)
    {
        return $result;
    }

    /**
     * Display baseModel create form
     * @param
     * @return \Illuminate\View\View
     */
    public function getCreate()
    {
        view()->share('title', '+ New');
        $param = Input::all();
        $baseObj = $this->baseModel;
        $allAttribute = $baseObj->getAttributeSettings();
        foreach ($allAttribute as $key => $cnf) {
            if ((isset($cnf['initiated']) && !$cnf['initiated']) || old($key)) {
                $baseObj->{$key} = old($key);
            }
        }
        return view($this->viewBaseClosure . '.create')->with($this->viewInstanceName, $baseObj);
    }

    /**
     * Save entry data from baseModel create form
     * @param
     * @return \Illuminate\Support\Facades\Redirect
     */
    public function postCreate() {
        // Save
        $param = $this->processRequest(Input::all());
        $baseObj = $this->baseModel;
        $result = $this->baseRepository->create($param, $baseObj);
        $result = $this->afterSave($result, $param);
        // Return
        if ($result) {
            $this->showNotification(self::NOTICE_NOTIFICATION, ucwords(trans('label.model.created_alert_title', ['model' => $baseObj->getTranslationLabel()])), trans('label.model.created_alert_message', ['model' => $baseObj->getTranslationLabel()]));
            if (Route::has($this->routeBaseName . '.show'))
                return Redirect::route($this->routeBaseName . '.show', ['id'=>$baseObj->id]);
            else {
                return $this->returnToRootIndex($baseObj);
            }
        }
        return Redirect::route($this->routeBaseName . '.create')->with('errors', $baseObj->errors)->withInput($param);
    }

    /**
     * Display baseModel update form
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function getUpdate($id) {
        view()->share('title', 'Update');
        $baseObj = $this->baseModel->find($id);
        if ($baseObj == null) return App::abort(404);
        $baseObj = $this->beforeView($baseObj);
        return view($this->viewBaseClosure . '.edit')->with($this->viewInstanceName, $baseObj);
    }

    /**
     * Save entry data from baseModel update form
     * @param  int $id
     * @return \Illuminate\Support\Facades\Redirect
     */
    public function postUpdate($id) {
        // Save
        $param = $this->processRequest(Input::all());
        $baseObj = $this->baseModel;
        $result = $this->baseRepository->update($id, $param, $baseObj);
        $result = $this->afterSave($result, $param);
        // Return
        if ($result) {
            $this->showNotification(self::NOTICE_NOTIFICATION, ucwords(trans('label.model.updated_alert_title', ['model' => $baseObj->getTranslationLabel()])), ucfirst(trans('label.model.updated_alert_message', ['model' => $baseObj->getTranslationLabel()])));
            if (Route::has($this->routeBaseName . '.show'))
                return Redirect::route($this->routeBaseName . '.show', ['id'=>$id]);
            else {
                return $this->returnToRootIndex($baseObj);
            }
        }
        if ($baseObj == null) App::abort(404);
        return Redirect::route($this->routeBaseName . '.update', ['id'=>$id])->with('errors', $baseObj->errors)->withInput($param);
    }

    /**
     * Select baseModel data
     * @param int $id
     * @return \Illuminate\Support\Facades\Redirect
     */
    public function postSelect($id) {
        // Delete
        $baseObj = $this->baseModel->find($id);
        if ($baseObj) {
            $isSelected = request()->get('is_selected', 1);
            $selectedIds = session()->get(class_basename($baseObj) . '_selected_ids', []);
            if (!is_array($selectedIds)) $selectedIds = [];
            if ($isSelected) {
                array_push($selectedIds, $id);
            } else {
                // is unselected
                if (in_array($id, $selectedIds) == true) {
                    foreach ($selectedIds as $key=>$value) {
                        if ($value == $id) unset($selectedIds[$key]);
                    }
                }
            }
            session()->put(class_basename($baseObj) . '_selected_ids', $selectedIds);
            // dd($selectedIds);
            return response()->json([
                'result' => true
            ]);
        }
        return response()->json([
            'result' => false
        ]);
    }

    /**
     * Delete baseModel data
     * @param int $id
     * @return \Illuminate\Support\Facades\Redirect
     */
    public function postDelete($id) {
        // Delete
        $baseObj = $this->baseModel;
        $result = $this->baseRepository->delete($id, $baseObj);
        // Return
        if ($result) {
            $this->showNotification(self::NOTICE_NOTIFICATION, ucwords(trans('label.model.deleted_alert_title', ['model' => $baseObj->getTranslationLabel()])), ucfirst(trans('label.model.deleted_alert_message', ['model' => $baseObj->getTranslationLabel()])));
        } else {
            $this->showNotification(self::ERROR_NOTIFICATION,  ucwords(trans('label.model.cannot_deleted_alert_title', ['model' => $baseObj->getTranslationLabel()])), ucfirst(trans('label.model.cannot_deleted_alert_message', ['model' => $baseObj->getTranslationLabel()])));
        }
        return $this->returnToRootIndex($baseObj);
    }

    /**
     * Delete Many baseModel data
     * @param int $id
     * @return \Illuminate\Support\Facades\Redirect
     */
    public function postDeleteAll() {
        // Delete
        $baseObj = $this->baseModel;
        $deletedIds = session()->get(class_basename($baseObj) . '_selected_ids', []);
        $result = false;
        if (is_array($deletedIds) && count($deletedIds) > 0) {
            $result = $this->baseRepository->deleteAll($deletedIds);
        }
        // Return
        if ($result) {
            session()->put(class_basename($baseObj) . '_selected_ids', []);
            $this->showNotification(self::NOTICE_NOTIFICATION, ucwords(trans('label.model.deleted_alert_title', ['model' => $baseObj->getTranslationLabel()])), ucfirst(trans('label.model.deleted_alert_message', ['model' => $baseObj->getTranslationLabel()])));
        } else {
            $this->showNotification(self::ERROR_NOTIFICATION,  ucwords(trans('label.model.cannot_deleted_alert_title', ['model' => $baseObj->getTranslationLabel()])), ucfirst(trans('label.model.cannot_deleted_alert_message', ['model' => $baseObj->getTranslationLabel()])));
        }
        return $this->returnToRootIndex($baseObj);
    }

    /**
     * Index return route
     * @param int $id
     * @return \Illuminate\Support\Facades\Redirect
     */
    protected function returnToRootIndex($baseObj) {
        if (!empty($this->routeDefaultIndex)) {
            if (endsWith('.show', $this->routeDefaultIndex) &&
                !empty($this->topLevelRelationModelString)) {
                // custom detail return
                $topLevelRelationObject = $baseObj->getAttribute($this->topLevelRelationModelString);
                if ($topLevelRelationObject) {
                    return Redirect::route($this->routeDefaultIndex, ['id'=>$topLevelRelationObject->id]);
                }
            }
            return Redirect::route($this->routeDefaultIndex);
        }
        return Redirect::route($this->routeBaseName . '.index');
    }

    public function exportToExcel($specificFilter = null)
    {
        if ($this->baseModel) {
            $baseObj = $this->baseModel;
            // BOX SPOUT WAY
            $fileName = str_replace(" ", "", $baseObj->getLabel())  . 'Data'.date('dmYhis').'.xlsx';
            $headers = [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename='. $fileName,
            ];

            $callback = function () use ($baseObj, $specificFilter) {
                $writer = WriterFactory::create(Type::XLSX); // available :  XLSX / CSV / ODS

                $writer->openToFile("php://output"); // file or phpStream
                // $writer->openToBrowser($fileName); // stream data directly to the browser

                // Customizing the sheet name when writing
                $sheet = $writer->getCurrentSheet();
                $sheet->setName('Data');

                $columnHeader = [];
                foreach ($baseObj->getBufferedAttributeSettings() as $key=>$val) {
                    if ( $val['visible'] ) {
                        $columnHeader[] = $val['label'];
                    }
                }
                $writer->addRow($columnHeader); // Header - add a row at a time
                $objectList = $baseObj->select($baseObj->getTable().".*");
                if ($specificFilter != null && count($specificFilter) > 0) {
                    $objectList->where(function($query) use ($specificFilter) {
                        foreach($specificFilter as $key=>$val) {
                            $query->where($key, "=", $val);
                        }
                    });
                }
                $objectList->chunk(100, function($objects) use ($writer, $specificFilter) {
                    // init data
                    $data = [];
                    foreach ($objects as $object) {
                        $tmpRow = [];
                        foreach ($object->getBufferedAttributeSettings() as $attrName=>$attrSettings) {
                            if ($attrSettings['visible']) {
                                if ($specificFilter == null ||
                                    !is_array($specificFilter) ||
                                    !isset($specificFilter[$attrName])) {
                                    $tmpRow[] = $object->renderRawAttribute($attrName);
                                }
                            }
                        }
                        $data[] = $tmpRow;
                    }
                    // Write Rows
                    $writer->addRows($data); // add multiple rows at a time
                });
                // Close Writer
                $writer->close();
            };
            // return
            return Response::stream($callback, 200, $headers);

            /*
            // MAATWEBSITE EXCEL WAY
            $currentMaxTimeLimit = ini_get('max_execution_time');
            ini_set('max_execution_time', 0);
            Excel::create(str_replace(" ", "", $baseObj->getLabel())  . 'Data', function ($excel) {
                // Set sheets
                $excel->sheet('Data', function ($sheet) {
                    $idx = 1;
                    $objectList = $baseObj->select($baseObj->getTable().".*");
                    if ($specificFilter != null && count($specificFilter) > 0) {
                        $objectList->where(function($query) use ($specificFilter) {
                            foreach($specificFilter as $key=>$val) {
                                $query->where($key, "=", $val);
                            }
                        });
                    }
                    $objectList->chunk(100, function($objects) use ($idx, $sheet) {
                        // reinit
                        $data = [];
                        // header if needed
                        if ($idx == 1 ){
                            $data[0] = [];
                            foreach ($baseObj->getBufferedAttributeSettings() as $key=>$val) {
                                if ( $val['visible'] ) {
                                    $data[0][] = $val['label'];
                                }
                            }
                        }
                        // write data
                        foreach ($objects as $object) {
                            $tmpRow = [];
                            foreach ($object->getBufferedAttributeSettings() as $attrName=>$attrSettings) {
                                if ($attrSettings['visible']) {
                                    if ($specificFilter == null ||
                                        !is_array($specificFilter) ||
                                        !isset($specificFilter[$attrName])) {
                                        $tmpRow[] = $object->renderRawAttribute($attrName);
                                    }
                                }
                            }
                            $data[] = $tmpRow;
                        }
                        // Sheet manipulation
                        $sheet->fromArray($data, null, 'A' . $idx, false, false);
                        // increment index
                        $idx += ($idx == 1 ? 101 : 100);
                    });

                    // Sheet further manipulation
                    // $sheet->fromArray($data, null, 'A1', false, false);
                    $sheet->cells('A1:Z1', function ($cells) {
                        $cells->setFontWeight('bold');
                    });
                    $sheet->freezeFirstRow();

                });
            })->export('xlsx'); //*.xls only support until 65.536 rows
            ini_set('max_execution_time', $currentMaxTimeLimit);
            */
        }
    }

    /**
     * Route Implementation to import from excel page
     * @return Illuminate\View\View
     */
    public function template()
    {
        $baseObj = $this->baseModel;
        return view($this->viewImportExcelClosure)->with($this->viewInstanceName, $baseObj);
    }

    /**
     * Route Implementation to download template
     * @return ExcelFile
     */
    public function downloadTemplate()
    {
        // Generate data for Excel file.
        $sourceData = $this->getTemplateAttributes();
        $sourceDataSettings = $this->baseModel->getBufferedAttributeSettings();
        // Create an Excel file
        Excel::create($this->baseModel->getLabel() . ' Template', function ($excel) use ($sourceData, $sourceDataSettings) {
            $excel->sheet('Data', function ($sheet) use ($sourceData, $sourceDataSettings) {
                $excelSourceDataFormat = [];
                $parentIdx = -1;
                $idx = 0;
                $ranges = range('A', 'Z');
                $rangePrefix = "";
                foreach ($sourceData as $key => $value) {
                    $columnIdx = $rangePrefix.$ranges[$idx]; // handle A to ZZ
                    $excelSourceDataFormat[$columnIdx] = PHPExcel_Style_NumberFormat::FORMAT_TEXT;
                    if (isset($sourceDataSettings[$key]) &&
                        isset($sourceDataSettings[$key]['type'])) {
                        if (($sourceDataSettings[$key]['type'] == SuitModel::TYPE_NUMERIC ||
                             $sourceDataSettings[$key]['type'] == SuitModel::TYPE_FLOAT) &&
                            ( (isset($sourceDataSettings[$key]['options']) && !is_array($sourceDataSettings[$key]['options'])) || !isset($sourceDataSettings[$key]['options'])) ) {
                            $excelSourceDataFormat[$columnIdx] = PHPExcel_Style_NumberFormat::FORMAT_NUMBER;
                        } else if ($sourceDataSettings[$key]['type'] == SuitModel::TYPE_DATETIME ||
                            $sourceDataSettings[$key]['type'] == SuitModel::TYPE_DATE ||
                            $sourceDataSettings[$key]['type'] == SuitModel::TYPE_TIME) {
                            $excelSourceDataFormat[$columnIdx] = PHPExcel_Style_NumberFormat::FORMAT_DATE_DATETIME; // d/m/Y H:i
                        }
                    }
                    $idx++;
                    if ($idx >= 26) {
                        $parentIdx++;
                        if ($parentIdx >= 26) break; // end of column index in excel
                        $idx = 0;
                        if ($parentIdx >= 0) $rangePrefix = $ranges[$parentIdx];
                    }
                }
                $sheet->fromArray($sourceData);
                $sheet->setColumnFormat($excelSourceDataFormat);
            });
            unset($sourceData);
            unset($sourceDataSettings);
        })->export('xlsx');
    }

    /**
     * Helper function to return template attribuets for imported baseModel
     * @return ExcelFile
     */
    public function getTemplateAttributes()
    {
        $attributes = [];
        $sourceData = $this->baseModel->getBufferedAttributeSettings();
        foreach ($sourceData as $key => $value) {
            if ( isset($value['formdisplay']) &&
                 $value['formdisplay'] &&
                 ( (isset($value['initiated']) && !$value['initiated']) ||
                    !isset($value['initiated']))
               ) {
                $attributes[$key] = ucwords(str_replace("_", " ", strtolower($key))); // $value['label'];
            }
        }
        return $attributes;
    }

    /**
     * Route Implementation to process importing from excel
     * @return Redirect
     */
    public function importFromTemplate()
    {
        $method = Input::get('method');
        $templateAttributes = $this->getTemplateAttributes();
        $modelAttrSettings = $this->baseModel->getBufferedAttributeSettings();

        $excel = $this->getUploadedFile();
        if ($excel == null || $excel->first() == null) {
            return redirect()->back()->with('error', 'Error template! Please check if header column is exist and data is available!');
        }
        $tableHeader = array_keys($excel->first()->toArray());

        $checkResult = true;
        foreach ($tableHeader as $key => $value) {
            if (!in_array($value, array_keys($templateAttributes))) {
                $checkResult = false;
            }
        }
        if ($checkResult == false)
            return redirect()->back()->with('error', 'Your file should contain the following column: ' . implode(', ', $templateAttributes));

        DB::beginTransaction();
        try {
            $rowNumber = 0;
            $datas = $excel->chunk(50);
            foreach ($datas as $data) {
                foreach ($data as $row) {
                    $rowNumber++;
                    $objectInstance = $this->baseModel;
                    if (isset($row[$this->baseModel->getImportExcelKeyBaseName()])) {
                        $objectInstance = $this->baseModel->where($this->baseModel->getImportExcelKeyBaseName(), $row[$this->baseModel->getImportExcelKeyBaseName()])->first();
                    }

                    // datetime, date and time format validation
                    foreach($row as $key=>$val) {
                        if (is_array($modelAttrSettings) &&
                            isset($modelAttrSettings[$key]) &&
                            isset($modelAttrSettings[$key]['type'])) {
                            if ($modelAttrSettings[$key]['type'] == SuitModel::TYPE_DATETIME ||
                                $modelAttrSettings[$key]['type'] == SuitModel::TYPE_DATE ||
                                $modelAttrSettings[$key]['type'] == SuitModel::TYPE_TIME) {
                                $dateConfirm = null;
                                try {
                                    // From Excel : m/d/Y H:i
                                    // Already a Carbon
                                    $dateConfirm = $val->format('Y-m-d H:i:s');
                                    $row[$key] = $dateConfirm;
                                    // $dateConfirm = Carbon::createFromFormat('d/m/Y H:i', $val);
                                } catch (Exception $e) { $dateConfirm = null; }
                                if ($dateConfirm == null || $dateConfirm == false) {
                                    return back()->with('error', 'Error in row ' . $rowNumber . ': Date format does not match');
                                }
                            }
                        }
                    }

                    // Process...
                    if ($objectInstance && $objectInstance->id > 0) {
                        if ($method == self::REPLACE) {
                            // if data virtual account confirmation exist, update
                            $result = $this->baseRepository->update($objectInstance->id, $row->toArray(), $objectInstance);
                        }
                        // else IGNORED
                    } else {
                        // create new record
                        $baseObj = $this->baseModel;
                        $result = $this->baseRepository->create($row->toArray(), $baseObj);
                    }
                }
            }
        } catch (\PDOException $e) {
            DB::rollback();
            return redirect()->back()
                 ->with('error', 'Error in row ' . $rowNumber . ': ' . $e->errorInfo[2] . '.');
        }
        DB::commit();
        return back()->with('success', 'New records successfully imported.');
    }

    /**
     * Route Implementation to read uploaded template
     * @return ExcelFile
     */
    protected function getUploadedFile()
    {
        $input = Input::all();
        $destinationPath = 'files/importexcel/'.strtolower(get_class($this->baseModel)); // upload path
        $file = array_get($input, 'file_url');
        if (is_null($file)) {
            Session::flash('error', 'Please selec files that want to be imported!');
            return back();
        }
        $extension = $file->getClientOriginalExtension();
        // RENAME THE UPLOAD WITH RANDOM NUMBER
        $fileName = rand(11111, 99999) . '.' . $extension;
        // MOVE THE UPLOADED FILES TO THE DESTINATION DIRECTORY
        $isUploadSuccess = $file->move($destinationPath, $fileName);
        //get by sheet
        return Excel::selectSheetsByIndex(0)->load($destinationPath . '/' . $fileName, function () {})->get();
    }

    /**
     * Show notification to users
     * @param int $type
     * @param int $title
     * @param int $message
     * @return void
     */
    protected function showNotification($type, $title, $message) {
        if (!session()->has('webNotification')) {
            session()->put('webNotification', []);
        }
        $webNotification = session()->get('webNotification');
        $webNotification[] = ['type' => $type, 'title' => $title, 'message' => $message];
        session()->put('webNotification', $webNotification);
    }

    protected function setView($key, $value = null)
    {
        view()->share($key, $value);
    }

    public function __call($method, $parameters)
    {
        if (Str::startsWith($method, 'set')) {
            $key = Str::camel(Str::replaceFirst('set', '', $method));
            return call_user_func_array([$this, 'setView'], [$key, $parameters[0]]);
        }

        return parent::__call($method, $parameters);
    }
}
