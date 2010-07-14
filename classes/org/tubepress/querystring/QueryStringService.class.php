<?php
/**
 * Copyright 2006 - 2010 Eric D. Hough (http://ehough.com)
 * 
 * This file is part of TubePress (http://tubepress.org)
 * 
 * TubePress is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * TubePress is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with TubePress.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

/**
 * Handles some tasks related to the query string
 */
class org_tubepress_querystring_QueryStringService
{
    const TUBEPRESS_GALLERY_ID = 'tubepress_galleryId';
    const TUBEPRESS_PAGE       = 'tubepress_page';
    const TUBEPRESS_SHORTCODE  = 'tubepress_shortcode';
    const TUBEPRESS_VIDEO      = 'tubepress_video';

    /**
     * Try to get the custom video ID from the query string
     *
     * @return string The custom video ID, or '' if not set
    */
    public static function getCustomVideo($getVars)
    {
    return org_tubepress_querystring_QueryStringService::_getQueryVar($getVars, 
        org_tubepress_querystring_QueryStringService::TUBEPRESS_VIDEO);
    }

    /**
     * Try to get the gallery ID from the query string
     *
     * @return string The gallery ID, or '' if not set
    */
    public static function getGalleryId($getVars)
    {
        return org_tubepress_querystring_QueryStringService::_getQueryVar($getVars, 
            org_tubepress_querystring_QueryStringService::TUBEPRESS_GALLERY_ID);
    }

    /**
     * Returns what's in the address bar
     * 
     * @return string What's in the address bar
     */
    public static function getFullUrl($serverVars)
    {
        $pageURL = 'http';
        if (isset($serverVars['HTTPS']) && $serverVars['HTTPS'] == 'on') {
            $pageURL .= 's';
        }
        $pageURL .= '://';
        if ($serverVars['SERVER_PORT'] != '80') {
             $pageURL .= $serverVars['SERVER_NAME'].':'.
                 $serverVars['SERVER_PORT'].$serverVars['REQUEST_URI'];
        } else {
             $pageURL .= $serverVars['SERVER_NAME'].$serverVars['REQUEST_URI'];
        }
        return $pageURL;
    }
    
    /**
     * Try to figure out what page we're on by looking at the query string
     * Defaults to '1' if there's any doubt
     * 
     * @return int The page number
     */
    public static function getPageNum($getVars)
    {
    $key = org_tubepress_querystring_QueryStringService::TUBEPRESS_PAGE;
        $pageNum = ((isset($getVars[$key])) ?
            $getVars[$key] : 1);
        
        if (!is_numeric($pageNum) || ($pageNum < 1)) {
            $pageNum = 1;
        }
        return $pageNum;
    }

    /**
     * Try to get the shortcode from the query string
     *
     * @return string The shortcode, or '' if not set
    */
    public static function getShortcode($getVars)
    {
        return org_tubepress_querystring_QueryStringService::_getQueryVar($getVars, 
            org_tubepress_querystring_QueryStringService::TUBEPRESS_SHORTCODE);
    }

    private static function _getQueryVar($getVars, $key)
    {
        return isset($getVars[$key]) ? $getVars[$key] : '';
    }
}
