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
 * @covers tubepress_impl_contrib_ContributableBase<extended>
 */
class tubepress_test_impl_contrib_ContributableBaseTest extends tubepress_test_TubePressUnitTest
{
    public function testNormalConstruction1()
    {
        $sut = new tubepress_impl_contrib_ContributableBase(

            'NAme',
            '1.0.0',
            'description',
            array('name' => 'eric'),
            array(array('type' => 'proprietary'))
        );

        $this->assertEquals('name', $sut->getName());
        $this->assertEquals('1.0.0', (string) $sut->getVersion());
        $this->assertEquals('description', $sut->getTitle());
        $this->assertEquals(array('name' => 'eric'), $sut->getAuthor());
        $this->assertEquals(array(array('type' => 'proprietary')), $sut->getLicenses());
    }

    public function testNormalConstruction2()
    {
        $sut = new tubepress_impl_contrib_ContributableBase(

            'name',
            '2.3.1',
            'description',
            array('name' => 'eric', 'url' => 'http://foo.bar'),
            array(array('type' => 'proprietary'))
        );

        $this->assertEquals('name', $sut->getName());
        $this->assertEquals('2.3.1', (string) $sut->getVersion());
        $this->assertEquals('description', $sut->getTitle());
        $actualAuthor = $sut->getAuthor();
        $this->assertEquals('eric', $actualAuthor['name']);
        $this->assertEquals(array(array('type' => 'proprietary')), $sut->getLicenses());
    }

    public function testInvalidNameBadChars()
    {
        $this->setExpectedException('InvalidArgumentException', 'Invalid name.');

        new tubepress_impl_contrib_ContributableBase(

            '@#*(&*&',
            '1.2.3',
            'description',
            array('name' => 'eric'),
            array(array('url' => 'http://tubepress.com'))
        );
    }

    public function testInvalidNameTooLong()
    {
        $this->setExpectedException('InvalidArgumentException', 'Invalid name.');

        new tubepress_impl_contrib_ContributableBase(

            'wlkjwkljrwklejfwklfjsdklfjsdklfjsdgkljgkljdfkljsdfklaejerklwjfsklfjskldjskldfjsklfjsklgjwekltjsdklvjhxdjkvhsklfjsdjkfhsdjklfhsdfjhsdfsg',
            '1.2.3',
            'description',
            array('name' => 'eric'),
            array(array('url' => 'http://tubepress.com'))
        );
    }

    public function testInvalidNameTooShort()
    {
        $this->setExpectedException('InvalidArgumentException', 'Invalid name.');

        new tubepress_impl_contrib_ContributableBase(

            '',
            '1.2.3',
            'description',
            array('name' => 'eric'),
            array(array('url' => 'http://tubepress.com'))
        );
    }

    public function testNonStringName()
    {
        $this->setExpectedException('InvalidArgumentException', 'Name must be a string');

        new tubepress_impl_contrib_ContributableBase(

            array(),
            '1.2.3',
            'description',
            array('name' => 'eric'),
            array(array('url' => 'http://tubepress.com'))
        );
    }

    public function testInvalidTitleTooLong()
    {
        $this->setExpectedException('InvalidArgumentException', 'Title must be 255 characters or less.');

        new tubepress_impl_contrib_ContributableBase(

            'foobar',
            '1.2.3',
            'wlkjwkljrwklejfwklfjsdklfjsdklfjsdgkljgkljdfkljsdfklaejerklwjfsklfjskldjskldfjsklfjsklgjwekltjsdklvjhxdjkvhsklfjsdjkfhsdjklfhsdfjhsdfsg'
            . 'wlkjwkljrwklejfwklfjsdklfjsdklfjsdgkljgkljdfkljsdfklaejerklwjfsklfjskldjskldfjsklfjsklgjwekltjsdklvjhxdjkvhsklfjsdjkfhsdjklfhsdfjhsdfsg',
            array('name' => 'eric'),
            array(array('url' => 'http://tubepress.com'))
        );
    }

    public function testInvalidTitleNonString()
    {
        $this->setExpectedException('InvalidArgumentException', 'Title must be a string');

        new tubepress_impl_contrib_ContributableBase(

            'foobar',
            '1.2.3',
            array(),
            array('name' => 'eric'),
            array(array('url' => 'http://tubepress.com'))
        );
    }

    public function testInvalidAuthorAttribute()
    {
        $this->setExpectedException('InvalidArgumentException', 'Author information must only include name, email, and/or URL');

        new tubepress_impl_contrib_ContributableBase(

            'name',
            '1.0.0',
            'description',
            array('name' => 'eric', 'foo' => 'bar'),
            array(array('url' => 'http://tubepress.com'))
        );
    }

    public function testMissingAuthorName()
    {
        $this->setExpectedException('InvalidArgumentException', 'Must include author name');

        new tubepress_impl_contrib_ContributableBase(

            'name',
            '1.0.0',
            'description',
            array('foo' => 'eric'),
            array(array('url' => 'http://tubepress.com'))
        );
    }

    public function testInvalidAuthorEmail()
    {
        $this->setExpectedException('InvalidArgumentException', 'Author email is invalid.');

        new tubepress_impl_contrib_ContributableBase(

            'name',
            '1.0.0',
            'description',
            array('name' => 'eric', 'url' => 'http://foo.bar', 'email' => 'xyz'),
            array(array('type' => 'foobar'))
        );
    }

