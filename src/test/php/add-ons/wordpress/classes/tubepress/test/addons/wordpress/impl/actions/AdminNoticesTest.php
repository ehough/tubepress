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
 * @covers tubepress_addons_wordpress_impl_actions_AdminNotices
 */
class tubepress_test_addons_wordpress_impl_actions_AdminNoticesTest extends tubepress_test_TubePressUnitTest
{
    /**
     * @var tubepress_addons_wordpress_impl_actions_AdminNotices
     */
    private $_sut;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockWordPressFunctionWrapper;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockHttpRequestParameterService;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockQss;

    public function onSetup()
    {
        $this->_sut                             = new tubepress_addons_wordpress_impl_actions_AdminNotices();
        $this->_mockWordPressFunctionWrapper    = $this->createMockSingletonService(tubepress_addons_wordpress_spi_WpFunctionsInterface::_);
        $this->_mockHttpRequestParameterService = $this->createMockSingletonService(tubepress_spi_http_HttpRequestParameterService::_);
        $this->_mockQss                         = $this->createMockSingletonService(tubepress_spi_querystring_QueryStringService::_);

        $this->_sut->___doNotIgnoreExceptions();
    }

    public function testNagNoDismissRequestedDismissStored()
    {
        $this->_mockWordPressFunctionWrapper->shouldReceive('current_user_can')->once()->with('manage_options')->andReturn(true);
        $this->_mockHttpRequestParameterService->shouldReceive('hasParam')->once()->with('dismissTubePressCacheNag')->andReturn(false);
        $mockUser = new stdClass();
        $mockUser->ID = 5;
        $this->_mockWordPressFunctionWrapper->shouldReceive('wp_get_current_user')->once()->andReturn($mockUser);
        $this->_mockWordPressFunctionWrapper->shouldReceive('get_transient')->once()->with('user_5_dismiss_tubepress_nag')->andReturn('dismiss');

        $this->_sut->execute(array());

        $this->assertTrue(true);
    }

    public function testNagNonAdminUser()
    {
        $this->_mockWordPressFunctionWrapper->shouldReceive('current_user_can')->once()->with('manage_options')->andReturn(false);

        $this->_sut->execute(array());

        $this->assertTrue(true);
    }

    public function testNagNoDismissRequestedNoDismissStored()
    {
        $this->_mockWordPressFunctionWrapper->shouldReceive('current_user_can')->once()->with('manage_options')->andReturn(true);
        $this->_mockHttpRequestParameterService->shouldReceive('hasParam')->once()->with('dismissTubePressCacheNag')->andReturn(false);
        $mockUser = new stdClass();
        $this->_completeNagTest();
    }

    public function testNagNoDismissRequestedNoDismissStored2()
    {
        $this->_mockWordPressFunctionWrapper->shouldReceive('current_user_can')->once()->with('manage_options')->andReturn(true);
        $this->_mockHttpRequestParameterService->shouldReceive('hasParam')->once()->with('dismissTubePressCacheNag')->andReturn(true);
        $this->_mockHttpRequestParameterService->shouldReceive('getParamValue')->once()->with('dismissTubePressCacheNag')->andReturn('xyz');
        $this->_completeNagTest();
    }

    public function testNagNoDismissRequestedNoDismissStored3()
    {
        $this->_mockWordPressFunctionWrapper->shouldReceive('current_user_can')->once()->with('manage_options')->andReturn(true);
        $this->_mockHttpRequestParameterService->shouldReceive('hasParam')->once()->with('dismissTubePressCacheNag')->andReturn(true);
        $this->_mockHttpRequestParameterService->shouldReceive('hasParam')->once()->with('tubePressWpNonce')->andReturn(false);
        $this->_mockHttpRequestParameterService->shouldReceive('getParamValue')->once()->with('dismissTubePressCacheNag')->andReturn(true);

        $this->_completeNagTest();
    }

    public function testNagNoDismissRequestedNoDismissStored4()
    {
        $this->_mockWordPressFunctionWrapper->shouldReceive('current_user_can')->once()->with('manage_options')->andReturn(true);
        $this->_mockHttpRequestParameterService->shouldReceive('hasParam')->once()->with('dismissTubePressCacheNag')->andReturn(true);
        $this->_mockHttpRequestParameterService->shouldReceive('hasParam')->once()->with('tubePressWpNonce')->andReturn(true);
        $this->_mockHttpRequestParameterService->shouldReceive('getParamValue')->once()->with('dismissTubePressCacheNag')->andReturn(true);
        $this->_mockHttpRequestParameterService->shouldReceive('getParamValue')->once()->with('tubePressWpNonce')->andReturn('bad nonce');
        $this->_mockWordPressFunctionWrapper->shouldReceive('wp_verify_nonce')->once()->with('bad nonce', 'tubePressDismissNag')->andReturn(false);

        $this->_completeNagTest();
    }

    public function testDismissRequested()
    {
        $this->_mockWordPressFunctionWrapper->shouldReceive('current_user_can')->once()->with('manage_options')->andReturn(true);
        $this->_mockHttpRequestParameterService->shouldReceive('hasParam')->once()->with('dismissTubePressCacheNag')->andReturn(true);
        $this->_mockHttpRequestParameterService->shouldReceive('hasParam')->once()->with('tubePressWpNonce')->andReturn(true);
        $this->_mockHttpRequestParameterService->shouldReceive('getParamValue')->once()->with('dismissTubePressCacheNag')->andReturn(true);
        $this->_mockHttpRequestParameterService->shouldReceive('getParamValue')->once()->with('tubePressWpNonce')->andReturn('good nonce');
        $this->_mockWordPressFunctionWrapper->shouldReceive('wp_verify_nonce')->once()->with('good nonce', 'tubePressDismissNag')->andReturn(true);
        $this->_mockWordPressFunctionWrapper->shouldReceive('set_transient')->once()->with('user_5_dismiss_tubepress_nag', 'dismiss', 86400);

        $mockUser = new stdClass();
        $mockUser->ID = 5;
        $this->_mockWordPressFunctionWrapper->shouldReceive('wp_get_current_user')->once()->andReturn($mockUser);

        $this->_sut->execute(array());

        $this->assertTrue(true);
    }

    private function _completeNagTest()
    {
        $mockUser = new stdClass();
        $mockUser->ID = 5;
        $this->_mockWordPressFunctionWrapper->shouldReceive('wp_get_current_user')->once()->andReturn($mockUser);
        $this->_mockWordPressFunctionWrapper->shouldReceive('get_transient')->once()->with('user_5_dismiss_tubepress_nag')->andReturn(false);
        $this->_mockWordPressFunctionWrapper->shouldReceive('wp_create_nonce')->once()->with('tubePressDismissNag')->andReturn('your nonce');
        $this->_mockQss->shouldReceive('getFullUrl')->once()->with($_SERVER)->andReturn('http://tubepress.com/some/thing.php?color=blue');

        $this->expectOutputString(<<<ABC
<div class="update-nag">
TubePress is not configured for optimal performance, and could be slowing down your site. <strong><a target="_blank" href="http://docs.tubepress.com/page/manual/wordpress/faq-and-troubleshooting.html">Fix it now</a></strong> or <a href="?color=blue&tubePressWpNonce=your+nonce&dismissTubePressCacheNag=true">dismiss this message</a>.
</div>
ABC
        );

        $this->_sut->execute(array());

        $this->assertTrue(true);
    }
}