<?php
/**
 * Copyright 2006 - 2012 Eric D. Hough (http://ehough.com)
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
class tubepress_impl_patterns_ioc_CoreIocContainerTest extends TubePressUnitTest
{
    private $_sut;

    function setUp()
    {
        $this->_sut = new tubepress_impl_patterns_ioc_CoreIocContainer();
    }

    function testBuildsNormally()
    {
        $this->assertNotNull($this->_sut);
    }

    function testServiceConstructions()
    {
        $toTest = array(


            tubepress_spi_const_patterns_ioc_ServiceIds::ENVIRONMENT_DETECTOR      => tubepress_spi_environment_EnvironmentDetector::_,
            tubepress_spi_const_patterns_ioc_ServiceIds::FILESYSTEM_FINDER_FACTORY => 'ehough_fimble_api_FinderFactory',
            tubepress_spi_const_patterns_ioc_ServiceIds::PLUGIN_DISCOVER           => tubepress_spi_plugin_PluginDiscoverer::_,
            tubepress_spi_const_patterns_ioc_ServiceIds::PLUGIN_REGISTRY           => tubepress_spi_plugin_PluginRegistry::_,
        );

        foreach ($toTest as $key => $value) {

            $this->_testServiceBuilt($key, $value);
        }
    }

    private function _testServiceBuilt($id, $class)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $obj = $this->_sut->get($id);

        $this->assertTrue($obj instanceof $class, "Failed to build $id of type $class. Instead got " . gettype($obj) . var_export($obj, true));
    }


}