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

class ContactToCreate extends \Beehive\Google\Model
{
    protected $contactPersonType = Person::class;
    protected $contactPersonDataType = '';
    /**
     * @param Person
     */
    public function setContactPerson(Person $contactPerson)
    {
        $this->contactPerson = $contactPerson;
    }
    /**
     * @return Person
     */
    public function getContactPerson()
    {
        return $this->contactPerson;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(ContactToCreate::class, 'Beehive\\Google_Service_PeopleService_ContactToCreate');