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

class GoogleAnalyticsAdminV1betaAccessBetweenFilter extends \Beehive\Google\Model
{
    protected $fromValueType = GoogleAnalyticsAdminV1betaNumericValue::class;
    protected $fromValueDataType = '';
    protected $toValueType = GoogleAnalyticsAdminV1betaNumericValue::class;
    protected $toValueDataType = '';
    /**
     * @param GoogleAnalyticsAdminV1betaNumericValue
     */
    public function setFromValue(GoogleAnalyticsAdminV1betaNumericValue $fromValue)
    {
        $this->fromValue = $fromValue;
    }
    /**
     * @return GoogleAnalyticsAdminV1betaNumericValue
     */
    public function getFromValue()
    {
        return $this->fromValue;
    }
    /**
     * @param GoogleAnalyticsAdminV1betaNumericValue
     */
    public function setToValue(GoogleAnalyticsAdminV1betaNumericValue $toValue)
    {
        $this->toValue = $toValue;
    }
    /**
     * @return GoogleAnalyticsAdminV1betaNumericValue
     */
    public function getToValue()
    {
        return $this->toValue;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(GoogleAnalyticsAdminV1betaAccessBetweenFilter::class, 'Beehive\\Google_Service_GoogleAnalyticsAdmin_GoogleAnalyticsAdminV1betaAccessBetweenFilter');