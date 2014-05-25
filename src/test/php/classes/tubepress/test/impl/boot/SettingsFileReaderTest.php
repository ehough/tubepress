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
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @covers tubepress_impl_boot_BootSettings<extended>
 */
class tubepress_test_impl_boot_BootSettingsTest extends tubepress_test_TubePressUnitTest
{
    /**
     * @var tubepress_impl_boot_BootSettings
     */
    private $_sut;

    /**
     * @var string
     */
    private $_userContentDirectory;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockLogger;

    public function onSetup()
    {
        $this->_mockLogger              = $this->mock(tubepress_api_log_LoggerInterface::_);

        $this->_mockLogger->shouldReceive('isEnabled')->once()->andReturn(true);
        $this->_mockLogger->shouldReceive('debug')->atLeast(1);

        $this->_sut                     = new tubepress_impl_boot_BootSettings($this->_mockLogger);

        $this->_userContentDirectory = sys_get_temp_dir() . '/tubepress-container-cache/';

        if (is_dir($this->_userContentDirectory)) {

            $this->recursivelyDeleteDirectory($this->_userContentDirectory);
        }

        mkdir($this->_userContentDirectory . '/config', 0777, true);

        define('TUBEPRESS_CONTENT_DIRECTORY', $this->_userContentDirectory);
    }

    public function onTearDown()
    {
        unset($_GET['hello']);
        $this->recursivelyDeleteDirectory($this->_userContentDirectory);
    }

    public function testContainerStoragePathWritableDirectory()
    {
        $path = $this->_userContentDirectory . 'foo';
        mkdir($path, 0755, false);

        if (!is_dir($path) || !is_writable($path)) {

            $this->fail('Could not create test dir.');
            return;
        }

        $this->_writeBootConfig(<<<EOF
<?php
return array(
    'system' => array(
        'cache' => array(

            'containerStoragePath'  => '$path'
        )
    )
);
EOF
        );

        $result = $this->_sut->getPathToContainerCacheFile();

        $this->assertEquals($path . '/tubepress-service-container.php', $result);
    }

    public function testContainerStoragePathCreateDirectory()
    {
        $path = $this->_userContentDirectory . 'foo';

        $this->_writeBootConfig(<<<EOF
<?php
return array(
    'system' => array(
        'cache' => array(

            'containerStoragePath'  => '$path'
        )
    )
);
EOF
        );

        $result = $this->_sut->getPathToContainerCacheFile();

        $this->assertEquals($path . '/tubepress-service-container.php', $result);
    }

    public function testContainerStoragePathNonWritableDirectory()
    {

        $this->_writeBootConfig(<<<EOF
<?php
return array(
    'system' => array(
        'cache' => array(

            'containerStoragePath'  => '/sdfkklsjdflkslkjsklfjskljsflksjdfklsjklsjfksldfjsdf'
        )
    )
);
EOF
        );

        $result = $this->_sut->getPathToContainerCacheFile();

        $this->assertRegExp('~[^/]+/tubepress-container-cache/[a-f0-9]+/tubepress-service-container\.php~', $result);
    }

    public function testFallback()
    {
        $this->_verifyAllDefaults();
    }

    public function testCustomAddonsBlacklist()
    {
        $this->_writeBootConfig(<<<EOF
<?php
return array(
    'system' => array(
        'cache' => array(

            'instance'  => new ehough_stash_Pool(new ehough_stash_driver_Ephemeral()),
            'killerKey' => 'hello',
        ),
        'add-ons' => array(

            'blacklist' => array('some', 'thing', 'else'),
        ),
        'classloader' => array(

            'enabled' => true,
        )
    )
);
EOF
);

        $result = $this->_sut->getAddonBlacklistArray();

        $this->assertTrue(is_array($result));
        $this->assertEquals(array('some', 'thing', 'else'), $result);
    }

    public function testNonPhpBootFile()
    {
        $this->_writeBootConfig(<<<EOF
this should be php
EOF
        );

        $this->_verifyAllDefaults();
    }

    public function testClearCache()
    {

        $this->_writeBootConfig(<<<EOF
<?php
return array(

    'system' => array(
        'cache' => array(

            'instance'  => new ehough_stash_Pool(new ehough_stash_driver_Ephemeral()),
            'killerKey' => 'hello',
        ),
        'add-ons' => array(

            'blacklist' => array('some', 'thing', 'else'),
        ),
        'classloader' => array(

            'enabled' => true,
        )
    )
);
EOF
        );

        $_GET['hello'] = 'true';

        $result = $this->_sut->getAddonBlacklistArray();

        $this->assertTrue(is_array($result));
        $this->assertEquals(array('some', 'thing', 'else'), $result);
    }

