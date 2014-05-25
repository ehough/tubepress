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
 * @covers tubepress_core_impl_listeners_html_generation_ThumbGalleryListener
 */
class tubepress_test_core_impl_shortcode_ThumbGalleryCommandTest extends tubepress_test_TubePressUnitTest
{
    /**
     * @var tubepress_core_impl_listeners_html_generation_ThumbGalleryListener
     */
    private $_sut;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockExecutionContext;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockHttpRequestParameterService;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockTemplateFactory;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockProvider;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockEventDispatcher;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockLogger;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockEvent;

    public function onSetup()
    {
        $this->_mockExecutionContext = $this->mock(tubepress_core_api_options_ContextInterface::_);
        $this->_mockHttpRequestParameterService = $this->mock(tubepress_core_api_http_RequestParametersInterface::_);
        $this->_mockTemplateFactory     = $this->mock(tubepress_core_api_template_TemplateFactoryInterface::_);
        $this->_mockProvider         = $this->mock(tubepress_core_api_collector_CollectorInterface::_);
        $this->_mockEventDispatcher  = $this->mock(tubepress_core_api_event_EventDispatcherInterface::_);
        $this->_mockLogger           = $this->mock(tubepress_api_log_LoggerInterface::_);
        $this->_mockEvent            = $this->mock('tubepress_core_api_event_EventInterface');

        $this->_mockLogger->shouldReceive('isEnabled')->once()->andReturn(true);
        $this->_mockLogger->shouldReceive('debug')->atLeast(1);

        $this->_sut = new tubepress_core_impl_listeners_html_generation_ThumbGalleryListener(

            $this->_mockLogger,
            $this->_mockExecutionContext,
            $this->_mockEventDispatcher,
            $this->_mockProvider,
            $this->_mockHttpRequestParameterService,
            $this->_mockTemplateFactory
        );
    }


    public function testExecuteGenerateGalleryId()
    {
        $this->_mockHttpRequestParameterService->shouldReceive('getParamValueAsInt')->once()->with(tubepress_core_api_const_http_ParamName::PAGE, 1)->andReturn('page-num');

        $mockTemplate = $this->mock('tubepress_core_api_template_TemplateInterface');
        $mockTemplate->shouldReceive('toString')->once()->andReturn('template-string');

        $this->_mockTemplateFactory->shouldReceive('fromFilesystem')->once()->with(array(
            'gallery.tpl.php', TUBEPRESS_ROOT . '/src/main/web/themes/default/gallery.tpl.php'))->andReturn($mockTemplate);

        $this->_mockExecutionContext->shouldReceive('get')->once()->with(tubepress_core_api_const_options_Names::GALLERY_ID)->andReturn('');
        $this->_mockExecutionContext->shouldReceive('set')->once()->with(tubepress_core_api_const_options_Names::GALLERY_ID, ehough_mockery_Mockery::type('integer'))->andReturn(true);

        $mockFeedResult = new tubepress_core_api_video_VideoGalleryPage();
        $mockFeedResult->setVideos(array('x', 'y'));

        $this->_mockProvider->shouldReceive('collectPage')->once()->andReturn($mockFeedResult);

        $mockTemplateEvent = $this->mock('tubepress_core_api_event_EventInterface');
        $mockTemplateEvent->shouldReceive('getSubject')->once()->andReturn($mockTemplate);

        $this->_mockEventDispatcher->shouldReceive('newEventInstance')->once()->with($mockTemplate, array(

            'page'             => 'page-num',
            'videoGalleryPage' => $mockFeedResult
        ))->andReturn($mockTemplateEvent);
        $this->_mockEventDispatcher->shouldReceive('hasListeners')->once()->with(tubepress_core_api_const_event_EventNames::TEMPLATE_THUMBNAIL_GALLERY)->andReturn(true);
        $this->_mockEventDispatcher->shouldReceive('dispatch')->once()->with(tubepress_core_api_const_event_EventNames::TEMPLATE_THUMBNAIL_GALLERY, $mockTemplateEvent);

        $mockHtmlEvent = $this->mock('tubepress_core_api_event_EventInterface');
        $mockHtmlEvent->shouldReceive('getSubject')->once()->andReturn('bla');

        $this->_mockEventDispatcher->shouldReceive('newEventInstance')->once()->with('template-string', array(

            'page'             => 'page-num',
            'videoGalleryPage' => $mockFeedResult
        ))->andReturn($mockHtmlEvent);
        $this->_mockEventDispatcher->shouldReceive('hasListeners')->once()->with(tubepress_core_api_const_event_EventNames::HTML_THUMBNAIL_GALLERY)->andReturn(true);
        $this->_mockEventDispatcher->shouldReceive('dispatch')->once()->with(tubepress_core_api_const_event_EventNames::HTML_THUMBNAIL_GALLERY, $mockHtmlEvent);

        $this->_mockEvent->shouldReceive('setSubject')->once()->with('bla');
        $this->_mockEvent->shouldReceive('stopPropagation')->once();

        $this->_sut->onHtmlGeneration($this->_mockEvent);
        $this->assertTrue(true);
    }

