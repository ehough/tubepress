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
class org_tubepress_impl_template_templates_optionspage_TabTemplateTest extends TubePressUnitTest
{
    public function test()
    {
        $one = \Mockery::mock(tubepress_spi_options_ui_Field::_);
        $one->shouldReceive('getHtml')->once()->andReturn('one-html');
        $one->shouldReceive('getTitle')->once()->andReturn('one-title');
        $one->shouldReceive('getDescription')->once()->andReturn('one-description');
        $one->shouldReceive('isProOnly')->once()->andReturn(true);
        $one->shouldReceive('getArrayOfApplicableProviderNames')->once()->andReturn(array('foo', 'bar'));

        $two = \Mockery::mock(tubepress_spi_options_ui_Field::_);
        $two->shouldReceive('getHtml')->once()->andReturn('two-html');
        $two->shouldReceive('getTitle')->once()->andReturn('two-title');
        $two->shouldReceive('getDescription')->once()->andReturn('two-description');
        $two->shouldReceive('isProOnly')->once()->andReturn(false);
        $two->shouldReceive('getArrayOfApplicableProviderNames')->once()->andReturn(array());

        ${tubepress_impl_options_ui_tabs_AbstractTab::TEMPLATE_VAR_WIDGETARRAY} = array($one, $two);

        ob_start();
        include __DIR__ . '/../../../../../main/resources/system-templates/options_page/tab.tpl.php';
        $result = ob_get_contents();
        ob_end_clean();

        $this->assertEquals(tubepress_impl_util_StringUtils::removeEmptyLines($this->_expected()), tubepress_impl_util_StringUtils::removeEmptyLines($result));
    }

    private function _expected()
    {
        return <<<EOT
<table class="tubepress-tab">
    <tr class="tubepress-foo-option tubepress-bar-option tubepress-pro-option">
		<th>one-title</th>
		<td>
		    one-html			<br />
			one-description		</td>
	</tr>
    <tr class="">
		<th>two-title</th>
		<td>
		    two-html			<br />
			two-description		</td>
	</tr>
	</table>
EOT;
    }

}