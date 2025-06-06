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
namespace Beehive\Google\Service\GoogleAnalyticsAdmin;

class GoogleAnalyticsAdminV1betaAccount extends \Beehive\Google\Model
{
    /**
     * @var string
     */
    public $createTime;
    /**
     * @var bool
     */
    public $deleted;
    /**
     * @var string
     */
    public $displayName;
    /**
     * @var string
     */
    public $gmpOrganization;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $regionCode;
    /**
     * @var string
     */
    public $updateTime;
    /**
     * @param string
     */
    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;
    }
    /**
     * @return string
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }
    /**
     * @param bool
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }
    /**
     * @return bool
     */
    public function getDeleted()
    {
        return $this->deleted;
    }
    /**
     * @param string
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }
    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }
    /**
     * @param string
     */
    public function setGmpOrganization($gmpOrganization)
    {
        $this->gmpOrganization = $gmpOrganization;
    }
    /**
     * @return string
     */
    public function getGmpOrganization()
    {
        return $this->gmpOrganization;
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
     * @param string
     */
    public function setRegionCode($regionCode)
    {
        $this->regionCode = $regionCode;
    }
    /**
     * @return string
     */
    public function getRegionCode()
    {
        return $this->regionCode;
    }
    /**
     * @param string
     */
    public function setUpdateTime($updateTime)
    {
        $this->updateTime = $updateTime;
    }
    /**
     * @return string
     */
    public function getUpdateTime()
    {
        return $this->updateTime;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(GoogleAnalyticsAdminV1betaAccount::class, 'Beehive\\Google_Service_GoogleAnalyticsAdmin_GoogleAnalyticsAdminV1betaAccount');