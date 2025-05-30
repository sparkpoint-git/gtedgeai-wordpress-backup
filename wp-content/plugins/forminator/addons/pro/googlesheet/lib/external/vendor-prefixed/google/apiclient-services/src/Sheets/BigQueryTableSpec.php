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

class BigQueryTableSpec extends \ForminatorGoogleAddon\Google\Model
{
    /**
     * @var string
     */
    public $datasetId;
    /**
     * @var string
     */
    public $tableId;
    /**
     * @var string
     */
    public $tableProjectId;
    /**
     * @param string
     */
    public function setDatasetId($datasetId)
    {
        $this->datasetId = $datasetId;
    }
    /**
     * @return string
     */
    public function getDatasetId()
    {
        return $this->datasetId;
    }
    /**
     * @param string
     */
    public function setTableId($tableId)
    {
        $this->tableId = $tableId;
    }
    /**
     * @return string
     */
    public function getTableId()
    {
        return $this->tableId;
    }
    /**
     * @param string
     */
    public function setTableProjectId($tableProjectId)
    {
        $this->tableProjectId = $tableProjectId;
    }
    /**
     * @return string
     */
    public function getTableProjectId()
    {
        return $this->tableProjectId;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(BigQueryTableSpec::class, 'ForminatorGoogleAddon\\Google_Service_Sheets_BigQueryTableSpec');