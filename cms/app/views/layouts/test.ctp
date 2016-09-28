<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?PHP echo $html->css('main_css')?>
<?PHP echo $html->css('style')?>
<?php echo $this->element('header_menu',array('parent_code'=>$parent_code))?>
<div class="test-center">
	<div class="test-blue">
    	<div class="test-white">
        	<div class="test-left">
            	<div class="test-sidebar">
                	<ul>
                    	<li>
                        	<h2>Test Test Test Test Test Test Test Test Test</h2>
                            <ul>
                            	<li><a href="">Test Test Test Test Test Test Test Test Test Test</a>
                                	<ul>
                                    	<li><a href="">Test</a></li>
                                        <li><a href="">Test</a></li>
                                        <li><a href="">Test</a></li>
                                    </ul>
                                </li>
                                <li><a href="">Test</a></li>
                                <li><a href="">Test</a></li>
                            </ul>
                        </li>
                        <li>
                        	<h2>Test</h2>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="test-right">
            </div>
        </div>
    </div>
</div>
<?php echo $this->element('footer_menu')?>
</body>
</html>