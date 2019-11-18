<?php

namespace App\Repositories;

use App\Models\ContactMessage;
use Mail, Response;
use Suitcore\Models\SuitModel;
use Suitcore\Models\SuitTranslation;
use Suitcore\Repositories\SuitRepository;

class ContactMessageRepository extends SuitRepository
{
    public function __construct()
    {
        $this->mainModel = new ContactMessage;
    }

    /**
     * Display object list as JSON text that suitable for datatable frontend needs
     * @param  array $param
     * @return string jsonOutput
     **/
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
            if (is_array($columnFormatted) && 
                isset($columnFormatted['menu']) && 
                isset($columnFormatted['menu_without_delete'])) {
                try {
                    if ($obj->status != ContactMessage::MESSAGE_REPLIED) 
                        $tmpRow[] = str_replace('#id#', $obj->getAttribute('id'), $columnFormatted['menu']);
                    else  
                        $tmpRow[] = str_replace('#id#', $obj->getAttribute('id'), $columnFormatted['menu_without_delete']);
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

    public function sendReply($id, $param, ContactMessage &$contactus)
    {
        $contactus = ContactMessage::find($id);
        if ($contactus == null) return false;
        $result = false;
        if (isset($param['reply']) && !empty($param['reply'])) {
            // TO DO : Should be added reply text and reply datetime
            // $contactus->reply = Input::get('reply');
            // $contactus->reply_datetime = date('Y-m-d H:i:s');
            $messageReply = $param['reply'];
            $contactus->status = ContactMessage::MESSAGE_REPLIED;
            $contactus->reply = $messageReply;
            $result = $contactus->save();
            if ($result) {
                try {
	                // Send Reply via Email
	                $emailTo = $contactus->sender_email;
                    $sent = Mail::send('emails.contactmessagereply',
                        ['contactus' => $contactus],
                        function($message) use ($emailTo) {
                            $message->to($emailTo)->subject('[Talentsaga] Contact Message Reply');
                    });
                    $result = true;
                } catch (Exception $e) { }
            }
        }
        return $result;
    }
}
