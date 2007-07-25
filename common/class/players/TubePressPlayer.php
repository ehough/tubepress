<?php
/**
 * TubePressPlayer.php
 * 
 * Copyright (C) 2007 Eric D. Hough (http://ehough.com)
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

class_exists("TubePressBaseDataItem")
    || require(dirname(__FILE__) . "/../TubePressBaseDataItem.php");

/**
 * A TubePress "player", such as lightWindow, GreyBox, popup window, etc
 */
class TubePressPlayer extends TubePressBaseDataItem
{
	/*
	 * for each player, we want to know which CSS
	 * and JS libraries that it needs
	 */
	var $_cssLibs, $_jsLibs;

	function TubePressPlayer($title, $cssLibs = "", $jsLibs = "") {
		$this->_title = $title;
		$this->_cssLibs = $cssLibs;
		$this->_jsLibs = $jsLibs;
	}
}
?>
