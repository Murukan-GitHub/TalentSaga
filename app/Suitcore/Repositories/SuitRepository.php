<?php

namespace Suitcore\Repositories;

use Carbon\Carbon;
use DB;
use Illuminate\Pagination\Paginator;
use Intervention\Image\Facades\Image;
use ReflectionClass;
use Response;
use Suitcore\Datatable\DatatableTrait;
use Suitcore\Models\SuitModel;
use Suitcore\Models\SuitTranslation;
use Suitcore\Repositories\Contract\SuitRepositoryContract;
use Upload;
use Exception;

class SuitRepository implements SuitRepositoryContract
{
    use DatatableTrait;

    const FETCH_ALL = -1;
    const DEFAULT_FETCH = 10;

    protected $mainModel = null;
    protected $dependencies = [];

    // METHODS
    /**
     * Display object list as JSON text that suitable for datatable frontend needs
     * @param  array $param
     * @return string jsonOutput
     **/
    public function jsonDatatable($param, $columnFormatted = null, $specificFilter = null, $optionalFilter = null, $columnException = null) {
        // Selection Column
        SuitModel::$isFormGeneratorContext = false;
        $tmpObject = ($this->mainModel ? $this->mainModel : new SuitModel);
        $tmpObject->showAllOptions = true;
        $object = $tmpObject->select($tmpObject->getTable().".*");
        $datatableSelection = [];
        $datatableExtendedSelection = [];
        $datatableKeyIndex = [];
        $datatableColumnRelationObject = [];
        $datatableColumnOptions = [];
        $datatableDateOptions = [];
        $columFilterIdx = 0;
        foreach ($tmpObject->getBufferedAttributeSettings() as $attrName=>$attrSettings) {
            /*
            if ($attrSettings['visible']) {
                if ($specificFilter == null ||
                    !is_array($specificFilter) ||
                    !isset($specificFilter[$attrName])) {
                    $datatableSelection[] = $tmpObject->getTable().'.'.$attrName;
                    $columFilterIdx++;
                }
            }
            */
            if (isset($attrSettings['visible']) &&
                $attrSettings['visible'] &&
                ($specificFilter == null ||
                 !is_array($specificFilter) ||
                 !isset($specificFilter[$attrName])) && 
                ($columnException == null ||
                 !is_array($columnException) ||
                 !in_array($attrName, $columnException))) {
                // selection
                $datatableSelection[$columFilterIdx] = $tmpObject->getTable().'.'.$attrName;
                // filter
                if (isset($attrSettings['filterable']) &&
                    $attrSettings['filterable']) {
                    $datatableKeyIndex[$columFilterIdx] = $attrName;
                    $datatableColumnRelationObject[$columFilterIdx] = isset($attrSettings['relation']) ? $attrSettings['relation'] : null;
                    $datatableColumnOptions[$columFilterIdx] = isset($attrSettings['options']) ? $attrSettings['options'] : null;
                    $datatableDateOptions[$columFilterIdx] = in_array($attrSettings['type'], [SuitModel::TYPE_DATETIME, SuitModel::TYPE_DATE]);
                }
                // extended selection
                if (isset($attrSettings['relation']) &&
                    $attrSettings['relation']) {
                    $datatableExtendedSelection[$attrName] = $tmpObject->getAttribute($attrSettings['relation'].'__object');
                    if (!$datatableExtendedSelection[$attrName]) unset($datatableExtendedSelection[$attrSettings['relation']]);
                }
                // next field
                $columFilterIdx++;
            }
        }

        // YADCF Column Specific Filter
        $specificDefinition = [];
        $tmpValue = "";
        $columnFilter = $param["columns"];
        foreach ($columnFilter as $key => $element) {
            if (isset($element["search"]["value"]) && 
                !empty($element["search"]["value"]) &&
                isset($datatableSelection[$key]) ) {
                // Specific Column Filter
                $tmpValue = $element["search"]["value"];
                if (isset($datatableColumnRelationObject[$key]) &&
                    $datatableColumnRelationObject[$key]) {
                    $objProperty = $datatableColumnRelationObject[$key]."__object";
                    $relatedObject = $tmpObject->$objProperty;
                    if ($relatedObject) {
                        $relatedObject = $relatedObject->where($relatedObject->getUniqueValueColumn(),"=",$tmpValue)->first();
                        if ($relatedObject) {
                            $specificDefinition[$datatableSelection[$key]] = $relatedObject->id;
                        }
                    }
                } else if (isset($datatableColumnOptions[$key]) &&
                           is_array($datatableColumnOptions[$key]) && 
                           count($datatableColumnOptions[$key]) > 0) {
                    $optionKey = array_search($tmpValue, $datatableColumnOptions[$key]);
                    $specificDefinition[$datatableSelection[$key]] = $optionKey;
                } else if ( isset( $datatableDateOptions[$key] ) &&
                    $datatableDateOptions[$key] ) {
                    $specificDefinition[$datatableSelection[$key]] = $tmpValue;
                }
            }
        }

        if ($specificFilter && is_array($specificFilter)) $specificDefinition = array_merge($specificDefinition, $specificFilter);

        // Process Datatable Request
        $jsonSource = $this->preprocessDatatablesJson($object,
                             $datatableSelection,
                             $specificDefinition,
                             $optionalFilter,
                             $tmpObject->_defaultOrder,
                             $tmpObject->_defaultOrderDir,
                             $datatableExtendedSelection);

        // Complete json, set data (view rendered) and unset rawdata (model rendered)
        $jsonSource['data'] = array();
        foreach($jsonSource['rawdata'] as $obj) {
            $tmpRow = [];
            // Selection Tools
            $selectedIds = (is_array($columnFormatted) && isset($columnFormatted['selectedIds']) && is_array($columnFormatted['selectedIds']) ? $columnFormatted['selectedIds'] : []);
            if (is_array($columnFormatted) && isset($columnFormatted['selection'])) {
                try {
                    if (empty($columnFormatted['selection'])) {
                        $tmpRow[] = '-';
                    }
                    $selectionElmt = str_replace('#id#', $obj->getAttribute('id'), $columnFormatted['selection']);
                    if (in_array($obj->getAttribute('id'), $selectedIds)) {
                        $selectionElmt = str_replace('#checked#', 'checked', $selectionElmt);
                    } else {
                        $selectionElmt = str_replace('#checked#', '', $selectionElmt);
                    }
                    $tmpRow[] = $selectionElmt;
                } catch (Exception $e) {
                    $tmpRow[] = '-';
                }
            }
            // Data Body
            foreach ($tmpObject->getBufferedAttributeSettings() as $attrName=>$attrSettings) {
                if (isset($attrSettings['visible']) &&
                    $attrSettings['visible'] &&
                    ($specificFilter == null ||
                     !is_array($specificFilter) ||
                     !isset($specificFilter[$attrName])) && 
                    ($columnException == null ||
                     !is_array($columnException) ||
                     !in_array($attrName, $columnException))) {
                    $tmpRow[] = $obj->renderAttribute($attrName, $columnFormatted);
                }
            }
            // Action Menu
            if (is_array($columnFormatted) && isset($columnFormatted['menu'])) {
                try {
                    if (empty($columnFormatted['menu'])) {
                        $tmpRow[] = '-';
                    }
                    $tmpRow[] = str_replace("##object_label##", ($obj->getTranslationLabel() . " " . $obj->getFormattedValue()), str_replace('#id#', $obj->getAttribute('id'), $columnFormatted['menu']) );
                } catch (Exception $e) {
                    $tmpRow[] = '-';
                }
            }
            // Add Row
            $jsonSource['data'][] = $tmpRow;
        }
        unset($jsonSource['rawdata']);

        // YADCF Column Specific Filter Options
        foreach ($columnFilter as $key => $element) {
            if (isset($datatableColumnRelationObject[$key]) &&
                $datatableColumnRelationObject[$key]) {
                // for attributes with relationship
                $objProperty = $datatableColumnRelationObject[$key]."__object";
                $relatedObject = $tmpObject->$objProperty;
                $attrSettings = $tmpObject->attribute_settings;
                if ($relatedObject) {
                    if (isset($datatableKeyIndex[$key]) &&
                        isset($attrSettings[$datatableKeyIndex[$key]]) &&
                        isset($attrSettings[$datatableKeyIndex[$key]]['options']) &&
                        !empty($attrSettings[$datatableKeyIndex[$key]]['options']) ) {
                        $optionSources = $attrSettings[$datatableKeyIndex[$key]]['options'];
                        foreach ($optionSources as $value => $label) {
                            $jsonSource['yadcf_data_'.$key][] = [
                                'value' => $value,
                                'label' => $label
                            ];
                        }
                    } else {
                        // $optionSources = $relatedObject->all()->pluck('default_name', $relatedObject->getUniqueValueColumn());
                        $jsonSource['yadcf_data_'.$key]  = [];

                        $passedValue = null;
                        $passedLabel = "";
                        try {
                            $passedValue = $specificDefinition[ $datatableSelection[$key] ];
                            $passedLabel = $relatedObject->find($passedValue)->getFormattedValue();
                        } catch (Exception $e) { }

                        if ($passedValue) {
                            $jsonSource['yadcf_data_'.$key][] = [
                                'value' => $passedValue,
                                'label' => $passedLabel
                            ];
                        }
                    }
                }
            } else if (isset($datatableColumnOptions[$key]) &&
                       is_array($datatableColumnOptions[$key]) &&
                       count($datatableColumnOptions[$key]) > 0) {
                // not relationship with options input
                $jsonSource['yadcf_data_'.$key] = array_values($datatableColumnOptions[$key]);
            }
        }

        // Return JSON Response
        return Response::json($jsonSource);
    }

