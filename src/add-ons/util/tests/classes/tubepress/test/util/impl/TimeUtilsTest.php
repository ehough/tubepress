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
 * @covers tubepress_util_impl_TimeUtils
 */
class tubepress_test_util_impl_TimeUtilsTest extends tubepress_api_test_TubePressUnitTest
{
    /**
     * @var tubepress_util_impl_TimeUtils
     */
    private $_sut;

    public function onSetup()
    {
        $stringUtils = new tubepress_util_impl_StringUtils();
        $this->_sut  = new tubepress_util_impl_TimeUtils($stringUtils);
    }

    public function testGetRelativeTimePastDecade()
    {
        $result = $this->_sut->getRelativeTime(1000000000);
        $this->assertEquals('15 years ago', $result);
    }

    public function testGetRelativeTimePast5Years()
    {
        $result = $this->_sut->getRelativeTime(1288760400);
        $this->assertEquals('6 years ago', $result);
    }

    /**
     * @dataProvider getDataSecondsToHumanTime
     */
    public function testSeconds2HumanTime($seconds, $humanTime)
    {
        $result = $this->_sut->secondsToHumanTime($seconds);

        $this->assertEquals($humanTime, $result);
    }

    public function getDataSecondsToHumanTime()
    {
        return array(

            array(3,    '0:03'),
            array(60,   '1:00'),
            array(63,   '1:03'),
            array(0,    '0:00'),
            array(3601, '1:00:01'),
            array(50402, '14:00:02'),
        );
    }

    public function testRfc3339toUnixTime()
    {
        $result = $this->_sut->rfc3339toUnixTime('1980-11-03T09:03:33.000-05:00');
        $this->assertEquals('342108213', $result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testHumanTimeDate()
    {
        date_default_timezone_set('America/New_York');
        $result = $this->_sut->unixTimeToHumanReadable(342108202, 'l jS \of F Y h:i:s A e', false);
        $this->assertEquals('Monday 3rd of November 1980 09:03:22 AM America/New_York', $result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testHumanTimeStrftime()
    {
        date_default_timezone_set('America/New_York');

        if (setlocale(LC_TIME, 'es_ES') !== 'es_ES') {

            $this->markTestSkipped('Missing es_ES locale');

            return;
        };

        $result = $this->_sut->unixTimeToHumanReadable(342108202, '%A%e de %B, %Y, %H:%M:%S %Z', false);
        $this->assertEquals('lunes 3 de noviembre, 1980, 09:03:22 EST', $result);
    }
}
