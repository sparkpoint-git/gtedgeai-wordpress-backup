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

class MetricCompatibility extends \Beehive\Google\Model
{
    /**
     * @var string
     */
    public $compatibility;
    protected $metricMetadataType = MetricMetadata::class;
    protected $metricMetadataDataType = '';
    /**
     * @param string
     */
    public function setCompatibility($compatibility)
    {
        $this->compatibility = $compatibility;
    }
    /**
     * @return string
     */
    public function getCompatibility()
    {
        return $this->compatibility;
    }
    /**
     * @param MetricMetadata
     */
    public function setMetricMetadata(MetricMetadata $metricMetadata)
    {
        $this->metricMetadata = $metricMetadata;
    }
    /**
     * @return MetricMetadata
     */
    public function getMetricMetadata()
    {
        return $this->metricMetadata;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(MetricCompatibility::class, 'Beehive\\Google_Service_AnalyticsData_MetricCompatibility');