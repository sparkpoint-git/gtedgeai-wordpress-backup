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
namespace Beehive\Google\Service\PeopleService;

class ListDirectoryPeopleResponse extends \Beehive\Google\Collection
{
    protected $collection_key = 'people';
    /**
     * @var string
     */
    public $nextPageToken;
    /**
     * @var string
     */
    public $nextSyncToken;
    protected $peopleType = Person::class;
    protected $peopleDataType = 'array';
    /**
     * @param string
     */
    public function setNextPageToken($nextPageToken)
    {
        $this->nextPageToken = $nextPageToken;
    }
    /**
     * @return string
     */
    public function getNextPageToken()
    {
        return $this->nextPageToken;
    }
    /**
     * @param string
     */
    public function setNextSyncToken($nextSyncToken)
    {
        $this->nextSyncToken = $nextSyncToken;
    }
    /**
     * @return string
     */
    public function getNextSyncToken()
    {
        return $this->nextSyncToken;
    }
    /**
     * @param Person[]
     */
    public function setPeople($people)
    {
        $this->people = $people;
    }
    /**
     * @return Person[]
     */
    public function getPeople()
    {
        return $this->people;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(ListDirectoryPeopleResponse::class, 'Beehive\\Google_Service_PeopleService_ListDirectoryPeopleResponse');