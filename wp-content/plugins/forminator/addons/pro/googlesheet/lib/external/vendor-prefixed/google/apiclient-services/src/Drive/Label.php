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

class Label extends \ForminatorGoogleAddon\Google\Model
{
    protected $fieldsType = LabelField::class;
    protected $fieldsDataType = 'map';
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
    public $revisionId;
    /**
     * @param LabelField[]
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }
    /**
     * @return LabelField[]
     */
    public function getFields()
    {
        return $this->fields;
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
    public function setRevisionId($revisionId)
    {
        $this->revisionId = $revisionId;
    }
    /**
     * @return string
     */
    public function getRevisionId()
    {
        return $this->revisionId;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(Label::class, 'ForminatorGoogleAddon\\Google_Service_Drive_Label');