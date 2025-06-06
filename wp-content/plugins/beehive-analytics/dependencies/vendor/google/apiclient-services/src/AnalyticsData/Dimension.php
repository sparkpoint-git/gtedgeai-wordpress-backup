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

class Dimension extends \Beehive\Google\Model
{
    protected $dimensionExpressionType = DimensionExpression::class;
    protected $dimensionExpressionDataType = '';
    /**
     * @var string
     */
    public $name;
    /**
     * @param DimensionExpression
     */
    public function setDimensionExpression(DimensionExpression $dimensionExpression)
    {
        $this->dimensionExpression = $dimensionExpression;
    }
    /**
     * @return DimensionExpression
     */
    public function getDimensionExpression()
    {
        return $this->dimensionExpression;
    }
    /**
     * @param string
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(Dimension::class, 'Beehive\\Google_Service_AnalyticsData_Dimension');