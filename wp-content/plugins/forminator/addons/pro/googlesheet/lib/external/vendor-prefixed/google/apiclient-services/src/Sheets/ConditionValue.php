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

class ConditionValue extends \ForminatorGoogleAddon\Google\Model
{
    /**
     * @var string
     */
    public $relativeDate;
    /**
     * @var string
     */
    public $userEnteredValue;
    /**
     * @param string
     */
    public function setRelativeDate($relativeDate)
    {
        $this->relativeDate = $relativeDate;
    }
    /**
     * @return string
     */
    public function getRelativeDate()
    {
        return $this->relativeDate;
    }
    /**
     * @param string
     */
    public function setUserEnteredValue($userEnteredValue)
    {
        $this->userEnteredValue = $userEnteredValue;
    }
    /**
     * @return string
     */
    public function getUserEnteredValue()
    {
        return $this->userEnteredValue;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(ConditionValue::class, 'ForminatorGoogleAddon\\Google_Service_Sheets_ConditionValue');