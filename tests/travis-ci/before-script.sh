#!/usr/bin/env bash
#
# Copyright 2006 - 2018 TubePress LLC (http://tubepress.com)
#
# This file is part of TubePress (http://tubepress.com)
#
# This Source Code Form is subject to the terms of the Mozilla Public
# License, v. 2.0. If a copy of the MPL was not distributed with this
# file, You can obtain one at http://mozilla.org/MPL/2.0/.
#

##############################################################################################
#### FUNCTION DECLARATIONS
##############################################################################################

#
# echos the major PHP version (i.e. 5, 7, or HHVM) as a string
#
get_php_major_version_as_string ()
{
    # php --version will spit out something like
    # 5.x:  PHP 5.6.5 (cli) (built: Feb 12 2015 01:41:10)
    # 7.x   PHP 7.0.4 (cli) (built: Mar  6 2016 19:45:20) ( ZTS )
    # HHVM: HipHop VM 3.6.6 (rel)

    MAJOR_PHP_VERSION=`php --version | head -n 1 | cut -d " " -f 2`

    if [ $MAJOR_PHP_VERSION == "VM" ]; then

        echo "HHVM"

    else

        MAJOR_PHP_VERSION=`echo $MAJOR_PHP_VERSION | cut -d . -f 1`
        MAJOR_PHP_VERSION=$(( MAJOR_PHP_VERSION ))

        echo "$MAJOR_PHP_VERSION"
    fi
}

#
# PHP 7 and HHVM need PHPUnit 5.x to work correctly.
#
upgrade_phpunit_if_necessary ()
{
    MAJOR_PHP_VERSION=$(get_php_major_version_as_string)

    if [ "$MAJOR_PHP_VERSION" != "5" ]; then

        # upgrade PHPUnit to 5.x
        composer remove  --dev phpunit/phpunit
        composer remove  --dev phpunit/phpunit-mock-objects
        composer remove  --dev phpunit/php-code-coverage
        composer require --dev phpunit/phpunit

        # go nuclear on composer, just to be extra sure
        rm -rf vendor composer.lock bin

        # then re-install
        composer install
    fi
}

run_composer_install ()
{
    composer install
}

##############################################################################################
#### MAIN ROUTINE
##############################################################################################

upgrade_phpunit_if_necessary

run_composer_install

