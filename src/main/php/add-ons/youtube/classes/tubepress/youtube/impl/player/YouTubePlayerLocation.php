<?php
/**
 * Copyright 2006 - 2014 TubePress LLC (http://tubepress.com)
 *
 * This file is part of TubePress (http://tubepress.com)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 *
 */
class tubepress_youtube_impl_player_YouTubePlayerLocation implements tubepress_core_api_player_PlayerLocationInterface
{
    /**
     * @return string[] The paths for the template factory.
     *
     * @api
     * @since 4.0.0
     */
    public function getPathsForTemplateFactory()
    {
        return array();
    }

    /**
     * @return string The name of this playerLocation. Never empty or null. All alphanumerics and dashes.
     *
     * @api
     * @since 4.0.0
     */
    public final function getName()
    {
        return 'youtube';
    }

    /**
     * @param tubepress_core_api_environment_EnvironmentInterface $environment
     *
     * @return tubepress_core_api_url_UrlInterface Gets the URL to this player location's JS init script.
     *
     * @api
     * @since 4.0.0
     */
    public function getPlayerJsUrl(tubepress_core_api_environment_EnvironmentInterface $environment)
    {
        $sysUrl = $environment->getBaseUrl()->getClone();

        $sysUrl->addPath('src/main/web/players/youtube/youtube.js');

        return $sysUrl;
    }

    /**
     * @return boolean True if this player location produces HTML, false otherwise.
     *
     * @api
     * @since 4.0.0
     */
    public final function producesHtml()
    {
        return false;
    }

    /**
     * @return string The human-readable name of this player location.
     *
     * @api
     * @since 4.0.0
     */
    public function getUntranslatedFriendlyName()
    {
        return 'from the video\'s original YouTube page';               //>(translatable)<
    }
}