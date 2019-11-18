<?php

namespace Suitcore\Datatable;

use Input;
use Exception;
use Suitcore\Models\SuitModel;

trait DatatableTrait {
    public function preprocessDatatablesJson($_genericModel, $_displayedColumn, $_specificFilters = null, $_optionalFilters = null, $_defaultOrder = null, $_defaultOrderDir = 'desc', $_extendedColumnSearch = null, $_rawExtendedQuery = null) {
        // get pageId from session if any and reset
        $datatablePageId = session()->get('datatablePageId', false);
        session()->put('datatablePageId', false);

        // input param
        $draw   = ((int) Input::get('draw',0));
        
        $start  = ((int) Input::get('start',0));
        if ($datatablePageId) {
            session()->put('datatable['.$datatablePageId.'][iDisplayStart]', $start); // save if next request needed to use this setting at client side
        }

        $length = ((int) Input::get('length',10));
        if ($datatablePageId) {
            session()->put('datatable['.$datatablePageId.'][iDisplayLength]', $length); // save if next request needed to use this setting at client side
        }

        $search = Input::get("search",'');
        $order  = Input::get("order",'');
        Input::merge(array('page' => (((int) floor($start / $length)) + 1))); // define page
        // fetch model
        $genericModel = $_genericModel;
        $displayedColumn = $_displayedColumn;
        $extendedColumnSearch = $_extendedColumnSearch;
        // search filter
        if (isset($search['value'])) {
            $searchFilter = trim($search['value']);
            $mainObject = $genericModel->getModel();
            $genericModel = $genericModel->where(function($query) use ($displayedColumn, $searchFilter, $extendedColumnSearch, $mainObject)
                          {
                            if ($displayedColumn) {
                                if (starts_with($searchFilter, '=') || starts_with($searchFilter, '>') || starts_with($searchFilter, '<')) {
                                    // INTEGER/NUMBER, FLOAT, DATE, TIME, DATETIME
                                    $operator = starts_with($searchFilter, '=') ? '=' : (starts_with($searchFilter, '>=') ? '>=' : (starts_with($searchFilter, '<=') ? '<=' : (starts_with($searchFilter, '>') ? '>' : '<') ));
                                    $operant = trim(str_replace($operator, '', $searchFilter));
                                    if (is_numeric($operant)) {
                                        // INTEGER NUMBER OR FLOAT
                                        $attrSettings = $mainObject->attribute_settings;
                                        $firstField = true;
                                        foreach ($displayedColumn as $idx => $field) {
                                            $actualFieldName = str_replace($mainObject->getTable().'.', '', $field);
                                            if (isset($attrSettings[$actualFieldName]) &&
                                                isset($attrSettings[$actualFieldName]['type']) &&
                                                ($attrSettings[$actualFieldName]['type'] == SuitModel::TYPE_NUMERIC ||
                                                 $attrSettings[$actualFieldName]['type'] == SuitModel::TYPE_FLOAT)) {
                                                if ($firstField) {
                                                    $query->where($field,$operator,$operant);
                                                    $firstField = false;
                                                } else {
                                                    $query->orWhere($field,$operator,$operant);
                                                }
                                            }
                                        }
                                    } elseif ( ($timestamp = strtotime($operant)) !== false ) {
                                        // DATE / TIME / DATETIME FORMAT
                                        $databaseDateFormat = date('Y-m-d H:i:s', $timestamp);
                                        $attrSettings = $mainObject->attribute_settings;
                                        $firstField = true;
                                        foreach ($displayedColumn as $idx => $field) {
                                            $actualFieldName = str_replace($mainObject->getTable().'.', '', $field);
                                            if (isset($attrSettings[$actualFieldName]) &&
                                                isset($attrSettings[$actualFieldName]['type']) &&
                                                ($attrSettings[$actualFieldName]['type'] == SuitModel::TYPE_DATETIME ||
                                                 $attrSettings[$actualFieldName]['type'] == SuitModel::TYPE_DATE ||
                                                 $attrSettings[$actualFieldName]['type'] == SuitModel::TYPE_TIME)) {
                                                if ($firstField) {
                                                    $query->where($field,$operator,$databaseDateFormat);
                                                    $firstField = false;
                                                } else {
                                                    $query->orWhere($field,$operator,$databaseDateFormat);
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    // TEXT BASED WITH LIKE OPERATOR AND EXTENDED SEARCH
                                    $attrSettings = $mainObject->attribute_settings;
                                    $firstField = true;
                                    foreach ($displayedColumn as $idx => $field) {
                                        $actualFieldName = str_replace($mainObject->getTable().'.', '', $field);
                                        if (isset($attrSettings[$actualFieldName]) &&
                                            isset($attrSettings[$actualFieldName]['type']) &&
                                            ($attrSettings[$actualFieldName]['type'] == SuitModel::TYPE_TEXT ||
                                             $attrSettings[$actualFieldName]['type'] == SuitModel::TYPE_TEXTAREA ||
                                             $attrSettings[$actualFieldName]['type'] == SuitModel::TYPE_RICHTEXTAREA)) {
                                            if ($firstField) {
                                                $query->where($field,"like","%".$searchFilter."%");
                                                $firstField = false;
                                            } else {
                                                $query->orWhere($field,"like","%".$searchFilter."%");
                                            }
                                        }
                                    }
                                    // extended search
                                    if($extendedColumnSearch) {
                                        foreach ($extendedColumnSearch as $key => $obj) {
                                            $localColumnSearch = $obj->getFormattedValueColumn();
                                            if ($localColumnSearch && ($relation = (isset($attrSettings[$key]) && isset($attrSettings[$key]['relation']) ? $attrSettings[$key]['relation'] : null)) ) {
                                                if ($firstField) {
                                                    $query->where(function ($qq) use ($key, $relation, $searchFilter, $obj, $localColumnSearch) {
                                                        $qq->whereHas($relation, function ($extendedQuery) use ($searchFilter, $obj, $localColumnSearch) {
                                                            if ($localColumnSearch) {
                                                                $attrSettings2 = $obj->attribute_settings;
                                                                $firstField2 = true;
                                                                foreach ($localColumnSearch as $idx => $field2) {
                                                                    if (isset($attrSettings2[$field2]) &&
                                                                        isset($attrSettings2[$field2]['type']) &&
                                                                        ($attrSettings2[$field2]['type'] == SuitModel::TYPE_TEXT ||
                                                                         $attrSettings2[$field2]['type'] == SuitModel::TYPE_TEXTAREA ||
                                                                         $attrSettings2[$field2]['type'] == SuitModel::TYPE_RICHTEXTAREA)) {
                                                                        if ($firstField2) {
                                                                            $extendedQuery->where($obj->getTable().'.'.$field2,"like","%".$searchFilter."%");
                                                                            $firstField2 = false;
                                                                        } else {
                                                                            $extendedQuery->orWhere($obj->getTable().'.'.$field2,"like","%".$searchFilter."%");
                                                                        }
                                                                    }
                                                                }
                                                                if ($firstField2) {
                                                                    // if still no field searched
                                                                    $extendedQuery->whereNull($obj->getTable().'.'.$obj->getKeyName());
                                                                }
                                                            }
                                                        });
                                                        if (empty($searchFilter)) {
                                                            $qq->orWhereNull($key);
                                                        }
                                                    });
                                                    $firstField = false;
                                                } else {
                                                    $query->orWhere(function ($qq) use ($key, $relation, $searchFilter, $obj, $localColumnSearch) {
                                                        $qq->whereHas($relation, function ($extendedQuery) use ($searchFilter, $obj, $localColumnSearch) {
                                                            if ($localColumnSearch) {
                                                                $attrSettings2 = $obj->attribute_settings;
                                                                $firstField2 = true;
                                                                foreach ($localColumnSearch as $idx => $field2) {
                                                                    if (isset($attrSettings2[$field2]) &&
                                                                        isset($attrSettings2[$field2]['type']) &&
                                                                        ($attrSettings2[$field2]['type'] == SuitModel::TYPE_TEXT ||
                                                                         $attrSettings2[$field2]['type'] == SuitModel::TYPE_TEXTAREA ||
                                                                         $attrSettings2[$field2]['type'] == SuitModel::TYPE_RICHTEXTAREA)) {
                                                                        if ($firstField2) {
                                                                            $extendedQuery->where($obj->getTable().'.'.$field2,"like","%".$searchFilter."%");
                                                                            $firstField2 = false;
                                                                        } else {
                                                                            $extendedQuery->orWhere($obj->getTable().'.'.$field2,"like","%".$searchFilter."%");
                                                                        }
                                                                    }
                                                                }
                                                                if ($firstField2) {
                                                                    // if still no field searched
                                                                    $extendedQuery->whereNull($obj->getTable().'.'.$obj->getKeyName());
                                                                }
                                                            }
                                                        });
                                                        if (empty($searchFilter)) {
                                                            $qq->orWhereNull($key);
                                                        }
                                                    });
                                                }
                                            }
                                        } 
                                    } 
                                }
                            }
                          });
            if ($_specificFilters != null && count($_specificFilters) > 0) {
                session()->put('datatable_specific_filters', $_specificFilters);
                $genericModel->where(function($query) use ($_specificFilters, $mainObject)
                    {
                        $attrSettings = $mainObject->attribute_settings;
                        foreach($_specificFilters as $key=>$val) {
                            $estimatedMainObjectRealKey = str_replace($mainObject->getTable().".", "", $key);
                            if (isset($attrSettings[$estimatedMainObjectRealKey]) &&
                                isset($attrSettings[$estimatedMainObjectRealKey]['type']) &&
                                in_array($attrSettings[$estimatedMainObjectRealKey]['type'], [SuitModel::TYPE_DATETIME, SuitModel::TYPE_DATE]) &&
                                str_contains($val, "-yadcf_delim-") ) {
                                $ranges = explode("-yadcf_delim-", $val);
                                if (count($ranges) == 2) {
                                    if (!empty($ranges[0]) &&
                                        !empty($ranges[1])) {
                                        $query->where($key,">=",$ranges[0]);
                                        $query->where($key,"<=",$ranges[1]);
                                    } elseif (!empty($ranges[0]) &&
                                        empty($ranges[1])) {
                                        $query->where($key,">=",$ranges[0]);
                                    } elseif (empty($ranges[0]) &&
                                        !empty($ranges[1])) {
                                        $query->where($key,"<=",$ranges[1]);
                                    } 
                                }
                            } elseif (!str_contains($val, "-yadcf_delim-")) {
                                if (is_array($val) && !empty($val)) {
                                    $query->whereIn($key, $val);
                                } else {
                                    $query->where($key,"=",$val);
                                }
                            }
                        }
                    });  
            } 
            if ($_optionalFilters != null && count($_optionalFilters) > 0) {
                $genericModel->orWhere(function($query) use ($_optionalFilters)
                    {
                        foreach($_optionalFilters as $key=>$val) {
                            if (is_array($val) && !empty($val)) {
                                $query->orWhereIn($key, $val);
                            } else {
                                $query->orWhere($key,"=",$val);
                            }
                        }
                    });  
            }       
        }
        // raw extended query (AND)
        if ($_rawExtendedQuery) {
            $genericModel = $genericModel->where($_rawExtendedQuery);
        }
        // order filter
        if (isset($order) && is_array($order) && count($order) > 0) {
            foreach ($order as $key => $value) {
                $genericModel = $genericModel->orderBy($displayedColumn[$value['column']], (isset($value['dir']) ? $value['dir'] : $_defaultOrderDir));
            }
        } else if ($_defaultOrder) {
            $genericModel = $genericModel->orderBy($_defaultOrder, $_defaultOrderDir);
        }
        // paginate
        $genericModel = $genericModel->paginate($length);
        // construct json-source
        $nbElmt = $genericModel->total();
        $jsonSource['draw'] = $draw;
        $jsonSource['recordsTotal'] = $nbElmt;
        $jsonSource['recordsFiltered'] = $nbElmt;
        $jsonSource['rawdata'] = $genericModel;
        // return
        return $jsonSource;
    }
}
