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

class ColorStyle extends \ForminatorGoogleAddon\Google\Model
{
    protected $rgbColorType = Color::class;
    protected $rgbColorDataType = '';
    /**
     * @var string
     */
    public $themeColor;
    /**
     * @param Color
     */
    public function setRgbColor(Color $rgbColor)
    {
        $this->rgbColor = $rgbColor;
    }
    /**
     * @return Color
     */
    public function getRgbColor()
    {
        return $this->rgbColor;
    }
    /**
     * @param string
     */
    public function setThemeColor($themeColor)
    {
        $this->themeColor = $themeColor;
    }
    /**
     * @return string
     */
    public function getThemeColor()
    {
        return $this->themeColor;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(ColorStyle::class, 'ForminatorGoogleAddon\\Google_Service_Sheets_ColorStyle');