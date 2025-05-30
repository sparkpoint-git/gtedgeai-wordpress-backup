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
namespace Beehive\Google\Service\AnalyticsData;

class CheckCompatibilityResponse extends \Beehive\Google\Collection
{
    protected $collection_key = 'metricCompatibilities';
    protected $dimensionCompatibilitiesType = DimensionCompatibility::class;
    protected $dimensionCompatibilitiesDataType = 'array';
    protected $metricCompatibilitiesType = MetricCompatibility::class;
    protected $metricCompatibilitiesDataType = 'array';
    /**
     * @param DimensionCompatibility[]
     */
    public function setDimensionCompatibilities($dimensionCompatibilities)
    {
        $this->dimensionCompatibilities = $dimensionCompatibilities;
    }
    /**
     * @return DimensionCompatibility[]
     */
    public function getDimensionCompatibilities()
    {
        return $this->dimensionCompatibilities;
    }
    /**
     * @param MetricCompatibility[]
     */
    public function setMetricCompatibilities($metricCompatibilities)
    {
        $this->metricCompatibilities = $metricCompatibilities;
    }
    /**
     * @return MetricCompatibility[]
     */
    public function getMetricCompatibilities()
    {
        return $this->metricCompatibilities;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(CheckCompatibilityResponse::class, 'Beehive\\Google_Service_AnalyticsData_CheckCompatibilityResponse');