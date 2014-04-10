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
 * Discovers add-ons for TubePress.
 */
interface tubepress_spi_addon_AddonFinderInterface
{
    const _ = 'tubepress_spi_addon_AddonFinderInterface';

    /**
     * Discovers TubePress add-ons.
     *
     * @param array $blacklist The add-on blacklist.
     *
     * @return array An array of tubepress_spi_addon_AddonInterface instances, which may be empty. Never null.
     */
    function findAddons(array $blacklist);
}