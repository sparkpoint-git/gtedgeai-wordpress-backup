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

class DataValidationRule extends \ForminatorGoogleAddon\Google\Model
{
    protected $conditionType = BooleanCondition::class;
    protected $conditionDataType = '';
    /**
     * @var string
     */
    public $inputMessage;
    /**
     * @var bool
     */
    public $showCustomUi;
    /**
     * @var bool
     */
    public $strict;
    /**
     * @param BooleanCondition
     */
    public function setCondition(BooleanCondition $condition)
    {
        $this->condition = $condition;
    }
    /**
     * @return BooleanCondition
     */
    public function getCondition()
    {
        return $this->condition;
    }
    /**
     * @param string
     */
    public function setInputMessage($inputMessage)
    {
        $this->inputMessage = $inputMessage;
    }
    /**
     * @return string
     */
    public function getInputMessage()
    {
        return $this->inputMessage;
    }
    /**
     * @param bool
     */
    public function setShowCustomUi($showCustomUi)
    {
        $this->showCustomUi = $showCustomUi;
    }
    /**
     * @return bool
     */
    public function getShowCustomUi()
    {
        return $this->showCustomUi;
    }
    /**
     * @param bool
     */
    public function setStrict($strict)
    {
        $this->strict = $strict;
    }
    /**
     * @return bool
     */
    public function getStrict()
    {
        return $this->strict;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(DataValidationRule::class, 'ForminatorGoogleAddon\\Google_Service_Sheets_DataValidationRule');