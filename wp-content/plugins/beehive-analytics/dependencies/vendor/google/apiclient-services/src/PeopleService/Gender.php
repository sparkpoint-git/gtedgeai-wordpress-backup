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

class Gender extends \Beehive\Google\Model
{
    /**
     * @var string
     */
    public $addressMeAs;
    /**
     * @var string
     */
    public $formattedValue;
    protected $metadataType = FieldMetadata::class;
    protected $metadataDataType = '';
    /**
     * @var string
     */
    public $value;
    /**
     * @param string
     */
    public function setAddressMeAs($addressMeAs)
    {
        $this->addressMeAs = $addressMeAs;
    }
    /**
     * @return string
     */
    public function getAddressMeAs()
    {
        return $this->addressMeAs;
    }
    /**
     * @param string
     */
    public function setFormattedValue($formattedValue)
    {
        $this->formattedValue = $formattedValue;
    }
    /**
     * @return string
     */
    public function getFormattedValue()
    {
        return $this->formattedValue;
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
    public function setValue($value)
    {
        $this->value = $value;
    }
    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(Gender::class, 'Beehive\\Google_Service_PeopleService_Gender');