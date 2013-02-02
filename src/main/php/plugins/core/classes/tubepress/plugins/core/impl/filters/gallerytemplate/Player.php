<?php
/**
 * Copyright 2006 - 2012 Eric D. Hough (http://ehough.com)
 *
 * This file is part of TubePress (http://tubepress.org)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 * Handles applying the player HTML to the gallery template.
 */
class tubepress_plugins_core_impl_filters_gallerytemplate_Player
{
    public function onGalleryTemplate(tubepress_api_event_TubePressEvent $event)
    {
        $context        = tubepress_impl_patterns_sl_ServiceLocator::getExecutionContext();
        $htmlGenerator  = tubepress_impl_patterns_sl_ServiceLocator::getPlayerHtmlGenerator();
        $playerName     = $context->get(tubepress_api_const_options_names_Embedded::PLAYER_LOCATION);
        $providerResult = $event->getArgument('videoGalleryPage');
        $videos         = $providerResult->getVideos();
        $template       = $event->getSubject();
        $galleryId      = $context->get(tubepress_api_const_options_names_Advanced::GALLERY_ID);
        $playerHtml     = $playerHtml = $this->_showPlayerHtmlOnPageLoad($playerName) ?
            $htmlGenerator->getHtml($videos[0], $galleryId) : '';

        $template->setVariable(tubepress_api_const_template_Variable::PLAYER_HTML, $playerHtml);
        $template->setVariable(tubepress_api_const_template_Variable::PLAYER_NAME, $playerName);
    }

    private function _showPlayerHtmlOnPageLoad($playerName)
    {
        if ($playerName === 'normal' || $playerName === 'static') {

            return true;
        }

        return false;
    }
}