    public function testAuthor()
    {
        $contrib = new tubepress_impl_contrib_ContributableBase(

            'name',
            '1.0.0',
            'description',
            array('name' => 'eric', 'url' => 'http://foo.bar', 'email' => 'no@yes.com'),
            array(array('type' => 'foobar'))
        );

        $author = $contrib->getAuthor();

        $this->assertEquals('eric', $author['name']);
        $this->assertEquals('no@yes.com', $author['email']);
    }

    public function testInvalidLicenseAttribute()
    {
        $this->setExpectedException('InvalidArgumentException', 'Only \'url\' and \'type\' attributes are supported for licenses');

        new tubepress_impl_contrib_ContributableBase(

            'name',
            '2.3.1',
            'description',
            array('name' => 'eric', 'url' => 'http://foo.bar'),
            array(array('type' => 'proprietary', 'foo' => 'bar'))
        );
    }

    public function testMissingLicenseIsMissingType()
    {
        $this->setExpectedException('InvalidArgumentException', 'License is missing type');

        new tubepress_impl_contrib_ContributableBase(

            'name',
            '2.3.1',
            'description',
            array('name' => 'eric', 'url' => 'http://foo.bar'),
            array(array('foo' => 'bar'))
        );
    }

    public function testMissingLicense()
    {
        $this->setExpectedException('InvalidArgumentException', 'Must include at least one license.');

        new tubepress_impl_contrib_ContributableBase(

            'name',
            '2.3.1',
            'description',
            array('name' => 'eric', 'url' => 'http://foo.bar'),
            array()
        );
    }

    public function testLicense()
    {
        $contrib = new tubepress_impl_contrib_ContributableBase(

            'name',
            '2.3.1',
            'description',
            array('name' => 'eric', 'url' => 'http://foo.bar'),
            array(array('type' => 'foobar', 'url' => 'http://foo.bar/'))
        );

        $licenses = $contrib->getLicenses();
        $this->assertTrue(is_array($licenses));
        $this->assertCount(1, $licenses);

        $license = $licenses[0];
        $this->assertTrue(is_array($license));
        $this->assertCount(2, $license);

        $this->assertEquals('foobar', $license['type']);
        $this->assertEquals('http://foo.bar/', $license['url']);
    }

    public function testSetGetDescription()
    {
        $sut = new tubepress_impl_contrib_ContributableBase(

            'name',
            '1.0.0',
            'description',
            array('name' => 'eric'),
            array(array('type' => 'proprietary'))
        );

        $sut->setDescription('something');
        $this->assertEquals('something', $sut->getDescription());
    }

    public function testSetNonStringDescription()
    {
        $this->setExpectedException('InvalidArgumentException', 'Description must be a string');

        $addon = $this->_buildValidContributable();

        $addon->setDescription(array());
    }

    public function testDescriptionTooLong()
    {
        $this->setExpectedException('InvalidArgumentException', 'Description must be 2000 characters or less.');

        $addon = $this->_buildValidContributable();

        $addon->setDescription(str_repeat('x', 2001));
    }

    public function testGetSetKeywords()
    {
        $addon = $this->_buildValidContributable();

        $addon->setKeywords(array('foo', 'bar'));
        $this->assertEquals(array('foo', 'bar'), $addon->getKeywords());
    }

    public function testInvalidKeywordTooLong()
    {
        $this->setExpectedException('InvalidArgumentException', 'Each keyword must be 100 characters or less.');

        $addon = $this->_buildValidContributable();

        $addon->setKeywords(array(str_repeat('x', 101)));
    }

    public function testInvalidKeywordTooShort()
    {
        $this->setExpectedException('InvalidArgumentException', 'Keywords must not be empty.');

        $addon = $this->_buildValidContributable();

        $addon->setKeywords(array(''));
    }

    public function testSetNonStringKeyword()
    {
        $this->setExpectedException('InvalidArgumentException', 'Each keyword must be a string');

        $addon = $this->_buildValidContributable();

        $addon->setKeywords(array('foo', array()));
    }

    public function testGetSetBugsUrl()
    {
        $addon = $this->_buildValidContributable();

        $addon->setBugTrackerUrl('http://foo.bar');
        $this->assertEquals('http://foo.bar', $addon->getBugTrackerUrl());
    }

    public function testGetSetDownloadUrl()
    {
        $addon = $this->_buildValidContributable();

        $addon->setDownloadUrl('http://foo.bar');
        $this->assertEquals('http://foo.bar', $addon->getDownloadUrl());
    }

    public function testGetSetDemoUrl()
    {
        $addon = $this->_buildValidContributable();

        $addon->setDemoUrl('http://foo.bar');
        $this->assertEquals('http://foo.bar', $addon->getDemoUrl());
    }

    public function testGetSetDocsUrl()
    {
        $addon = $this->_buildValidContributable();

        $addon->setDocumentationUrl('http://foo.bar');
        $this->assertEquals('http://foo.bar', $addon->getDocumentationUrl());
    }

    public function testGetSetHomePageUrl()
    {
        $addon = $this->_buildValidContributable();

        $addon->setHomepageUrl('http://foo.bar');
        $this->assertEquals('http://foo.bar', $addon->getHomepageUrl());
    }

    public function testScreenshots()
    {
        $sut = $this->_buildValidContributable();

        $sut->setScreenshots(array('http://foo.bar/one.png'));
        $this->assertEquals(array('http://foo.bar/one.png'), $sut->getScreenshots());
    }

    private function _buildValidContributable()
    {
        return new tubepress_impl_contrib_ContributableBase(

            'name',
            '2.3.1',
            'description',
            array('name' => 'eric', 'url' => 'http://foo.bar'),
            array(array('type' => 'proprietary'))
        );
    }
}