    public function testExecute()
    {

        $this->_mockHttpRequestParameterService->shouldReceive('getParamValueAsInt')->once()->with(tubepress_core_api_const_http_ParamName::PAGE, 1)->andReturn('page-num');

        $mockTemplate = $this->mock('tubepress_core_api_template_TemplateInterface');
        $mockTemplate->shouldReceive('toString')->once()->andReturn('template-string');

        $this->_mockTemplateFactory->shouldReceive('fromFilesystem')->once()->with(array(
            'gallery.tpl.php', TUBEPRESS_ROOT . '/src/main/web/themes/default/gallery.tpl.php'))->andReturn($mockTemplate);

        $this->_mockExecutionContext->shouldReceive('get')->once()->with(tubepress_core_api_const_options_Names::GALLERY_ID)->andReturn('gallery-id');

        $mockFeedResult = new tubepress_core_api_video_VideoGalleryPage();
        $mockFeedResult->setVideos(array('x', 'y'));

        $mockTemplateEvent = $this->mock('tubepress_core_api_event_EventInterface');
        $mockTemplateEvent->shouldReceive('getSubject')->once()->andReturn($mockTemplate);

        $this->_mockEventDispatcher->shouldReceive('newEventInstance')->once()->with($mockTemplate, array(

            'page'             => 'page-num',
            'videoGalleryPage' => $mockFeedResult
        ))->andReturn($mockTemplateEvent);

        $this->_mockProvider->shouldReceive('collectPage')->once()->andReturn($mockFeedResult);

        $mockHtmlEvent = $this->mock('tubepress_core_api_event_EventInterface');
        $mockHtmlEvent->shouldReceive('getSubject')->once()->andReturn('bla');

        $this->_mockEventDispatcher->shouldReceive('newEventInstance')->once()->with('template-string', array(

            'page'             => 'page-num',
            'videoGalleryPage' => $mockFeedResult
        ))->andReturn($mockHtmlEvent);

        $this->_mockEventDispatcher->shouldReceive('hasListeners')->once()->with(tubepress_core_api_const_event_EventNames::TEMPLATE_THUMBNAIL_GALLERY)->andReturn(true);
        $this->_mockEventDispatcher->shouldReceive('dispatch')->once()->with(tubepress_core_api_const_event_EventNames::TEMPLATE_THUMBNAIL_GALLERY, $mockTemplateEvent);

        $this->_mockEventDispatcher->shouldReceive('hasListeners')->once()->with(tubepress_core_api_const_event_EventNames::HTML_THUMBNAIL_GALLERY)->andReturn(true);
        $this->_mockEventDispatcher->shouldReceive('dispatch')->once()->with(tubepress_core_api_const_event_EventNames::HTML_THUMBNAIL_GALLERY, $mockHtmlEvent);

        $this->_mockEvent->shouldReceive('setSubject')->once()->with('bla');
        $this->_mockEvent->shouldReceive('stopPropagation')->once();

        $this->_sut->onHtmlGeneration($this->_mockEvent);
        $this->assertTrue(true);
    }
}