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

class PivotHeaderEntry extends \Beehive\Google\Collection
{
    protected $collection_key = 'dimensionValues';
    /**
     * @var string[]
     */
    public $dimensionNames;
    /**
     * @var string[]
     */
    public $dimensionValues;
    protected $metricType = MetricHeaderEntry::class;
    protected $metricDataType = '';
    /**
     * @param string[]
     */
    public function setDimensionNames($dimensionNames)
    {
        $this->dimensionNames = $dimensionNames;
    }
    /**
     * @return string[]
     */
    public function getDimensionNames()
    {
        return $this->dimensionNames;
    }
    /**
     * @param string[]
     */
    public function setDimensionValues($dimensionValues)
    {
        $this->dimensionValues = $dimensionValues;
    }
    /**
     * @return string[]
     */
    public function getDimensionValues()
    {
        return $this->dimensionValues;
    }
    /**
     * @param MetricHeaderEntry
     */
    public function setMetric(MetricHeaderEntry $metric)
    {
        $this->metric = $metric;
    }
    /**
     * @return MetricHeaderEntry
     */
    public function getMetric()
    {
        return $this->metric;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(PivotHeaderEntry::class, 'Beehive\\Google_Service_AnalyticsReporting_PivotHeaderEntry');