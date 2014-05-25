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
 * @covers tubepress_core_impl_listeners_template_SearchInputCoreVariables
 */
class tubepress_test_core_impl_listeners_template_SearchInputCoreVariablesTest extends tubepress_test_TubePressUnitTest
{
    /**
     * @var tubepress_core_impl_listeners_template_SearchInputCoreVariables
     */
    private $_sut;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockExecutionContext;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockMessageService;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockHttpRequestParameterService;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockUrlFactory;

    public function onSetup()
    {
        $this->_mockExecutionContext = $this->mock(tubepress_core_api_options_ContextInterface::_);
        $this->_mockMessageService = $this->mock(tubepress_core_api_translation_TranslatorInterface::_);
        $this->_mockHttpRequestParameterService = $this->mock(tubepress_core_api_http_RequestParametersInterface::_);
        $this->_mockUrlFactory = $this->mock(tubepress_core_api_url_UrlFactoryInterface::_);

        $this->_sut = new tubepress_core_impl_listeners_template_SearchInputCoreVariables(
            $this->_mockExecutionContext,
            $this->_mockMessageService,
            $this->_mockUrlFactory,
            $this->_mockHttpRequestParameterService);
    }

    public function testYouTubeFavorites()
    {
        $this->_mockExecutionContext->shouldReceive('get')->once()->with(tubepress_core_api_const_options_Names::SEARCH_RESULTS_URL)->andReturn('');
        $mockUrl = $this->mock('tubepress_core_api_url_UrlInterface');
        $mockQuery = $this->mock('tubepress_core_api_url_QueryInterface');
        $mockUrl->shouldReceive('getQuery')->once()->andReturn($mockQuery);
        $mockUrl->shouldReceive('toString')->once()->andReturn('abcabc');
        $mockQuery->shouldReceive('remove')->once()->with(tubepress_core_api_const_http_ParamName::PAGE);
        $mockQuery->shouldReceive('remove')->once()->with(tubepress_core_api_const_http_ParamName::SEARCH_TERMS);
        $mockQuery->shouldReceive('toArray')->once()->andReturn(array('foo' => 'bar', 'something' => 'else'));
        $this->_mockUrlFactory->shouldReceive('fromCurrent')->once()->andReturn($mockUrl);

        $this->_mockHttpRequestParameterService->shouldReceive('getParamValue')->once()->with(tubepress_core_api_const_http_ParamName::SEARCH_TERMS)->andReturn("search for something");

        $this->_mockMessageService->shouldReceive('_')->once()->andReturnUsing(function ($msg) {
            return "##$msg##";
        });

        $mockTemplate = $this->mock('tubepress_core_api_template_TemplateInterface');
        $mockTemplate->shouldReceive('setVariable')->once()->with(tubepress_core_api_const_template_Variable::SEARCH_HANDLER_URL, 'abcabc');
        $mockTemplate->shouldReceive('setVariable')->once()->with(tubepress_core_api_const_template_Variable::SEARCH_HIDDEN_INPUTS, array('foo' => 'bar', 'something' => 'else'));
        $mockTemplate->shouldReceive('setVariable')->once()->with(tubepress_core_api_const_template_Variable::SEARCH_TERMS, 'search for something');
        $mockTemplate->shouldReceive('setVariable')->once()->with(tubepress_core_api_const_template_Variable::SEARCH_BUTTON, '##Search##');

        $event = $this->mock('tubepress_core_api_event_EventInterface');
        $event->shouldReceive('getSubject')->once()->andReturn($mockTemplate);

        $this->_sut->onSearchInputTemplate($event);

        $this->assertTrue(true);
    }

}

