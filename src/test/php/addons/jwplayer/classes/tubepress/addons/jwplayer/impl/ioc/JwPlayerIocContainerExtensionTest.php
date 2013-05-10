<?php
/**
 * Copyright 2006 - 2013 TubePress LLC (http://tubepress.org)
 *
 * This file is part of TubePress (http://tubepress.org)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
class tubepress_addons_jwplayer_impl_ioc_JwPlayerIocContainerExtensionTest extends tubepress_test_impl_ioc_AbstractIocContainerExtensionTest
{
    protected function buildSut()
    {
        return new tubepress_addons_jwplayer_impl_ioc_JwPlayerIocContainerExtension();
    }

    protected function prepareForLoad()
    {
        $this->expectRegistration(

            'tubepress_addons_jwplayer_impl_embedded_JwPlayerPluggableEmbeddedPlayerService',
            'tubepress_addons_jwplayer_impl_embedded_JwPlayerPluggableEmbeddedPlayerService'

        )->withTag(tubepress_spi_embedded_PluggableEmbeddedPlayerService::_);

        $this->expectRegistration(

            'tubepress_addons_jwplayer_impl_options_ui_JwPlayerOptionsPageParticipant',
            'tubepress_addons_jwplayer_impl_options_ui_JwPlayerOptionsPageParticipant'

        )->withTag(tubepress_spi_options_ui_PluggableOptionsPageParticipant::_);

        $this->expectRegistration(

            'tubepress_addons_jwplayer_impl_listeners_boot_JwPlayerOptionsRegistrar',
            'tubepress_addons_jwplayer_impl_listeners_boot_JwPlayerOptionsRegistrar'
        );

        $this->expectRegistration(

            'tubepress_addons_jwplayer_impl_listeners_template_JwPlayerTemplateVars',
            'tubepress_addons_jwplayer_impl_listeners_template_JwPlayerTemplateVars'
        );

        $this->expectRegistration(

            'tubepress_addons_jwplayer_impl_Bootstrap',
            'tubepress_addons_jwplayer_impl_Bootstrap'
        );
    }

}