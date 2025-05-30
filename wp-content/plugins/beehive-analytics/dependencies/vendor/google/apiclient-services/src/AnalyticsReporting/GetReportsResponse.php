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

class GetReportsResponse extends \Beehive\Google\Collection
{
    protected $collection_key = 'reports';
    /**
     * @var int
     */
    public $queryCost;
    protected $reportsType = Report::class;
    protected $reportsDataType = 'array';
    protected $resourceQuotasRemainingType = ResourceQuotasRemaining::class;
    protected $resourceQuotasRemainingDataType = '';
    /**
     * @param int
     */
    public function setQueryCost($queryCost)
    {
        $this->queryCost = $queryCost;
    }
    /**
     * @return int
     */
    public function getQueryCost()
    {
        return $this->queryCost;
    }
    /**
     * @param Report[]
     */
    public function setReports($reports)
    {
        $this->reports = $reports;
    }
    /**
     * @return Report[]
     */
    public function getReports()
    {
        return $this->reports;
    }
    /**
     * @param ResourceQuotasRemaining
     */
    public function setResourceQuotasRemaining(ResourceQuotasRemaining $resourceQuotasRemaining)
    {
        $this->resourceQuotasRemaining = $resourceQuotasRemaining;
    }
    /**
     * @return ResourceQuotasRemaining
     */
    public function getResourceQuotasRemaining()
    {
        return $this->resourceQuotasRemaining;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(GetReportsResponse::class, 'Beehive\\Google_Service_AnalyticsReporting_GetReportsResponse');