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

class EmbeddedObjectBorder extends \ForminatorGoogleAddon\Google\Model
{
    protected $colorType = Color::class;
    protected $colorDataType = '';
    protected $colorStyleType = ColorStyle::class;
    protected $colorStyleDataType = '';
    /**
     * @param Color
     */
    public function setColor(Color $color)
    {
        $this->color = $color;
    }
    /**
     * @return Color
     */
    public function getColor()
    {
        return $this->color;
    }
    /**
     * @param ColorStyle
     */
    public function setColorStyle(ColorStyle $colorStyle)
    {
        $this->colorStyle = $colorStyle;
    }
    /**
     * @return ColorStyle
     */
    public function getColorStyle()
    {
        return $this->colorStyle;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(EmbeddedObjectBorder::class, 'ForminatorGoogleAddon\\Google_Service_Sheets_EmbeddedObjectBorder');