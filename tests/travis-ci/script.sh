#!/usr/bin/env bash
#
# Copyright 2006 - 2016 TubePress LLC (http://tubepress.com)
#
# This file is part of TubePress (http://tubepress.com)
#
# This Source Code Form is subject to the terms of the Mozilla Public
# License, v. 2.0. If a copy of the MPL was not distributed with this
# file, You can obtain one at http://mozilla.org/MPL/2.0/.
#

bin/phpunit -d memory_limit=2048M --verbose -c tests/phpunit.xml --testsuite unit
