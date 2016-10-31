<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'Home', 'action' => 'Index'));
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

Router::connect(
    '/DaftarMotor/*',
    array('controller' => 'Home',"action"=>"DaftarMotor")
);
Router::connect(
    '/MotorMurah/*',
    array('controller' => 'MotorMurah',"action"=>"Index")
);
Router::connect(
    '/MotorKredit/*',
    array('controller' => 'MotorKredit',"action"=>"Index")
);
Router::connect(
    '/MotorGede/*',
    array('controller' => 'MotorGede',"action"=>"Index")
);
Router::connect(
    '/MotorKlasik/*',
    array('controller' => 'MotorKlasik',"action"=>"Index")
);
Router::connect(
    '/DaftarHarga/*',
    array('controller' => 'DaftarHarga',"action"=>"Index")
);
Router::connect(
    '/Cpanel/UploadPhoto',
    array('controller' => 'CpanelPhoto',"action"=>"Index")
);

Router::connect(
    '/Cpanel/UploadLogo',
    array('controller' => 'CpanelLogo',"action"=>"Index")
);

Router::connect(
    '/Cpanel/CompanyProfile',
    array('controller' => 'CpanelCompanies',"action"=>"Index")
);
Router::connect(
    '/Cpanel/AddProduct/:action/*',
    array('controller' => 'AddProduct')
);
Router::connect(
    '/Cpanel/AddProduct',
    array('controller' => 'AddProduct','action'=>'Index')
);