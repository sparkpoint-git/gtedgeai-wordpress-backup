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
namespace Beehive\Google\Service\Analytics\Resource;

use Beehive\Google\Service\Analytics\RemarketingAudience;
use Beehive\Google\Service\Analytics\RemarketingAudiences;
/**
 * The "remarketingAudience" collection of methods.
 * Typical usage is:
 *  <code>
 *   $analyticsService = new Google\Service\Analytics(...);
 *   $remarketingAudience = $analyticsService->management_remarketingAudience;
 *  </code>
 */
class ManagementRemarketingAudience extends \Beehive\Google\Service\Resource
{
    /**
     * Delete a remarketing audience. (remarketingAudience.delete)
     *
     * @param string $accountId Account ID to which the remarketing audience
     * belongs.
     * @param string $webPropertyId Web property ID to which the remarketing
     * audience belongs.
     * @param string $remarketingAudienceId The ID of the remarketing audience to
     * delete.
     * @param array $optParams Optional parameters.
     * @throws \Google\Service\Exception
     */
    public function delete($accountId, $webPropertyId, $remarketingAudienceId, $optParams = [])
    {
        $params = ['accountId' => $accountId, 'webPropertyId' => $webPropertyId, 'remarketingAudienceId' => $remarketingAudienceId];
        $params = \array_merge($params, $optParams);
        return $this->call('delete', [$params]);
    }
    /**
     * Gets a remarketing audience to which the user has access.
     * (remarketingAudience.get)
     *
     * @param string $accountId The account ID of the remarketing audience to
     * retrieve.
     * @param string $webPropertyId The web property ID of the remarketing audience
     * to retrieve.
     * @param string $remarketingAudienceId The ID of the remarketing audience to
     * retrieve.
     * @param array $optParams Optional parameters.
     * @return RemarketingAudience
     * @throws \Google\Service\Exception
     */
    public function get($accountId, $webPropertyId, $remarketingAudienceId, $optParams = [])
    {
        $params = ['accountId' => $accountId, 'webPropertyId' => $webPropertyId, 'remarketingAudienceId' => $remarketingAudienceId];
        $params = \array_merge($params, $optParams);
        return $this->call('get', [$params], RemarketingAudience::class);
    }
    /**
     * Creates a new remarketing audience. (remarketingAudience.insert)
     *
     * @param string $accountId The account ID for which to create the remarketing
     * audience.
     * @param string $webPropertyId Web property ID for which to create the
     * remarketing audience.
     * @param RemarketingAudience $postBody
     * @param array $optParams Optional parameters.
     * @return RemarketingAudience
     * @throws \Google\Service\Exception
     */
    public function insert($accountId, $webPropertyId, RemarketingAudience $postBody, $optParams = [])
    {
        $params = ['accountId' => $accountId, 'webPropertyId' => $webPropertyId, 'postBody' => $postBody];
        $params = \array_merge($params, $optParams);
        return $this->call('insert', [$params], RemarketingAudience::class);
    }
    /**
     * Lists remarketing audiences to which the user has access.
     * (remarketingAudience.listManagementRemarketingAudience)
     *
     * @param string $accountId The account ID of the remarketing audiences to
     * retrieve.
     * @param string $webPropertyId The web property ID of the remarketing audiences
     * to retrieve.
     * @param array $optParams Optional parameters.
     *
     * @opt_param int max-results The maximum number of remarketing audiences to
     * include in this response.
     * @opt_param int start-index An index of the first entity to retrieve. Use this
     * parameter as a pagination mechanism along with the max-results parameter.
     * @opt_param string type
     * @return RemarketingAudiences
     * @throws \Google\Service\Exception
     */
    public function listManagementRemarketingAudience($accountId, $webPropertyId, $optParams = [])
    {
        $params = ['accountId' => $accountId, 'webPropertyId' => $webPropertyId];
        $params = \array_merge($params, $optParams);
        return $this->call('list', [$params], RemarketingAudiences::class);
    }
    /**
     * Updates an existing remarketing audience. This method supports patch
     * semantics. (remarketingAudience.patch)
     *
     * @param string $accountId The account ID of the remarketing audience to
     * update.
     * @param string $webPropertyId The web property ID of the remarketing audience
     * to update.
     * @param string $remarketingAudienceId The ID of the remarketing audience to
     * update.
     * @param RemarketingAudience $postBody
     * @param array $optParams Optional parameters.
     * @return RemarketingAudience
     * @throws \Google\Service\Exception
     */
    public function patch($accountId, $webPropertyId, $remarketingAudienceId, RemarketingAudience $postBody, $optParams = [])
    {
        $params = ['accountId' => $accountId, 'webPropertyId' => $webPropertyId, 'remarketingAudienceId' => $remarketingAudienceId, 'postBody' => $postBody];
        $params = \array_merge($params, $optParams);
        return $this->call('patch', [$params], RemarketingAudience::class);
    }
    /**
     * Updates an existing remarketing audience. (remarketingAudience.update)
     *
     * @param string $accountId The account ID of the remarketing audience to
     * update.
     * @param string $webPropertyId The web property ID of the remarketing audience
     * to update.
     * @param string $remarketingAudienceId The ID of the remarketing audience to
     * update.
     * @param RemarketingAudience $postBody
     * @param array $optParams Optional parameters.
     * @return RemarketingAudience
     * @throws \Google\Service\Exception
     */
    public function update($accountId, $webPropertyId, $remarketingAudienceId, RemarketingAudience $postBody, $optParams = [])
    {
        $params = ['accountId' => $accountId, 'webPropertyId' => $webPropertyId, 'remarketingAudienceId' => $remarketingAudienceId, 'postBody' => $postBody];
        $params = \array_merge($params, $optParams);
        return $this->call('update', [$params], RemarketingAudience::class);
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(ManagementRemarketingAudience::class, 'Beehive\\Google_Service_Analytics_Resource_ManagementRemarketingAudience');