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

class DataSourceChartProperties extends \ForminatorGoogleAddon\Google\Model
{
    protected $dataExecutionStatusType = DataExecutionStatus::class;
    protected $dataExecutionStatusDataType = '';
    /**
     * @var string
     */
    public $dataSourceId;
    /**
     * @param DataExecutionStatus
     */
    public function setDataExecutionStatus(DataExecutionStatus $dataExecutionStatus)
    {
        $this->dataExecutionStatus = $dataExecutionStatus;
    }
    /**
     * @return DataExecutionStatus
     */
    public function getDataExecutionStatus()
    {
        return $this->dataExecutionStatus;
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
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(DataSourceChartProperties::class, 'ForminatorGoogleAddon\\Google_Service_Sheets_DataSourceChartProperties');