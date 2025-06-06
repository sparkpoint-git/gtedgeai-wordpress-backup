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

class TextFormatRun extends \ForminatorGoogleAddon\Google\Model
{
    protected $formatType = TextFormat::class;
    protected $formatDataType = '';
    /**
     * @var int
     */
    public $startIndex;
    /**
     * @param TextFormat
     */
    public function setFormat(TextFormat $format)
    {
        $this->format = $format;
    }
    /**
     * @return TextFormat
     */
    public function getFormat()
    {
        return $this->format;
    }
    /**
     * @param int
     */
    public function setStartIndex($startIndex)
    {
        $this->startIndex = $startIndex;
    }
    /**
     * @return int
     */
    public function getStartIndex()
    {
        return $this->startIndex;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(TextFormatRun::class, 'ForminatorGoogleAddon\\Google_Service_Sheets_TextFormatRun');