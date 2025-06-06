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

class CoverPhoto extends \Beehive\Google\Model
{
    /**
     * @var bool
     */
    public $default;
    protected $metadataType = FieldMetadata::class;
    protected $metadataDataType = '';
    /**
     * @var string
     */
    public $url;
    /**
     * @param bool
     */
    public function setDefault($default)
    {
        $this->default = $default;
    }
    /**
     * @return bool
     */
    public function getDefault()
    {
        return $this->default;
    }
    /**
     * @param FieldMetadata
     */
    public function setMetadata(FieldMetadata $metadata)
    {
        $this->metadata = $metadata;
    }
    /**
     * @return FieldMetadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
    /**
     * @param string
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }
    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(CoverPhoto::class, 'Beehive\\Google_Service_PeopleService_CoverPhoto');