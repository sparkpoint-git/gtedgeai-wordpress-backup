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

class EntityAdWordsLink extends \Beehive\Google\Collection
{
    protected $collection_key = 'profileIds';
    protected $adWordsAccountsType = AdWordsAccount::class;
    protected $adWordsAccountsDataType = 'array';
    protected $entityType = EntityAdWordsLinkEntity::class;
    protected $entityDataType = '';
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $kind;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string[]
     */
    public $profileIds;
    /**
     * @var string
     */
    public $selfLink;
    /**
     * @param AdWordsAccount[]
     */
    public function setAdWordsAccounts($adWordsAccounts)
    {
        $this->adWordsAccounts = $adWordsAccounts;
    }
    /**
     * @return AdWordsAccount[]
     */
    public function getAdWordsAccounts()
    {
        return $this->adWordsAccounts;
    }
    /**
     * @param EntityAdWordsLinkEntity
     */
    public function setEntity(EntityAdWordsLinkEntity $entity)
    {
        $this->entity = $entity;
    }
    /**
     * @return EntityAdWordsLinkEntity
     */
    public function getEntity()
    {
        return $this->entity;
    }
    /**
     * @param string
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
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
    /**
     * @param string[]
     */
    public function setProfileIds($profileIds)
    {
        $this->profileIds = $profileIds;
    }
    /**
     * @return string[]
     */
    public function getProfileIds()
    {
        return $this->profileIds;
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
\class_alias(EntityAdWordsLink::class, 'Beehive\\Google_Service_Analytics_EntityAdWordsLink');