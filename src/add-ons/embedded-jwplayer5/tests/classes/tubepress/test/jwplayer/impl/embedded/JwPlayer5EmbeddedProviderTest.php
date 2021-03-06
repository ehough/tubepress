<?php
/*
 * Copyright 2006 - 2018 TubePress LLC (http://tubepress.com)
 *
 * This file is part of TubePress (http://tubepress.com)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 * @covers tubepress_jwplayer5_impl_embedded_JwPlayer5EmbeddedProvider
 */
class tubepress_test_jwplayer5_impl_embedded_JwPlayer5EmbeddedProviderTest extends tubepress_api_test_TubePressUnitTest
{
    /**
     * @var tubepress_jwplayer5_impl_embedded_JwPlayer5EmbeddedProvider
     */
    private $_sut;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockUrlFactory;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockContext;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockEnvironment;

    public function onSetup() {

        $this->_mockUrlFactory  = $this->mock(tubepress_api_url_UrlFactoryInterface::_);
        $this->_mockContext     = $this->mock(tubepress_api_options_ContextInterface::_);
        $this->_mockEnvironment = $this->mock(tubepress_api_environment_EnvironmentInterface::_);

        $this->_sut = new tubepress_jwplayer5_impl_embedded_JwPlayer5EmbeddedProvider(
            $this->_mockContext,
            $this->_mockUrlFactory,
            $this->_mockEnvironment
        );
    }

    public function testBasics()
    {
        $this->assertEquals('longtail', $this->_sut->getName());
        $this->assertEquals('JW Player (by Longtail Video)', $this->_sut->getUntranslatedDisplayName());
        $this->assertEquals(array('youtube'), $this->_sut->getCompatibleMediaProviderNames());
        $this->assertEquals('single/embedded/jwplayer5', $this->_sut->getTemplateName());
        $this->assertEquals(array(TUBEPRESS_ROOT . '/src/add-ons/embedded-jwplayer5/templates'), $this->_sut->getTemplateDirectories());
    }

    public function testGetTemplateVariables()
    {
        $mockMediaItem = $this->mock('tubepress_api_media_MediaItem');
        $mockUrl       = $this->mock(tubepress_api_url_UrlInterface::_);
        $mockBaseUrl   = $this->mock(tubepress_api_url_UrlInterface::_);

        $this->_mockEnvironment->shouldReceive('getUserContentUrl')->once()->andReturn($mockBaseUrl);

        $mockMediaItem->shouldReceive('getId')->once()->andReturn('abc');

        $this->_mockUrlFactory->shouldReceive('fromString')->once()->with('https://www.youtube.com/watch?v=abc')
            ->andReturn($mockUrl);

        $this->_mockContext->shouldReceive('get')->once()->with(tubepress_jwplayer5_api_OptionNames::COLOR_FRONT)->andReturn('front-color');
        $this->_mockContext->shouldReceive('get')->once()->with(tubepress_jwplayer5_api_OptionNames::COLOR_BACK)->andReturn('back-color');
        $this->_mockContext->shouldReceive('get')->once()->with(tubepress_jwplayer5_api_OptionNames::COLOR_LIGHT)->andReturn('light-color');
        $this->_mockContext->shouldReceive('get')->once()->with(tubepress_jwplayer5_api_OptionNames::COLOR_SCREEN)->andReturn('screen-color');
        $this->_mockContext->shouldReceive('get')->once()->with(tubepress_api_options_Names::EMBEDDED_AUTOPLAY)->andReturn('autoplay?');

        $actual   = $this->_sut->getTemplateVariables($mockMediaItem);
        $expected = array(
            'userContentUrl'                                        => $mockBaseUrl,
            'autostart'                                             => 'autoplay?',
            tubepress_api_template_VariableNames::EMBEDDED_DATA_URL => $mockUrl,
            tubepress_jwplayer5_api_OptionNames::COLOR_FRONT        => 'front-color',
            tubepress_jwplayer5_api_OptionNames::COLOR_LIGHT        => 'light-color',
            tubepress_jwplayer5_api_OptionNames::COLOR_SCREEN       => 'screen-color',
            tubepress_jwplayer5_api_OptionNames::COLOR_BACK         => 'back-color',
        );

        $this->assertEquals($expected, $actual);
    }
}
