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
 * Adds a few JW Player template variables.
 */
class tubepress_jwplayer_impl_listeners_template_JwPlayerTemplateVars
{
    /**
     * @var tubepress_core_api_options_ContextInterface
     */
    private $_context;

    public function __construct(tubepress_core_api_options_ContextInterface $context)
    {
        $this->_context = $context;
    }

    public function onEmbeddedTemplate(tubepress_core_api_event_EventInterface $event)
    {
        $implName = $event->getArgument('embeddedImplementationName');

        if ($implName !== 'longtail') {

            return;
        }

        $template = $event->getSubject();

        $toSet = array(

            tubepress_jwplayer_api_const_template_Variable::COLOR_FRONT =>
            tubepress_jwplayer_api_const_options_names_Embedded::COLOR_FRONT,

            tubepress_jwplayer_api_const_template_Variable::COLOR_LIGHT =>
                tubepress_jwplayer_api_const_options_names_Embedded::COLOR_LIGHT,

            tubepress_jwplayer_api_const_template_Variable::COLOR_SCREEN =>
                tubepress_jwplayer_api_const_options_names_Embedded::COLOR_SCREEN,

            tubepress_jwplayer_api_const_template_Variable::COLOR_BACK =>
                tubepress_jwplayer_api_const_options_names_Embedded::COLOR_BACK,
        );

        foreach ($toSet as $templateVariableName => $optionName) {

            $template->setVariable($templateVariableName, $this->_context->get($optionName));
        }
    }
}