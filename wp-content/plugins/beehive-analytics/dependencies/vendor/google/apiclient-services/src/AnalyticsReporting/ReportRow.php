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
namespace Beehive\Google\Service\AnalyticsReporting;

class ReportRow extends \Beehive\Google\Collection
{
    protected $collection_key = 'metrics';
    /**
     * @var string[]
     */
    public $dimensions;
    protected $metricsType = DateRangeValues::class;
    protected $metricsDataType = 'array';
    /**
     * @param string[]
     */
    public function setDimensions($dimensions)
    {
        $this->dimensions = $dimensions;
    }
    /**
     * @return string[]
     */
    public function getDimensions()
    {
        return $this->dimensions;
    }
    /**
     * @param DateRangeValues[]
     */
    public function setMetrics($metrics)
    {
        $this->metrics = $metrics;
    }
    /**
     * @return DateRangeValues[]
     */
    public function getMetrics()
    {
        return $this->metrics;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(ReportRow::class, 'Beehive\\Google_Service_AnalyticsReporting_ReportRow');