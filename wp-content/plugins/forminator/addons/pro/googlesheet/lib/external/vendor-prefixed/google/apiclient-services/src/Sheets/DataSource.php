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

class DataSource extends \ForminatorGoogleAddon\Google\Collection
{
    protected $collection_key = 'calculatedColumns';
    protected $calculatedColumnsType = DataSourceColumn::class;
    protected $calculatedColumnsDataType = 'array';
    /**
     * @var string
     */
    public $dataSourceId;
    /**
     * @var int
     */
    public $sheetId;
    protected $specType = DataSourceSpec::class;
    protected $specDataType = '';
    /**
     * @param DataSourceColumn[]
     */
    public function setCalculatedColumns($calculatedColumns)
    {
        $this->calculatedColumns = $calculatedColumns;
    }
    /**
     * @return DataSourceColumn[]
     */
    public function getCalculatedColumns()
    {
        return $this->calculatedColumns;
    }
    /**
     * @param string
     */
    public function setDataSourceId($dataSourceId)
    {
        $this->dataSourceId = $dataSourceId;
    }
    /**
     * @return string
     */
    public function getDataSourceId()
    {
        return $this->dataSourceId;
    }
    /**
     * @param int
     */
    public function setSheetId($sheetId)
    {
        $this->sheetId = $sheetId;
    }
    /**
     * @return int
     */
    public function getSheetId()
    {
        return $this->sheetId;
    }
    /**
     * @param DataSourceSpec
     */
    public function setSpec(DataSourceSpec $spec)
    {
        $this->spec = $spec;
    }
    /**
     * @return DataSourceSpec
     */
    public function getSpec()
    {
        return $this->spec;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(DataSource::class, 'ForminatorGoogleAddon\\Google_Service_Sheets_DataSource');