    /**
     * Get object detail
     * @param  int $objectId
     * @return array Object Detail
     **/
    public function get($objectId)
    {
        $object = ($this->mainModel ? $this->mainModel->find($objectId) : SuitModel::find($objectId));
        return [
            'object' => $object
        ];
    }

    protected function doUpload(&$object)
    {
        Upload::setFilenameMaker(function ($file, $object) {
            $title = $object->getFormattedValue();
            return str_limit(str_slug($title.' '.date('YmdHis')), 200) . '.' . $file->getClientOriginalExtension();
        }, $object);
        try {
            Upload::model($object, false);
        } catch (\Exception $e) {
            $object->uploadError = $e;
        }

        foreach ($object->getImageAttributes() as $field => $value) {
            if ($object->getAttribute($field) !== null) {
                $folder = $object->getFolderPath($field).DIRECTORY_SEPARATOR;
                $image = substr($object->getImagePath($field), strlen($folder));
                try {
                    if ($file = Image::make($image)) {
                        try {
                            $name = md5($object->getFormattedValue().Carbon::now()).'.png';
                            $file->save($folder.$name);
                            $object->{$field} = $name;
                            info([$folder.$name, file_exists($folder.$name)]);
                        } catch (\Exception $e) {
                            unset($object->{$field});
                        }
                    }
                } catch (\Exception $e) {
                    //
                }
            } else {
                unset($object->{$field});
            }
        }
    }

