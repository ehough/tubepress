<?php
/**
 * Copyright 2006, 2007, 2008 Eric D. Hough (http://ehough.com)
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
 * Plays videos from the original YouTube page
 */
class TPYouTubePlayer extends TubePressPlayer
{
    public function __construct() {
        $this->setName(TubePressPlayer::youTube);
        $this->setTitle("from the original YouTube page");
    }
	
	/**
	 * Tells the gallery how to play a video
	 */
	function getPlayLink(TubePressVideo $vid, TubePressStorage_v157 $stored)
	{   
	    return sprintf('href="http://youtube.com/watch?v=%s"',
	        $vid->getId());
	}
}
?>
