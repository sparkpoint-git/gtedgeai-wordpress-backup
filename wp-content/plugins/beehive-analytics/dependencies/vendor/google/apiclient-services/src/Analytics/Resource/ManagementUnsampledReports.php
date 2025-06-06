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

use Beehive\Google\Service\Analytics\UnsampledReport;
use Beehive\Google\Service\Analytics\UnsampledReports;
/**
 * The "unsampledReports" collection of methods.
 * Typical usage is:
 *  <code>
 *   $analyticsService = new Google\Service\Analytics(...);
 *   $unsampledReports = $analyticsService->management_unsampledReports;
 *  </code>
 */
class ManagementUnsampledReports extends \Beehive\Google\Service\Resource
{
    /**
     * Deletes an unsampled report. (unsampledReports.delete)
     *
     * @param string $accountId Account ID to delete the unsampled report for.
     * @param string $webPropertyId Web property ID to delete the unsampled reports
     * for.
     * @param string $profileId View (Profile) ID to delete the unsampled report
     * for.
     * @param string $unsampledReportId ID of the unsampled report to be deleted.
     * @param array $optParams Optional parameters.
     * @throws \Google\Service\Exception
     */
    public function delete($accountId, $webPropertyId, $profileId, $unsampledReportId, $optParams = [])
    {
        $params = ['accountId' => $accountId, 'webPropertyId' => $webPropertyId, 'profileId' => $profileId, 'unsampledReportId' => $unsampledReportId];
        $params = \array_merge($params, $optParams);
        return $this->call('delete', [$params]);
    }
    /**
     * Returns a single unsampled report. (unsampledReports.get)
     *
     * @param string $accountId Account ID to retrieve unsampled report for.
     * @param string $webPropertyId Web property ID to retrieve unsampled reports
     * for.
     * @param string $profileId View (Profile) ID to retrieve unsampled report for.
     * @param string $unsampledReportId ID of the unsampled report to retrieve.
     * @param array $optParams Optional parameters.
     * @return UnsampledReport
     * @throws \Google\Service\Exception
     */
    public function get($accountId, $webPropertyId, $profileId, $unsampledReportId, $optParams = [])
    {
        $params = ['accountId' => $accountId, 'webPropertyId' => $webPropertyId, 'profileId' => $profileId, 'unsampledReportId' => $unsampledReportId];
        $params = \array_merge($params, $optParams);
        return $this->call('get', [$params], UnsampledReport::class);
    }
    /**
     * Create a new unsampled report. (unsampledReports.insert)
     *
     * @param string $accountId Account ID to create the unsampled report for.
     * @param string $webPropertyId Web property ID to create the unsampled report
     * for.
     * @param string $profileId View (Profile) ID to create the unsampled report
     * for.
     * @param UnsampledReport $postBody
     * @param array $optParams Optional parameters.
     * @return UnsampledReport
     * @throws \Google\Service\Exception
     */
    public function insert($accountId, $webPropertyId, $profileId, UnsampledReport $postBody, $optParams = [])
    {
        $params = ['accountId' => $accountId, 'webPropertyId' => $webPropertyId, 'profileId' => $profileId, 'postBody' => $postBody];
        $params = \array_merge($params, $optParams);
        return $this->call('insert', [$params], UnsampledReport::class);
    }
    /**
     * Lists unsampled reports to which the user has access.
     * (unsampledReports.listManagementUnsampledReports)
     *
     * @param string $accountId Account ID to retrieve unsampled reports for. Must
     * be a specific account ID, ~all is not supported.
     * @param string $webPropertyId Web property ID to retrieve unsampled reports
     * for. Must be a specific web property ID, ~all is not supported.
     * @param string $profileId View (Profile) ID to retrieve unsampled reports for.
     * Must be a specific view (profile) ID, ~all is not supported.
     * @param array $optParams Optional parameters.
     *
     * @opt_param int max-results The maximum number of unsampled reports to include
     * in this response.
     * @opt_param int start-index An index of the first unsampled report to
     * retrieve. Use this parameter as a pagination mechanism along with the max-
     * results parameter.
     * @return UnsampledReports
     * @throws \Google\Service\Exception
     */
    public function listManagementUnsampledReports($accountId, $webPropertyId, $profileId, $optParams = [])
    {
        $params = ['accountId' => $accountId, 'webPropertyId' => $webPropertyId, 'profileId' => $profileId];
        $params = \array_merge($params, $optParams);
        return $this->call('list', [$params], UnsampledReports::class);
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(ManagementUnsampledReports::class, 'Beehive\\Google_Service_Analytics_Resource_ManagementUnsampledReports');