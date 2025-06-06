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

class SegmentSequenceStep extends \Beehive\Google\Collection
{
    protected $collection_key = 'orFiltersForSegment';
    /**
     * @var string
     */
    public $matchType;
    protected $orFiltersForSegmentType = OrFiltersForSegment::class;
    protected $orFiltersForSegmentDataType = 'array';
    /**
     * @param string
     */
    public function setMatchType($matchType)
    {
        $this->matchType = $matchType;
    }
    /**
     * @return string
     */
    public function getMatchType()
    {
        return $this->matchType;
    }
    /**
     * @param OrFiltersForSegment[]
     */
    public function setOrFiltersForSegment($orFiltersForSegment)
    {
        $this->orFiltersForSegment = $orFiltersForSegment;
    }
    /**
     * @return OrFiltersForSegment[]
     */
    public function getOrFiltersForSegment()
    {
        return $this->orFiltersForSegment;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(SegmentSequenceStep::class, 'Beehive\\Google_Service_AnalyticsReporting_SegmentSequenceStep');