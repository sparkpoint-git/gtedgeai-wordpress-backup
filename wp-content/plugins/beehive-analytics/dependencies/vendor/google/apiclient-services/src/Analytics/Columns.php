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
namespace Beehive\Google\Service\Analytics;

class Columns extends \Beehive\Google\Collection
{
    protected $collection_key = 'items';
    /**
     * @var string[]
     */
    public $attributeNames;
    /**
     * @var string
     */
    public $etag;
    protected $itemsType = Column::class;
    protected $itemsDataType = 'array';
    /**
     * @var string
     */
    public $kind;
    /**
     * @var int
     */
    public $totalResults;
    /**
     * @param string[]
     */
    public function setAttributeNames($attributeNames)
    {
        $this->attributeNames = $attributeNames;
    }
    /**
     * @return string[]
     */
    public function getAttributeNames()
    {
        return $this->attributeNames;
    }
    /**
     * @param string
     */
    public function setEtag($etag)
    {
        $this->etag = $etag;
    }
    /**
     * @return string
     */
    public function getEtag()
    {
        return $this->etag;
    }
    /**
     * @param Column[]
     */
    public function setItems($items)
    {
        $this->items = $items;
    }
    /**
     * @return Column[]
     */
    public function getItems()
    {
        return $this->items;
    }
    /**
     * @param string
     */
    public function setKind($kind)
    {
        $this->kind = $kind;
    }
    /**
     * @return string
     */
    public function getKind()
    {
        return $this->kind;
    }
    /**
     * @param int
     */
    public function setTotalResults($totalResults)
    {
        $this->totalResults = $totalResults;
    }
    /**
     * @return int
     */
    public function getTotalResults()
    {
        return $this->totalResults;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(Columns::class, 'Beehive\\Google_Service_Analytics_Columns');