    protected function saved($object, $param)
    {
        return $object;
    }

    public function create($param, SuitModel &$object = null) {
        SuitModel::$isFormGeneratorContext = false;
        $object = ($this->mainModel ? $this->mainModel->getNew() : new SuitModel);
        foreach($param as $key=>$val) {
            if (empty($val) && $val != '0' && $val != '0.0') $param[$key] = null;
        }
        if (!$object->isValid('create', null, $param)) {
            return false;
        }
        $object->fill($param);

        $this->doUpload($object);
        $result = $object->save();
        if ($result) {
            // saving translation if needed
            if (env('APP_MULTI_LOCALE', false)) {
                $locale = strtolower(config('app.fallback_locale', 'en'));
                $baseAttr = $object->getAttributeSettings();
                $localeOptions = explode(',', env('APP_MULTI_LOCALE_OPTIONS', $locale));
                foreach ($baseAttr as $attrName=>$attrSettings) {
                    foreach ($localeOptions as $lang) {
                        $normalizedLang = strtolower($lang);
                        if ($normalizedLang != $locale) {
                            $paramKey = $attrName.'_trans_'.$normalizedLang;
                            if (isset($attrSettings['translation']) &&
                                $attrSettings['translation'] &&
                                isset($param[$paramKey]) &&
                                !empty($param[$paramKey])) {
                                $currentTranslation = SuitTranslation::create([
                                    'class' => $object->nodeFullClassName,
                                    'identifier' => $object->id,
                                    'attribute' => $attrName,
                                    'locale' => $normalizedLang,
                                    'value' => $param[$paramKey]
                                ]);
                            }
                        }
                    }
                }
            }
        }

        $object = $this->saved($object, $param);
        return $object;
    }

    public function update($id, $param, SuitModel &$object = null) {
        /** @var SuitModel $object */
        SuitModel::$isFormGeneratorContext = false;
        $object = ($this->mainModel ? $this->mainModel->find($id) : SuitModel::find($id));
        if ($object == null) return false;
        $deletedFieldFiles = [];
        foreach($param as $key=>$val) {
            if (empty($val) && $val != '0' && $val != '0.0') $param[$key] = null;
            if (starts_with($key, 'delete_file__') && $val == 'on') {
                $deletedFieldFiles[] = str_replace('delete_file__', '', $key);
            }
        }
        if (!$object->isValid('update', null, $param)) {
            return false;
        }
        $object->fill($param);

        Upload::cleanUploaded($object, $deletedFieldFiles, false);
        $this->doUpload($object);
        $result = $object->save();
        if ($result) {
            // saving translation if needed
            if (env('APP_MULTI_LOCALE', false)) {
                $locale = strtolower(config('app.fallback_locale', 'en'));
                $baseAttr = $object->getAttributeSettings();
                $localeOptions = explode(',', env('APP_MULTI_LOCALE_OPTIONS', $locale));
                foreach ($baseAttr as $attrName=>$attrSettings) {
                    foreach ($localeOptions as $lang) {
                        $normalizedLang = strtolower($lang);
                        if ($normalizedLang != $locale) {
                            $paramKey = $attrName.'_trans_'.$normalizedLang;
                            if (isset($attrSettings['translation']) &&
                                $attrSettings['translation'] &&
                                isset($param[$paramKey]) &&
                                !empty($param[$paramKey])) {
                                $currentTranslation = SuitTranslation::select('*')->instance($object->nodeFullClassName, $object->id)->locale($locale)->field($attrName)->first();
                                if ($currentTranslation) {
                                    $currentTranslation->value = $param[$paramKey];
                                    $currentTranslation->save();
                                } else {
                                    $currentTranslation = SuitTranslation::create([
                                            'class' => $object->nodeFullClassName,
                                            'identifier' => $object->id,
                                            'attribute' => $attrName,
                                            'locale' => $normalizedLang,
                                            'value' => $param[$paramKey]
                                        ]);
                                }
                            }
                        }
                    }
                }
            }
        }

        $object = $this->saved($object, $param);
        return $object;
    }

