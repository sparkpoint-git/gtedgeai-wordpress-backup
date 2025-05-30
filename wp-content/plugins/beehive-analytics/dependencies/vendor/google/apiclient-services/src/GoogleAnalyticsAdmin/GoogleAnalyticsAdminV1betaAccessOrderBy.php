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
namespace Beehive\Google\Service\GoogleAnalyticsAdmin;

class GoogleAnalyticsAdminV1betaAccessOrderBy extends \Beehive\Google\Model
{
    /**
     * @var bool
     */
    public $desc;
    protected $dimensionType = GoogleAnalyticsAdminV1betaAccessOrderByDimensionOrderBy::class;
    protected $dimensionDataType = '';
    protected $metricType = GoogleAnalyticsAdminV1betaAccessOrderByMetricOrderBy::class;
    protected $metricDataType = '';
    /**
     * @param bool
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;
    }
    /**
     * @return bool
     */
    public function getDesc()
    {
        return $this->desc;
    }
    /**
     * @param GoogleAnalyticsAdminV1betaAccessOrderByDimensionOrderBy
     */
    public function setDimension(GoogleAnalyticsAdminV1betaAccessOrderByDimensionOrderBy $dimension)
    {
        $this->dimension = $dimension;
    }
    /**
     * @return GoogleAnalyticsAdminV1betaAccessOrderByDimensionOrderBy
     */
    public function getDimension()
    {
        return $this->dimension;
    }
    /**
     * @param GoogleAnalyticsAdminV1betaAccessOrderByMetricOrderBy
     */
    public function setMetric(GoogleAnalyticsAdminV1betaAccessOrderByMetricOrderBy $metric)
    {
        $this->metric = $metric;
    }
    /**
     * @return GoogleAnalyticsAdminV1betaAccessOrderByMetricOrderBy
     */
    public function getMetric()
    {
        return $this->metric;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(GoogleAnalyticsAdminV1betaAccessOrderBy::class, 'Beehive\\Google_Service_GoogleAnalyticsAdmin_GoogleAnalyticsAdminV1betaAccessOrderBy');