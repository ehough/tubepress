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
 * An embedded video player.
 *
 * @api
 * @since 4.0.0
 */
interface tubepress_core_api_embedded_EmbeddedProviderInterface
{
    /**
     * @ignore
     */
    const _ = 'tubepress_core_api_embedded_EmbeddedProviderInterface';

    /**
     * @return string The name of this embedded player. Never empty or null. All lowercase alphanumerics and dashes.
     *
     * @api
     * @since 4.0.0
     */
    function getName();

    /**
     * @return string The friendly name of this embedded player service.
     *
     * @api
     * @since 4.0.0
     */
    function getUntranslatedDisplayName();

    /**
     * @return string[] The paths, to pass to the template factory, for this embedded provider.
     *
     * @api
     * @since 4.0.0
     */
    function getPathsForTemplateFactory();

    /**
     * @param tubepress_core_api_url_UrlFactoryInterface         $urlFactory URL factory
     * @param tubepress_core_api_provider_VideoProviderInterface $provider   The video provider
     * @param string                                             $videoId    The video ID to play
     *
     * @return tubepress_core_api_url_UrlInterface The URL of the data for this video.
     *
     * @api
     * @since 4.0.0
     */
    function getDataUrlForVideo(tubepress_core_api_url_UrlFactoryInterface $urlFactory,
                                tubepress_core_api_provider_VideoProviderInterface $provider,
                                $videoId);

    /**
     * @param tubepress_core_api_provider_VideoProviderInterface
     *
     * @return string[] An array of provider names that this embedded provider can handle.
     *
     * @api
     * @since 4.0.0
     */
    function getCompatibleProviderNames();
}