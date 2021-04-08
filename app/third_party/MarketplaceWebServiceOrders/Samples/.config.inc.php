<?php

   /************************************************************************
    * REQUIRED
    *
    * Access Key ID and Secret Acess Key ID, obtained from:
    * http://aws.amazon.com
    ***********************************************************************/


   /************************************************************************
    * REQUIRED
    *
    * All MWS requests must contain a User-Agent header. The application
    * name and version defined below are used in creating this value.
    ***********************************************************************/
    define('APPLICATION_NAME', 'EcommEliteProductExporter');
    define('APPLICATION_VERSION', '1.0');

   /************************************************************************
    * REQUIRED
    *
    * All MWS requests must contain the seller's merchant ID and
    * marketplace ID.
    ***********************************************************************/
	


   /************************************************************************
    * OPTIONAL ON SOME INSTALLATIONS
    *
    * Set include path to root of library, relative to Samples directory.
    * Only needed when running library from local directory.
    * If library is installed in PHP include path, this is not needed
    ***********************************************************************/
    set_include_path(get_include_path() . PATH_SEPARATOR . '../../.');

   /************************************************************************
    * OPTIONAL ON SOME INSTALLATIONS
    *
    * Autoload function is reponsible for loading classes of the library on demand
    *
    * NOTE: Only one __autoload function is allowed by PHP per each PHP installation,
    * and this function may need to be replaced with individual require_once statements
    * in case where other framework that define an __autoload already loaded.
    *
    * However, since this library follow common naming convention for PHP classes it
    * may be possible to simply re-use an autoload mechanism defined by other frameworks
    * (provided library is installed in the PHP include path), and so classes may just
    * be loaded even when this function is removed
	
    ***********************************************************************/


    spl_autoload_register(function ($class) {
        $filePath = str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
        $includePaths = explode(PATH_SEPARATOR, get_include_path());
        $includePaths[] = app_path('third_party');
        if($filePath=='App\Helpers\MarketplaceWebServiceOrders/Client.php')
        {
            $path ='/home/vagrant/Code/ecomm-laravel/app/third_party/MarketplaceWebServiceOrders/Client.php';
            if(file_exists($path)){
                echo "loaded";
                require_once $path;

                $service = new MarketplaceWebServiceOrders_Client(
                    '',
                    '',
                    '',
                    '',
                    '');
                return ;
            }
        }

//        echo $class."<br/>";
        foreach($includePaths as $includePath){
            // echo $includePath . DIRECTORY_SEPARATOR . $filePath."<br/>";
            if(file_exists($includePath . DIRECTORY_SEPARATOR . $filePath)){
                require_once $includePath . DIRECTORY_SEPARATOR .$filePath;
                return;
            }
        }
    });

//    function __autoload($className){
//        $filePath = str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
//        $includePaths = explode(PATH_SEPARATOR, get_include_path());
//		$includePaths[] = app_path('third_party');
//		foreach($includePaths as $includePath){
//            if(file_exists($includePath . DIRECTORY_SEPARATOR . $filePath)){
//				require_once $includePath . DIRECTORY_SEPARATOR .$filePath;
//                return;
//            }
//        }


