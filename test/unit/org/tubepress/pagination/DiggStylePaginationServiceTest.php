<?php

require_once dirname(__FILE__) . '/../../../../../classes/org/tubepress/pagination/DiggStylePaginationService.class.php';

class org_tubepress_pagination_DiggStylePaginationServiceTest extends PHPUnit_Framework_TestCase {

    public function testAjax()
    {
        global $_SERVER;
        $_SERVER['SERVER_PORT'] = 80;
        $_SERVER['SERVER_NAME'] = 'ehough.com';
        $_SERVER['REQUEST_URI'] = '';
        $this->_tester('digg_test_callback_with_ajax',<<<EOT
<div class="pagination"><span class="disabled">prev</span><span class="current">1</span><a rel="page=2">2</a><a rel="page=3">3</a><a rel="page=4">4</a><a rel="page=5">5</a>... <a rel="page=24">24</a><a rel="page=25">25</a><a rel="page=2">next</a></div>

EOT
);
    }
    
    public function testNoAjax()
    {
        global $_SERVER;
        $_SERVER['SERVER_PORT'] = 80;
        $_SERVER['SERVER_NAME'] = 'ehough.com';
        $_SERVER['REQUEST_URI'] = '';
        $this->_tester('digg_test_callback_without_ajax',<<<EOT
<div class="pagination"><span class="disabled">prev</span><span class="current">1</span><a rel="nofollow" href="http://ehough.com?tubepress_page=2">2</a><a rel="nofollow" href="http://ehough.com?tubepress_page=3">3</a><a rel="nofollow" href="http://ehough.com?tubepress_page=4">4</a><a rel="nofollow" href="http://ehough.com?tubepress_page=5">5</a>... <a rel="nofollow" href="http://ehough.com?tubepress_page=24">24</a><a rel="nofollow" href="http://ehough.com?tubepress_page=25">25</a><a rel="nofollow" href="http://ehough.com?tubepress_page=2">next</a></div>

EOT
);
    }
    
	private function _tester($callback, $expected)
	{
		$tpom = $this->getMock("org_tubepress_options_manager_OptionsManager");
        $tpom->expects($this->exactly(2))
             ->method('get')
             ->will($this->returnCallback($callback));
		
		$msgService = $this->getMock("org_tubepress_message_MessageService");
		$msgService->expects($this->any())
				   ->method("_")
				   ->will($this->returnCallback("msgCallback"));	
			
		$sut = new org_tubepress_pagination_DiggStylePaginationService();
		$sut->setOptionsManager($tpom);
		$sut->setMessageService($msgService);
		
		$this->assertEquals($expected, $sut->getHtml(100));
	}
}

function digg_test_callback_without_ajax() {
    $args = func_get_args();
    $vals = array(
        org_tubepress_options_category_Display::AJAX_PAGINATION => false,
        org_tubepress_options_category_Display::RESULTS_PER_PAGE => 4
    );
    return $vals[$args[0]]; 
}

function digg_test_callback_with_ajax() {
    $args = func_get_args();
    $vals = array(
        org_tubepress_options_category_Display::AJAX_PAGINATION => true,
        org_tubepress_options_category_Display::RESULTS_PER_PAGE => 4
    );
    return $vals[$args[0]]; 
}

function msgCallback()
{
	$args = func_get_args();
	return $args[0];
}
?>