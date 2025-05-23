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

class Segment extends \Beehive\Google\Model
{
    protected $dynamicSegmentType = DynamicSegment::class;
    protected $dynamicSegmentDataType = '';
    /**
     * @var string
     */
    public $segmentId;
    /**
     * @param DynamicSegment
     */
    public function setDynamicSegment(DynamicSegment $dynamicSegment)
    {
        $this->dynamicSegment = $dynamicSegment;
    }
    /**
     * @return DynamicSegment
     */
    public function getDynamicSegment()
    {
        return $this->dynamicSegment;
    }
    /**
     * @param string
     */
    public function setSegmentId($segmentId)
    {
        $this->segmentId = $segmentId;
    }
    /**
     * @return string
     */
    public function getSegmentId()
    {
        return $this->segmentId;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(Segment::class, 'Beehive\\Google_Service_AnalyticsReporting_Segment');