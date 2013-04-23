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
class classMapTest extends TubePressUnitTest
{
    public function testClassMapValidity()
    {
        $classMap = require dirname(__FILE__) . '/../../../main/php/scripts/classMap.php';

        $this->assertTrue(is_array($classMap));

        $this->assertTrue(tubepress_impl_util_LangUtils::isAssociativeArray($classMap));

        foreach ($classMap as $className => $path) {

            $this->assertTrue(is_readable($path) && is_file($path));

            if (!class_exists($className) && !interface_exists($className)) {

                require $path;

                $this->assertTrue(class_exists($className) || interface_exists($className));
            }
        }
    }
}