    public function delete($id, SuitModel &$object = null) {
        $object = ($this->mainModel ? $this->mainModel->find($id) : SuitModel::find($id));
        if ($object == null) return false;
        // Delete Object
        $result = $object->delete();
        if ($result) {
            // deleting translation if needed
            if (env('APP_MULTI_LOCALE', false)) {
                $allTranslationDeleted = SuitTranslation::select('*')->instance($object->nodeFullClassName, $object->id)->delete();
            }
        }
        return $result;
    }

    public function deleteAll($ids) {
        $result = false;
        if (is_array($ids) && count($ids) > 0) {
            $object = ($this->mainModel ? $this->mainModel : new SuitModel);
            // Delete Object
            $result = $object->whereIn($object->getKeyName(), $ids)->delete();
            if ($result) {
                // deleting all translation if needed
                if (env('APP_MULTI_LOCALE', false)) {
                    $allTranslationDeleted = SuitTranslation::select('*')->multiInstance($object->nodeFullClassName, $ids)->delete();
                }
            }
        }
        return $result;
    }

    /**
     *
     * Get Filtered Object List/Single/Raw Query Builder
     * 
     * Sample Parameter without condition and relationship
     *   attribute_name : "attribute_name"
     *   attribute_name : "attribute_name1,attribte_name2,dll"
     * Sample Parameter with condition
     *   _max_attribute_name : "max" "attribute_name"
     * Sample Parameter with relationship
     *   _relationshipName_attribute_name : "relationshipName" "attribute_name"
     * Sample Parameter with condition and relationship
     *   _max__relationshipName_attribute_name : "max" "relationshipName" "attribute_name"
     *
     * Defined Parameter
     *
     * keyword : keyword pattern separated by spaces
     * orderBy : multiple ordering column
     * orderType : multiple ordering column
     * paginate : false / true
     * perPage : number : -1 (all), 1, 2, 10, etc
     * if paginate false and perPage 1 then its single object, return first() from model collection
     *
     * @param  array  $param
     * @param  array  $setting
     *
     * @return SuitModel|SuitModel[]|\Illuminate\Database\Eloquent\Collection
     **/
    public function getByParameter($param, $setting = [])
    {
        // Check Settings
        $defaultSetting = [
            'raw_result' => false,
            'optional_dependency' => null,
            'extended_raw_select' => null,
            'extended_condition' => null,
            'extended_grouping' => null
        ];
        $setting = array_merge($defaultSetting, $setting);

        // Base Object
        SuitModel::$isFormGeneratorContext = false;
        $baseObject = ($this->mainModel ? $this->mainModel : new SuitModel);
        $baseAttr = $baseObject->getAttributeSettings();
        $model = null;

        // Standard Select
        $model = $baseObject->select($baseObject->getTable().".*");

        // Extended Query Select
        if ($setting['extended_raw_select']) {
            $model = $model->addSelect(DB::raw($setting['extended_raw_select']));
        }

        // Populate default paramater
        $default = [
            'keyword' => false,
            'orderBy' => 'created_at',
            'orderType' => 'desc',
            'paginate' => true,
            'perPage' => static::DEFAULT_FETCH
        ];
        foreach ($baseAttr as $key=>$value) {
            $default[$key] = false;
        }

        // Merge Filter Parameter
        $param = array_merge($default, $param);

        // Process Filter
        $model = $baseObject->with(($setting['optional_dependency'] ? $setting['optional_dependency'] : $this->dependencies)); // use default dependency if optional dependency not exist
        foreach ($param as $key=>$value) {
            $realAttrName = $key;
            $conditional = "";
            $relationName = "";
            // get conditional and real attribute name
            // IF MATCHED : _max_ , _min_ , _similar_
            if (starts_with($key, '_max_')) {
                $realAttrName = str_replace("_max_", "", $key);
                $conditional = 'max';
            } elseif (starts_with($key, '_min_')) {
                $realAttrName = str_replace("_min_", "", $key);
                $conditional = 'min';
            } elseif (starts_with($key, '_similar_')) {
                $realAttrName = str_replace("_similar_", "", $key);
                $conditional = 'similar';
            } elseif (starts_with($key, '_isnull_')) {
                $realAttrName = str_replace("_isnull_", "", $key);
                $conditional = 'isnull';
            }
            // get conditional from relationship attributes
            // IF MATCHED : _[relationshipName]_
            if (starts_with($realAttrName, '_')) {
                // if '_' exist then process relationship
                $attrNameComponents = explode('_', '#' . $realAttrName);
                if (count($attrNameComponents) > 2) {
                    // second components is relationship name
                    $relationName = $attrNameComponents[1];
                    $realAttrName = str_replace("_" . $relationName . "_", "", $realAttrName);
                }
            }
            // build condition to query builder
            if (!empty($relationName)) { 
                // if relatiohship exist
                // indirect condition, within relationship
                if (method_exists($baseObject, $relationName)) {
                    $className = (get_class($baseObject->{$relationName}()->getRelated()));
                    $reflection = new $className(); 
                    $relationBaseAttr = $reflection->getAttributeSettings();
                    if (isset($relationBaseAttr[$realAttrName]) && ($value || $value === 0)) {
                        if ($conditional == 'max' &&
                            isset($relationBaseAttr[$realAttrName]["type"]) &&
                            in_array($relationBaseAttr[$realAttrName]["type"], [SuitModel::TYPE_NUMERIC, SuitModel::TYPE_FLOAT, SuitModel::TYPE_DATETIME, SuitModel::TYPE_DATE, SuitModel::TYPE_TIME])) {
                            // maximum value
                            $model = $model->whereHas($relationName, function ($query) use ($realAttrName, $value, $reflection) {
                                $query->where($reflection->getTable().".".$realAttrName, '<=', $value);
                            });
                        } elseif ($conditional == 'min' &&
                            isset($relationBaseAttr[$realAttrName]["type"]) &&
                            in_array($relationBaseAttr[$realAttrName]["type"], [SuitModel::TYPE_NUMERIC, SuitModel::TYPE_FLOAT, SuitModel::TYPE_DATETIME, SuitModel::TYPE_DATE, SuitModel::TYPE_TIME])) {
                            // minimum value
                            $model = $model->whereHas($relationName, function ($query) use ($realAttrName, $value, $reflection) {
                                $query->where($reflection->getTable().".".$realAttrName, '>=', $value);
                            });
                        } elseif ($conditional == 'similar' &&
                            isset($relationBaseAttr[$realAttrName]["type"]) &&
                            in_array($relationBaseAttr[$realAttrName]["type"], [SuitModel::TYPE_TEXT, SuitModel::TYPE_TEXTAREA, SuitModel::TYPE_RICHTEXTAREA, SuitModel::TYPE_FILE])) {
                            // have similar value
                            $model = $model->whereHas($relationName, function ($query) use ($realAttrName, $value, $reflection) {
                                $query->where($reflection->getTable().".".$realAttrName, 'LIKE', '%' . $value . '%');
                            });
                        } elseif ($conditional == 'isnull') {
                            // check null
                            $model = $model->whereHas($relationName, function ($query) use ($realAttrName, $reflection) {
                                $query->whereNull($reflection->getTable().".".$realAttrName);
                            });
                        } else {
                            // exact match value
                            $model = $model->whereHas($relationName, function ($query) use ($realAttrName, $value, $reflection) {
                                $values = explode(',', $value);
                                $query->whereIn($reflection->getTable().".".$realAttrName, $values);
                            });
                        }
                    }
                }
            } else {
                // direct condition
                // add conditional to query builder
                if (isset($baseAttr[$realAttrName]) && ($value || $value === 0)) {
                    if ($conditional == 'max' &&
                        isset($baseAttr[$realAttrName]["type"]) &&
                        in_array($baseAttr[$realAttrName]["type"], [SuitModel::TYPE_NUMERIC, SuitModel::TYPE_FLOAT, SuitModel::TYPE_DATETIME, SuitModel::TYPE_DATE, SuitModel::TYPE_TIME])) {
                        // maximum value
                        $model = $model->where($baseObject->getTable().".".$realAttrName, '<=', $value);
                    } elseif ($conditional == 'min' &&
                        isset($baseAttr[$realAttrName]["type"]) &&
                        in_array($baseAttr[$realAttrName]["type"], [SuitModel::TYPE_NUMERIC, SuitModel::TYPE_FLOAT, SuitModel::TYPE_DATETIME, SuitModel::TYPE_DATE, SuitModel::TYPE_TIME])) {
                        // minimum value
                        $model = $model->where($baseObject->getTable().".".$realAttrName, '>=', $value);
                    } elseif ($conditional == 'similar' &&
                        isset($baseAttr[$realAttrName]["type"]) &&
                        in_array($baseAttr[$realAttrName]["type"], [SuitModel::TYPE_TEXT, SuitModel::TYPE_TEXTAREA, SuitModel::TYPE_RICHTEXTAREA, SuitModel::TYPE_FILE])) {
                        // have similar value
                        $model = $model->where($baseObject->getTable().".".$realAttrName, 'LIKE', '%' . $value . '%');
                    } elseif ($conditional == 'isnull') {
                        // check null
                        $model = $model->whereNull($baseObject->getTable().".".$realAttrName);
                    } else {
                        // exact match value
                        $values = explode(',', $value);
                        $model = $model->whereIn($baseObject->getTable().".".$realAttrName, $values);
                    }
                }
            }
        }

        // Filter by keyword
        if ($keyword = $param['keyword']) {
            if (!empty($keyword)) {
                $model = $model->where(function ($q) use ($baseObject, $keyword, $baseAttr) {
                    $keywords = explode(' ', $keyword);
                    $firstClauseInitiated = false;
                    foreach ($baseAttr as $key=>$setting) {
                        if (in_array($setting["type"], [SuitModel::TYPE_TEXT, SuitModel::TYPE_TEXTAREA, SuitModel::TYPE_RICHTEXTAREA, SuitModel::TYPE_FILE])) {
                            foreach ($keywords as $idx => $term) {
                                if (!$firstClauseInitiated) {
                                    $q->where($baseObject->getTable().".".$key, 'like', '%' . $term . '%');
                                    $firstClauseInitiated = true;
                                } else {
                                    $q->orWhere($baseObject->getTable().".".$key, 'like', '%' . $term . '%');
                                }
                            }
                        }
                    }
                }); 
            }
        }

        // Extended Query Condition
        if ($setting['extended_condition']) {
            $model = $model->where($setting['extended_condition']); 
        }

        // Ordering Rules
        if ($param['orderBy'])
        {
            $listOrderBy = explode(',', $param['orderBy']);
            $listOrderType = explode(',', $param['orderType']);
            if ($listOrderBy && is_array($listOrderBy) && count($listOrderBy) > 0) {
                foreach($listOrderBy as $key=>$val) {
                    if (isset($baseAttr[$val])) {
                        $model = $model->orderBy( $baseObject->getTable().".".$val, ($listOrderType && is_array($listOrderType) && count($listOrderType) > 0 && isset($listOrderType[$key]) ? $listOrderType[$key] : "asc") );
                    }
                }
            }
        }

        // Group By (id any)
        if ($setting['extended_grouping']) {
            $model = $model->groupBy($setting['extended_grouping']); 
        }

        // RETURN RESULT
        // Raw Result if Requested
        if ($setting['raw_result']) return $model;
        // Get Paginated / No Paging
        if ($param['paginate'] && $param['perPage'] != static::FETCH_ALL) {
            // n object paginated
            return $model->paginate($param['perPage']);
        }
        // not paginated ...
        if ($param['perPage'] == static::FETCH_ALL) {
            // all object
            return $model->get();
        } elseif ($param['perPage'] == 1) {
            // single object
            return $model->first();
        } 
        // n object
        return $model->take($param['perPage'] < 1 ? static::DEFAULT_FETCH : $param['perPage'])->get();
    }

