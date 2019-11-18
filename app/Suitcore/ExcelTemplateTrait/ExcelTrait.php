<?php

namespace Suitcore\ExcelTemplateTrait;

use PHPExcel_Cell_DataValidation;
use PHPExcel_NamedRange;

trait ExcelTrait
{
    /**
     * Generate data for excel file.
     * @param  array $dataSource
     * @return array
     */
    public function generateExcelData($dataSource)
    {
        $dataTable = [];
        foreach ($dataSource as $data) {
            if ($data['type'] == 'references') {
                array_push($dataTable, $data['references']);
            } else {
                array_push($dataTable, []);
            }
        }
        return $dataTable;
    }

    /**
     * Set table header in Excel file.
     * @param [type] $sheet
     * @param array  $dataSource
     */
    public function setTableHeader($sheet, $dataSource = [])
    {
        $column = 'A';
        $headers = array_keys($dataSource);
        foreach ($headers as $header) {
            $sheet->setCellValue($column . '1', $header);
            $column++;
        }
    }

    /**
     * Set cell validation in excel file.
     * @param [type]  $sheet
     * @param array   $dataSource
     * @param int $firstRow
     * @param int $numOfRow
     */
    public function setCellValidation($sheet, $dataSource = [], $firstRow = 2, $numOfRow = 100)
    {
        $column = 'A';
        foreach ($dataSource as $data) {
            if (!empty($data)) {
                for ($i = $firstRow; $i <= $numOfRow; $i++) {
                    $cellValidation = $sheet->getCell(sprintf('%s%s', $column, $i))->getDataValidation();
                    $cellValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
                    $cellValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                    $cellValidation->setAllowBlank(false);
                    $cellValidation->setShowInputMessage(true);
                    $cellValidation->setShowErrorMessage(true);
                    $cellValidation->setShowDropDown(true);
                    $cellValidation->setErrorTitle('Your input is invalid');
                    $cellValidation->setError('Value is does not exist in references sheet.');
                    $cellValidation->setPromptTitle('Choose from the list');
                    $cellValidation->setFormula1($column);
                }
            }
            $column++;
        }
    }

    /**
     * Insert an array to Excel file.
     * @param [type]  $sheet
     * @param array  $tableData
     * @param int $firstRow
     * @param bool $setRangeName
     */
    public function setExcelData($sheet, $tableData = [], $firstRow = 2, $setRangeName = false)
    {
        $column = 'A';
        foreach ($tableData as $data) {
            $row = $firstRow;
            foreach ($data as $value) {
                $cell = sprintf('%s%s', $column, $row);
                $sheet->setCellValue($cell, $value);
                $row++;
            }
            if ($setRangeName) {
                if (!empty($data)) {
                    // Set cell range name.
                    $sheet->_parent->addNamedRange(
                        new PHPExcel_NamedRange($column, $sheet, sprintf('%s%s:%s%s', $column, 2, $column, $row - 1))
                    );
                }
            }
            $column++;
        }
    }

    /**
     * Get header of imported file.
     * @param  [type] $import
     * @param  int $sheetNumber
     * @return array
     */
    public function getImportedFileHeader($import, $sheetNumber)
    {
        return array_keys($import->first()[$sheetNumber - 1]->toArray());
    }

    /**
     * Check if imported file has same column with database table.
     * @param  array  $sourceHeader
     * @param  array  $tableHeader
     * @return bool
     */
    public function isHeaderValid($sourceHeader, $tableHeader)
    {
        foreach ($tableHeader as $key => $value) {
            if (!in_array($key, $sourceHeader)) {
                return false;
            }
        }
        return true;
    }
}
