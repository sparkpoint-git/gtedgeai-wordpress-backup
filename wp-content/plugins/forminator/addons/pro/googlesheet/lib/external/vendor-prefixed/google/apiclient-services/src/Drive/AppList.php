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
namespace ForminatorGoogleAddon\Google\Service\Drive;

class AppList extends \ForminatorGoogleAddon\Google\Collection
{
    protected $collection_key = 'items';
    /**
     * @var string[]
     */
    public $defaultAppIds;
    protected $itemsType = App::class;
    protected $itemsDataType = 'array';
    /**
     * @var string
     */
    public $kind;
    /**
     * @var string
     */
    public $selfLink;
    /**
     * @param string[]
     */
    public function setDefaultAppIds($defaultAppIds)
    {
        $this->defaultAppIds = $defaultAppIds;
    }
    /**
     * @return string[]
     */
    public function getDefaultAppIds()
    {
        return $this->defaultAppIds;
    }
    /**
     * @param App[]
     */
    public function setItems($items)
    {
        $this->items = $items;
    }
    /**
     * @return App[]
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
     * @param string
     */
    public function setSelfLink($selfLink)
    {
        $this->selfLink = $selfLink;
    }
    /**
     * @return string
     */
    public function getSelfLink()
    {
        return $this->selfLink;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(AppList::class, 'ForminatorGoogleAddon\\Google_Service_Drive_AppList');