    public function testNonReturningPhpBootFile()
    {
        $this->_writeBootConfig(<<<EOF
<?php
\$x = 'x';
EOF
        );

        $this->_verifyAllDefaults();
    }

    public function testMissingCacheConfig()
    {

        $this->_writeBootConfig(<<<EOF
<?php
return array(
    'system' => array(
        'add-ons' => array(

            'blacklist' => array('some', 'thing', 'else'),
        ),
        'classloader' => array(

            'enabled' => true,
        )
    )
);
EOF
        );

        $result = $this->_sut->getPathToContainerCacheFile();
        $this->assertTrue(is_writable(dirname($result)));
        $this->assertTrue(!is_dir($result));
    }

    public function testMissingBlacklist()
    {
        $this->_writeBootConfig(<<<EOF
<?php
return array(
    'system' => array(
        'cache' => array(

            'killerKey' => 'hello',
        ),
        'add-ons' => array(),
        'classloader' => array(

            'enabled' => true,
        )
    )
);
EOF
        );

        $result = $this->_sut->getAddonBlacklistArray();

        $this->assertTrue(is_array($result));
        $this->assertTrue(empty($result));
    }

    public function testNonArrayCacheConfig()
    {

        $this->_writeBootConfig(<<<EOF
<?php
return array(
    'system' => array(
        'cache' => 'hi',
        'add-ons' => array(

            'blacklist' => array('some', 'thing', 'else'),
        ),
        'classloader' => array(

            'enabled' => true,
        )
    )
);
EOF
        );

        $result = $this->_sut->getPathToContainerCacheFile();
        $this->assertTrue(is_writable(dirname($result)));
        $this->assertTrue(!is_dir($result));
    }

    public function testNonStringKillerKey()
    {
        $this->_writeBootConfig(<<<EOF
<?php
return array(
    'system' => array(
        'cache' => array(

            'killerKey' => array(),
        ),
        'add-ons' => array(

            'blacklist' => array(),
        ),
        'classloader' => array(

            'enabled' => true,
        )
    )
);
EOF
        );

        $result = $this->_sut->getPathToContainerCacheFile();
        $this->assertTrue(is_writable(dirname($result)));
        $this->assertTrue(!is_dir($result));
    }

    public function testNonArrayBlacklist()
    {
        $this->_writeBootConfig(<<<EOF
<?php
return array(
    'system' => array(
        'cache' => array(

            'instance'  => new ehough_stash_Pool(new ehough_stash_driver_Ephemeral()),
            'killerKey' => 'hello',
        ),
        'add-ons' => array(

            'blacklist' => 3,
        ),
        'classloader' => array(

            'enabled' => true,
        )
    )
);
EOF
        );

        $result = $this->_sut->getAddonBlacklistArray();

        $this->assertTrue(is_array($result));
        $this->assertEquals(array(), $result);
    }

    public function testNonBooleanClassLoaderEnablement()
    {
        $this->_writeBootConfig(<<<EOF
<?php
return array(
    'system' => array(
        'cache' => array(

            'instance'  => new ehough_stash_Pool(new ehough_stash_driver_Ephemeral()),
            'killerKey' => 'hello',
        ),
        'add-ons' => array(

            'blacklist' => array(),
        ),
        'classloader' => array(

            'enabled' => 'hello',
        )
    )
);
EOF
        );

        $result = $this->_sut->isClassLoaderEnabled();
        $this->assertTrue($result);
    }

    private function _writeBootConfig($contents)
    {
        file_put_contents($this->_userContentDirectory . 'config/settings.php', $contents);
    }

    private function _verifyAllDefaults()
    {
        $result = $this->_sut->getAddonBlacklistArray();

        $this->assertTrue(is_array($result));
        $this->assertTrue(empty($result));

        $result = $this->_sut->getAddonBlacklistArray();

        $this->assertTrue(is_array($result));
        $this->assertTrue(empty($result));

        $result = $this->_sut->isClassLoaderEnabled();
        $this->assertTrue($result);

        $result = $this->_sut->getPathToContainerCacheFile();
        $this->assertTrue(is_writable(dirname($result)));
        $this->assertTrue(!is_dir($result));

        $result = $this->_sut->isContainerCacheEnabled();
        $this->assertTrue($result);
    }
}