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
 * Displays a drop-down input.
 */
class tubepress_core_impl_options_ui_fields_provided_DropdownField extends tubepress_core_impl_options_ui_fields_provided_AbstractProvidedOptionBasedField
{
    /**
     * @var tubepress_api_util_LangUtilsInterface
     */
    private $_langUtils;

    public function __construct($optionName,
                                tubepress_core_api_translation_TranslatorInterface   $translator,
                                tubepress_core_api_options_PersistenceInterface      $persistence,
                                tubepress_core_api_http_RequestParametersInterface   $requestParams,
                                tubepress_core_api_event_EventDispatcherInterface    $eventDispatcher,
                                tubepress_core_api_options_ProviderInterface         $optionProvider,
                                tubepress_core_api_template_TemplateFactoryInterface $templateFactory,
                                tubepress_api_util_LangUtilsInterface                $langUtils)
    {
        parent::__construct(

            $optionName,
            $translator,
            $persistence,
            $requestParams,
            $eventDispatcher,
            $templateFactory,
            $optionProvider
        );

        $this->_langUtils = $langUtils;
    }

    protected function getAbsolutePathToTemplate()
    {
        return TUBEPRESS_ROOT . '/src/main/resources/options-gui/field-templates/dropdown.tpl.php';
    }

    protected function getAdditionalTemplateVariables()
    {
        $values = array();
        $map    = $this->getOptionProvider()->getDiscreteAcceptableValues($this->getId());

        if (!$this->_langUtils->isAssociativeArray($map)) {

            throw new InvalidArgumentException(sprintf('"%s" has a non-associative array set for its value map', $this->getId()));
        }

        foreach ($map as $key => $value) {

            $values[$key] = $this->translate($value);
        }

        return array('choices' => $values);
    }
}