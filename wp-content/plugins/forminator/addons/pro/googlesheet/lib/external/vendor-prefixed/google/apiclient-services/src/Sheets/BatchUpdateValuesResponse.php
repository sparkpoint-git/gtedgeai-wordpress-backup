<?php

/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */
namespace ForminatorGoogleAddon\Google\Service\Sheets;

class BatchUpdateValuesResponse extends \ForminatorGoogleAddon\Google\Collection
{
    protected $collection_key = 'responses';
    protected $responsesType = UpdateValuesResponse::class;
    protected $responsesDataType = 'array';
    /**
     * @var string
     */
    public $spreadsheetId;
    /**
     * @var int
     */
    public $totalUpdatedCells;
    /**
     * @var int
     */
    public $totalUpdatedColumns;
    /**
     * @var int
     */
    public $totalUpdatedRows;
    /**
     * @var int
     */
    public $totalUpdatedSheets;
    /**
     * @param UpdateValuesResponse[]
     */
    public function setResponses($responses)
    {
        $this->responses = $responses;
    }
    /**
     * @return UpdateValuesResponse[]
     */
    public function getResponses()
    {
        return $this->responses;
    }
    /**
     * @param string
     */
    public function setSpreadsheetId($spreadsheetId)
    {
        $this->spreadsheetId = $spreadsheetId;
    }
    /**
     * @return string
     */
    public function getSpreadsheetId()
    {
        return $this->spreadsheetId;
    }
    /**
     * @param int
     */
    public function setTotalUpdatedCells($totalUpdatedCells)
    {
        $this->totalUpdatedCells = $totalUpdatedCells;
    }
    /**
     * @return int
     */
    public function getTotalUpdatedCells()
    {
        return $this->totalUpdatedCells;
    }
    /**
     * @param int
     */
    public function setTotalUpdatedColumns($totalUpdatedColumns)
    {
        $this->totalUpdatedColumns = $totalUpdatedColumns;
    }
    /**
     * @return int
     */
    public function getTotalUpdatedColumns()
    {
        return $this->totalUpdatedColumns;
    }
    /**
     * @param int
     */
    public function setTotalUpdatedRows($totalUpdatedRows)
    {
        $this->totalUpdatedRows = $totalUpdatedRows;
    }
    /**
     * @return int
     */
    public function getTotalUpdatedRows()
    {
        return $this->totalUpdatedRows;
    }
    /**
     * @param int
     */
    public function setTotalUpdatedSheets($totalUpdatedSheets)
    {
        $this->totalUpdatedSheets = $totalUpdatedSheets;
    }
    /**
     * @return int
     */
    public function getTotalUpdatedSheets()
    {
        return $this->totalUpdatedSheets;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(BatchUpdateValuesResponse::class, 'ForminatorGoogleAddon\\Google_Service_Sheets_BatchUpdateValuesResponse');