    /*
    public function getByParameter($param, $setting = [])
    {
        // Check Settings
        $defaultSetting = [
            'raw_result' => false,
            'optional_dependency' => null,
            'optional_mapped_query_dependency' => null,
            'extended_raw_select' => null,
            'extended_condition' => null,
            'extended_grouping' => null
        ];
        $setting = array_merge($defaultSetting, $setting);

        // Base Object
        SuitModel::$isFormGeneratorContext = false;
        $baseObject = ($this->mainModel ? $this->mainModel : new SuitModel);
        $baseAttr = $baseObject->getAttributeSettings();
        $model = null;

        // Standard Select
        $model = $baseObject->select($baseObject->getTable().".*");

        // Extended Query Select
        if ($setting['extended_raw_select']) {
            $model = $model->addSelect(DB::raw($setting['extended_raw_select']));
        }

        // Populate default paramater
        $default = [
            'keyword' => false,
            'orderBy' => 'created_at',
            'orderType' => 'desc',
            'paginate' => true,
            'perPage' => static::DEFAULT_FETCH
        ];
        foreach ($baseAttr as $key=>$value) {
            $default[$key] = false;
        }

        // Merge Filter Parameter
        $param = array_merge($default, $param);

        // Process Filter
        if ($setting['optional_dependency']) {
            if (!$setting['optional_mapped_query_dependency']) {
                $model = $model->with($setting['optional_dependency']); // standard eager loading
            }
        } elseif ($this->dependencies) {
            $model = $model->with($this->dependencies);
        }
        // $model = $model->with(($setting['optional_dependency'] ? $setting['optional_dependency'] : $this->dependencies)); // use default dependency if optional dependency not exist

        if ($setting['optional_mapped_query_dependency'] &&
            is_array($setting['optional_mapped_query_dependency']) &&
            $setting['optional_dependency'] &&
            is_array($setting['optional_dependency']) &&
            count($setting['optional_mapped_query_dependency']) == count($setting['optional_dependency'])) {
            foreach ($setting['optional_mapped_query_dependency'] as $key => $fullClassName) {
                try {
                    $relatedObj = new $fullClassName();
                    if ($relatedObj instanceof SuitModel) {
                        $fieldList = $relatedObj->getTableColumns();
                        foreach ($fieldList as $subKey) {
                            $model = $model->addSelect(DB::raw($relatedObj->getTable() . "." . $subKey." AS " . $relatedObj->getTable() . "_" . $subKey));
                        }
                        $model = $model->leftJoin($relatedObj->getTable(), $baseObject->getTable().'.'.$key, '=', $relatedObj->getTable().'.id');
                    }
                } catch (Exception $e) { }
            }
        }

        foreach ($param as $key=>$value) {
            $realAttrName = $key;
            $conditional = "";
            $relationName = "";
            // get conditional and real attribute name
            // IF MATCHED : _max_ , _min_ , _similar_
            if (starts_with($key, '_max_')) {
                $realAttrName = str_replace("_max_", "", $key);
                $conditional = 'max';
            } elseif (starts_with($key, '_min_')) {
                $realAttrName = str_replace("_min_", "", $key);
                $conditional = 'min';
            } elseif (starts_with($key, '_similar_')) {
                $realAttrName = str_replace("_similar_", "", $key);
                $conditional = 'similar';
            } elseif (starts_with($key, '_isnull_')) {
                $realAttrName = str_replace("_isnull_", "", $key);
                $conditional = 'isnull';
            }
            // get conditional from relationship attributes
            // IF MATCHED : _[relationshipName]_
            if (starts_with($realAttrName, '_')) {
                // if '_' exist then process relationship
                $attrNameComponents = explode('_', '#' . $realAttrName);
                if (count($attrNameComponents) > 2) {
                    // second components is relationship name
                    $relationName = $attrNameComponents[1];
                    $realAttrName = str_replace("_" . $relationName . "_", "", $realAttrName);
                }
            }
            // build condition to query builder
            if (!empty($relationName)) { 
                // if relatiohship exist
                // indirect condition, within relationship
                if (method_exists($baseObject, $relationName)) {
                    $className = (get_class($baseObject->{$relationName}()->getRelated()));
                    $reflection = new $className(); 
                    $relationBaseAttr = $reflection->getAttributeSettings();
                    if (isset($relationBaseAttr[$realAttrName]) && ($value || $value === 0)) {
                        if ($conditional == 'max' &&
                            isset($relationBaseAttr[$realAttrName]["type"]) &&
                            in_array($relationBaseAttr[$realAttrName]["type"], [SuitModel::TYPE_NUMERIC, SuitModel::TYPE_FLOAT, SuitModel::TYPE_DATETIME, SuitModel::TYPE_DATE, SuitModel::TYPE_TIME])) {
                            // maximum value
                            $model = $model->whereHas($relationName, function ($query) use ($realAttrName, $value) {
                                $query->where($realAttrName, '<=', $value);
                            });
                        } elseif ($conditional == 'min' &&
                            isset($relationBaseAttr[$realAttrName]["type"]) &&
                            in_array($relationBaseAttr[$realAttrName]["type"], [SuitModel::TYPE_NUMERIC, SuitModel::TYPE_FLOAT, SuitModel::TYPE_DATETIME, SuitModel::TYPE_DATE, SuitModel::TYPE_TIME])) {
                            // minimum value
                            $model = $model->whereHas($relationName, function ($query) use ($realAttrName, $value) {
                                $query->where($realAttrName, '>=', $value);
                            });
                        } elseif ($conditional == 'similar' &&
                            isset($relationBaseAttr[$realAttrName]["type"]) &&
                            in_array($relationBaseAttr[$realAttrName]["type"], [SuitModel::TYPE_TEXT, SuitModel::TYPE_TEXTAREA, SuitModel::TYPE_RICHTEXTAREA, SuitModel::TYPE_FILE])) {
                            // have similar value
                            $model = $model->whereHas($relationName, function ($query) use ($realAttrName, $value) {
                                $query->where($realAttrName, 'LIKE', '%' . $value . '%');
                            });
                        } elseif ($conditional == 'isnull') {
                            // check null
                            $model = $model->whereHas($relationName, function ($query) use ($realAttrName) {
                                $query->whereNull($realAttrName);
                            });
                        } else {
                            // exact match value
                            $model = $model->whereHas($relationName, function ($query) use ($realAttrName, $value) {
                                $values = explode(',', $value);
                                $query->whereIn($realAttrName, $values);
                            });
                        }
                    }
                }
            } else {
                // direct condition
                // add conditional to query builder
                if (isset($baseAttr[$realAttrName]) && ($value || $value === 0)) {
                    if ($conditional == 'max' &&
                        isset($baseAttr[$realAttrName]["type"]) &&
                        in_array($baseAttr[$realAttrName]["type"], [SuitModel::TYPE_NUMERIC, SuitModel::TYPE_FLOAT, SuitModel::TYPE_DATETIME, SuitModel::TYPE_DATE, SuitModel::TYPE_TIME])) {
                        // maximum value
                        $model = $model->where($realAttrName, '<=', $value);
                    } elseif ($conditional == 'min' &&
                        isset($baseAttr[$realAttrName]["type"]) &&
                        in_array($baseAttr[$realAttrName]["type"], [SuitModel::TYPE_NUMERIC, SuitModel::TYPE_FLOAT, SuitModel::TYPE_DATETIME, SuitModel::TYPE_DATE, SuitModel::TYPE_TIME])) {
                        // minimum value
                        $model = $model->where($realAttrName, '>=', $value);
                    } elseif ($conditional == 'similar' &&
                        isset($baseAttr[$realAttrName]["type"]) &&
                        in_array($baseAttr[$realAttrName]["type"], [SuitModel::TYPE_TEXT, SuitModel::TYPE_TEXTAREA, SuitModel::TYPE_RICHTEXTAREA, SuitModel::TYPE_FILE])) {
                        // have similar value
                        $model = $model->where($realAttrName, 'LIKE', '%' . $value . '%');
                    } elseif ($conditional == 'isnull') {
                        // check null
                        $model = $model->whereNull($realAttrName);
                    } else {
                        // exact match value
                        $values = explode(',', $value);
                        $model = $model->whereIn($realAttrName, $values);
                    }
                }
            }
        }

        // Filter by keyword
        if ($keyword = $param['keyword']) {
            if (!empty($keyword)) {
                $model = $model->where(function ($q) use ($keyword, $baseAttr) {
                    $keywords = explode(' ', $keyword);
                    $firstClauseInitiated = false;
                    foreach ($baseAttr as $key=>$_setting) {
                        if (in_array($_setting["type"], [SuitModel::TYPE_TEXT, SuitModel::TYPE_TEXTAREA, SuitModel::TYPE_RICHTEXTAREA, SuitModel::TYPE_FILE])) {
                            foreach ($keywords as $idx => $term) {
                                if (!$firstClauseInitiated) {
                                    $q->where($key, 'like', '%' . $term . '%');
                                    $firstClauseInitiated = true;
                                } else {
                                    $q->orWhere($key, 'like', '%' . $term . '%');
                                }
                            }
                        }
                    }
                }); 
            }
        }

        // Extended Query Condition
        if ($setting['extended_condition']) {
            $model = $model->where($setting['extended_condition']); 
        }

        // Ordering Rules
        if ($param['orderBy'])
        {
            $listOrderBy = explode(',', $param['orderBy']);
            $listOrderType = explode(',', $param['orderType']);
            if ($listOrderBy && is_array($listOrderBy) && count($listOrderBy) > 0) {
                foreach($listOrderBy as $key=>$val) {
                    if (isset($baseAttr[$val])) {
                        $model = $model->orderBy( $val, ($listOrderType && is_array($listOrderType) && count($listOrderType) > 0 && isset($listOrderType[$key]) ? $listOrderType[$key] : "asc") );
                    }
                }
            }
        }

        // Group By (id any)
        if ($setting['extended_grouping']) {
            $model = $model->groupBy($setting['extended_grouping']); 
        }

        // RETURN RESULT
        // Raw Result if Requested
        if ($setting['raw_result']) return $model;
        // Get Paginated / No Paging
        if ($param['paginate'] && $param['perPage'] != static::FETCH_ALL) {
            // n object paginated
            $result = $model->paginate($param['perPage']);

            // custom if optional_mapped_query_dependency exist
            if ($setting['optional_mapped_query_dependency'] &&
            is_array($setting['optional_mapped_query_dependency']) &&
            $setting['optional_dependency'] &&
            is_array($setting['optional_dependency']) &&
            count($setting['optional_mapped_query_dependency']) == count($setting['optional_dependency'])) {

                $modelArr = [];
                foreach ($result as $key => $obj) {
                    $processedObj = $obj;
                    $optionalDependencyIdx = 0;
                    foreach ($setting['optional_mapped_query_dependency'] as $fullClassName) {
                        try {
                            $relationshipName = $setting['optional_dependency'][$optionalDependencyIdx];
                            $relationshipObj = new $fullClassName();
                            if ($relationshipObj instanceof SuitModel) {
                                $fieldList = $relationshipObj->getTableColumns();
                                foreach ($fieldList as $subKey) {
                                    $aliasKey = $relationshipObj->getTable() . "_" . $subKey;
                                    $relationshipObj->$subKey = $processedObj->$aliasKey;
                                    unset($processedObj->$aliasKey);
                                }
                                $processedObj->setAttribute($relationshipName, $relationshipObj);
                            }
                        } catch (Exception $e) { }
                        $optionalDependencyIdx++;
                    }
                    $modelArr[] = $processedObj;
                }

                $page = isset($param['page']) ? $param['page'] : 1;
                $perPage = $param['perPage']; 
                $offset = ($page * $perPage) - $perPage;

                return new LengthAwarePaginator(
                    $modelArr,
                    $result->total(), 
                    $perPage, 
                    $page, 
                    ['path' => request()->url(), 'query' => request()->query()] 
                );
            }

            // standard way with eager loading if any
            return $result;
        }
        // not paginated ...
        if ($param['perPage'] == static::FETCH_ALL) {
            // all object
            return $model->get();
        } elseif ($param['perPage'] == 1) {
            // single object
            return $model->first();
        } 
        // n object
        return $model->take($param['perPage'] < 1 ? static::DEFAULT_FETCH : $param['perPage'])->get();
    }
    */
}
