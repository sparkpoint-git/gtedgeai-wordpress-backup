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
namespace Beehive\Google\Service\AnalyticsReporting;

class SearchUserActivityResponse extends \Beehive\Google\Collection
{
    protected $collection_key = 'sessions';
    /**
     * @var string
     */
    public $nextPageToken;
    public $sampleRate;
    protected $sessionsType = UserActivitySession::class;
    protected $sessionsDataType = 'array';
    /**
     * @var int
     */
    public $totalRows;
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
    public function setSampleRate($sampleRate)
    {
        $this->sampleRate = $sampleRate;
    }
    public function getSampleRate()
    {
        return $this->sampleRate;
    }
    /**
     * @param UserActivitySession[]
     */
    public function setSessions($sessions)
    {
        $this->sessions = $sessions;
    }
    /**
     * @return UserActivitySession[]
     */
    public function getSessions()
    {
        return $this->sessions;
    }
    /**
     * @param int
     */
    public function setTotalRows($totalRows)
    {
        $this->totalRows = $totalRows;
    }
    /**
     * @return int
     */
    public function getTotalRows()
    {
        return $this->totalRows;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(SearchUserActivityResponse::class, 'Beehive\\Google_Service_AnalyticsReporting_SearchUserActivityResponse');