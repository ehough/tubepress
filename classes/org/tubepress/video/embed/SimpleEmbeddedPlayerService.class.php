<?php
/**
 * Copyright 2006, 2007, 2008, 2009 Eric D. Hough (http://ehough.com)
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
 * Represents an HTML-embeddable YouTube player
 *
 */
class org_tubepress_video_embed_SimpleEmbeddedPlayerService implements org_tubepress_video_embed_EmbeddedPlayerService
{
    private $_color1      = "";
    private $_color2      = "";
    private $_showRelated = false;
    private $_autoPlay    = false;
    private $_loop        = false;
    private $_genie       = false;
    private $_border      = false;
    private $_id          = "";
    private $_width       = 425;
    private $_height      = 355;
    private $_quality     = "normal";
    
    /**
     * Applies options from a org_tubepress_options_manager_OptionsManager
     *
     * @param org_tubepress_video_Video          $vid  The video that this embedded player 
     *                                       will show
     * @param org_tubepress_options_manager_OptionsManager $tpom The options manager
     * 
     * @return void
     */
    public function applyOptions(org_tubepress_video_Video $vid, org_tubepress_options_manager_OptionsManager $tpom)
    {
        $color = $tpom->get(org_tubepress_options_category_Embedded::PLAYER_COLOR);
        if ($color != "/") {
            $colors        = split("/", $color);
            $this->_color1 = $colors[0];
            $this->_color2 = $colors[1];
        }
        
        $this->_showRelated = $tpom->get(org_tubepress_options_category_Embedded::SHOW_RELATED);
        $this->_autoPlay    = $tpom->get(org_tubepress_options_category_Embedded::AUTOPLAY);
        $this->_loop        = $tpom->get(org_tubepress_options_category_Embedded::LOOP);
        $this->_genie       = $tpom->get(org_tubepress_options_category_Embedded::GENIE);
        $this->_border      = $tpom->get(org_tubepress_options_category_Embedded::BORDER);
        $this->_id          = $vid->getId();
        $this->_width       = $tpom->get(org_tubepress_options_category_Embedded::EMBEDDED_WIDTH);
        $this->_height      = $tpom->get(org_tubepress_options_category_Embedded::EMBEDDED_HEIGHT);
        $this->_quality     = $tpom->get(org_tubepress_options_category_Embedded::QUALITY);
    }    
    
    /**
     * Applies options from a string
     *
     * @param string $packed The string containing the options
     * 
     * @return void
     */    
    public function applyOptionsFromPackedString($packed)
    {
        $broken = split(";", $packed);
        
        foreach ($broken as $pair) {
            
            $keyValue = split("=", $pair);
            $value    = $keyValue[1];
            if ($value == "") {
                continue;
            }
            
            switch($keyValue[0]) {
                
            case "c1":
                $this->_color1 = $value;
                break;
            case "c2":
                $this->_color2 = $value;
                break;
            case "r":
                $this->_showRelated = $value;
                break;
            case "a":
                $this->_autoPlay = $value;
                break;
            case "l":
                $this->_loop = $value;
                break;
            case "g":
                $this->_genie = $value;
                break;
            case "b":
                $this->_border = $value;
                break;
            case "id":
                $this->_id = $value;
                break;
            case "w":
                $this->_width = $value;
                break;
            case "h":
                $this->_height = $value;
                break;                
            case "q":
                $this->_quality = $value;
                break;
            }
        }
    }    
    
    /**
     * Packs options from a org_tubepress_options_manager_OptionsManager to a string
     *
     * @param org_tubepress_video_Video          $vid  The video that this embedded 
     *                                       player will show
     * @param org_tubepress_options_manager_OptionsManager $tpom The options manager that will 
     *                                       be packed to a string
     * 
     * @return void
     */    
    public function packOptionsToString(org_tubepress_video_Video $vid, 
        org_tubepress_options_manager_OptionsManager $tpom)
    {
        $opts = array(
            "r" => $tpom->get(org_tubepress_options_category_Embedded::SHOW_RELATED),
            "a" => $tpom->get(org_tubepress_options_category_Embedded::AUTOPLAY),
            "l" => $tpom->get(org_tubepress_options_category_Embedded::LOOP),
            "g" => $tpom->get(org_tubepress_options_category_Embedded::GENIE),
            "b" => $tpom->get(org_tubepress_options_category_Embedded::BORDER),
            "id" => $vid->getId(),
            "w" => $tpom->get(org_tubepress_options_category_Embedded::EMBEDDED_WIDTH),
            "h" => $tpom->get(org_tubepress_options_category_Embedded::EMBEDDED_HEIGHT),
            "q" => $tpom->get(org_tubepress_options_category_Embedded::QUALITY)
        );
        
        $color = $tpom->get(org_tubepress_options_category_Embedded::PLAYER_COLOR);
        if ($color != "/") {
            $colors  = split("/", $color);
            $toMerge = array(
                "c1" => $colors[0],
                "c2" => $colors[1]
            );
            $opts    = array_merge($opts, $toMerge);
        }
        
        $result = array();
        foreach ($opts as $key => $value) {
            $result[] = $key . "=" . $value;
        }
        return implode(";", $result);
    }
    
    /**
     * Spits back the text for this embedded player
     *
     * @return string The text for this embedded player
     */
    public function toString()
    {
        $link = new net_php_pear_Net_URL2(sprintf("http://www.youtube.com/v/%s", $this->_id));
        
        if ($this->_color1 != "" && $this->_color2 != "") {
            $link->setQueryVariable("color1", $this->_color1);
            $link->setQueryVariable("color2", $this->_color2);
        }
        
        $link->setQueryVariable("rel", $this->_showRelated ? "1" : "0");
        $link->setQueryVariable("autoplay", $this->_autoPlay    ? "1" : "0");
        $link->setQueryVariable("loop", $this->_loop        ? "1" : "0");
        $link->setQueryVariable("egm", $this->_genie       ? "1" : "0");
        $link->setQueryVariable("border", $this->_border      ? "1" : "0");
        
        switch ($this->_quality) {
        case "high":
            $link->setQueryVariable("ap", "%26");
            $link->setQueryVariable("fmt", "6");
            break;
        case "higher":
            $link->setQueryVariable("ap", "%26");
            $link->setQueryVariable("fmt", "18");
            break;
        case "highest":
            $link->setQueryVariable("ap", "%26");
            $link->setQueryVariable("fmt", "22");
            break;      
        }
        
        $link = $link->getURL(true);
        
        return sprintf(<<<EOT
<object type="application/x-shockwave-flash" 
    style="width: %spx; height: %spx" data="%s">
    <param name="wmode" value="transparent" />
    <param name="movie" value="%s" />
</object>
EOT
        , $this->_width, $this->_height, $link, $link);
    }
}

?>