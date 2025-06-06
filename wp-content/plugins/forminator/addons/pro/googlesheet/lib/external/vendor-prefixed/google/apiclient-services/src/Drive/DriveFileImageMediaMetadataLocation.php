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

class DriveFileImageMediaMetadataLocation extends \ForminatorGoogleAddon\Google\Model
{
    public $altitude;
    public $latitude;
    public $longitude;
    public function setAltitude($altitude)
    {
        $this->altitude = $altitude;
    }
    public function getAltitude()
    {
        return $this->altitude;
    }
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }
    public function getLatitude()
    {
        return $this->latitude;
    }
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }
    public function getLongitude()
    {
        return $this->longitude;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(DriveFileImageMediaMetadataLocation::class, 'ForminatorGoogleAddon\\Google_Service_Drive_DriveFileImageMediaMetadataLocation');