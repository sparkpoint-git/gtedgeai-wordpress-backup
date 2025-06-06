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

class GoogleAnalyticsAdminV1betaKeyEventDefaultValue extends \Beehive\Google\Model
{
    /**
     * @var string
     */
    public $currencyCode;
    public $numericValue;
    /**
     * @param string
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;
    }
    /**
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }
    public function setNumericValue($numericValue)
    {
        $this->numericValue = $numericValue;
    }
    public function getNumericValue()
    {
        return $this->numericValue;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(GoogleAnalyticsAdminV1betaKeyEventDefaultValue::class, 'Beehive\\Google_Service_GoogleAnalyticsAdmin_GoogleAnalyticsAdminV1betaKeyEventDefaultValue');