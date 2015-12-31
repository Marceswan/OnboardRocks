<?php

/******************************* LOADING & INITIALIZING BASE APPLICATION ****************************************/

// Configuration for error reporting, useful to show every little problem during development
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Load Composer's PSR-4 autoloader (necessary to load Slim, Onboard etc.)
require '../vendor/autoload.php';

// Initialize Slim (the router/micro framework used)
$app = new \Slim\Slim();

// and define the engine used for the view @see http://twig.sensiolabs.org
$app->view = new \Slim\Views\Twig();
$app->view->setTemplatesDirectory("../Onboard/view");

/******************************************* THE CONFIGS *******************************************************/

// Configs for mode "development" (Slim's default), see the GitHub readme for details on setting the environment
$app->configureMode('development', function () use ($app) {

    // pre-application hook, performs stuff before real action happens @see http://docs.slimframework.com/#Hooks
    $app->hook('slim.before', function () use ($app) {

        // SASS-to-CSS compiler @see https://github.com/panique/php-sass
        SassCompiler::run("scss/", "css/");

        // CSS minifier @see https://github.com/matthiasmullie/minify
        $minifier = new MatthiasMullie\Minify\CSS('css/style.css');
        $minifier->minify('css/style.css');

        // JS minifier @see https://github.com/matthiasmullie/minify
        // DON'T overwrite your real .js files, always save into a different file
        $minifier = new MatthiasMullie\Minify\JS('js/index-page.js');
        $minifier->minify('js/index-page.minified.js');
        $minifier = new MatthiasMullie\Minify\JS('js/search-properties.js');
        $minifier->minify('js/search-properties.minified.js');
    });

    // Set the configs for development environment
    // Get Onboard Property API Key here, https://developer.onboard-apis.com/
    $app->config(array(
        'debug' => true,
        'obpropapi' => array(
            'api_url' => 'https://search.onboard-apis.com/propertyapi/v1.0.0/',
            'api_key' => 'Insert Your Onboard Property API Key'
        )
    ));
});

/******************************************** THE MODEL ********************************************************/

// Initialize the model, pass the api configs. $model can now perform all methods from Onboard\model\model.php
$model = new \Onboard\Model\Model($app->config('obpropapi'));

/************************************ THE ROUTES / CONTROLLERS *************************************************/

// GET request on homepage, simply show the view template index.twig
$app->get('/', function () use ($app) {
    $app->render('index.twig');
});
   
// GET requests on /property-records
$app->group('/property-records', function () use ($app, $model) {
    
    //$app->get('/', function () use ($app, $model) {
    //    $app->render('index.twig');
    //});
    
    // perform search based on address
    $app->get('/search', function () use ($app, $model) {
        
        $address1 = isset($_GET['address1']) ? $_GET['address1'] : '';
        $address2 = isset($_GET['address2']) ? $_GET['address2'] : '';
        $radius = isset($_GET['radius']) ? $_GET['radius'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : '';
        $lat = isset($_GET['lat']) ? $_GET['lat'] : '';
        $lng = isset($_GET['lng']) ? $_GET['lng'] : '';
        
        if ((empty($address1)) || (empty($address2)) || (empty($radius))) {
            $app->render('property.search-results.twig', array(
                    'statusmsg' => "Input parameters address1, address2 and radius are required"
                 ));
        } else {
            $result_properties = $model->searchPropertyByAddress($address1,$address2,$radius,$page);
    
            if ($result_properties['status']['code'] == 0) {
                $app->render('property.search-results.twig', array(
                    'address1' => $address1,
                    'address2' => $address2,
                    'radius' => $radius,
                    'page' => $page,
                    'lat' => $lat,
                    'lng' => $lng,
                    'status' => $result_properties['status'],
                    'properties' => $result_properties['property']
                ));
            } else {
               $app->render('property.search-results.twig', array(
                    'statusmsg' => $result_properties['status']['msg'],
                    'address1' => $address1,
                    'address2' => $address2,
                    'radius' => $radius,
                    'page' => $page,
                    'lat' => $lat,
                    'lng' => $lng
                    ));
            }
        }
    });
    
    // get property sales history based on onboard property id
    $app->get('/sales-history', function () use ($app, $model) {

        $propertyid = isset($_GET['propertyid']) ? $_GET['propertyid'] : '';
        
        if (empty($propertyid)) {
            $app->render('property.sales-history.twig', array(
                    'statusmsg' => "Input parameter propertyid is required"
                 ));
        } else {
            $result_sales_history = $model->getSalesHistory($propertyid);
    
            if ($result_sales_history['status']['code'] == 0) {
                $app->render('property.sales-history.twig', array(
                    'salehistory' => $result_sales_history['property'][0]['salehistory']
                    ));
            } else {
               $app->render('property.sales-history.twig', array(
                    'statusmsg' => "Error:" . $result_sales_history['status']['code'] . ", " . $result_sales_history['status']['msg']
                    ));
            }
        }    
    });
    
    // get county tax assessment based on onboard property id
    $app->get('/assessment', function () use ($app, $model) {

        $propertyid = isset($_GET['propertyid']) ? $_GET['propertyid'] : '';
        
        if (empty($propertyid)) {
            $app->render('property.assessment.twig', array(
                    'statusmsg' => "Input parameter propertyid is required"
                 ));
        } else {
            $result_assessment = $model->getAssessment($_GET["propertyid"]);
    
            if ($result_assessment['status']['code'] == 0) {
                $app->render('property.assessment.twig', array(
                    'assessment' => $result_assessment['property'][0]['assessment']
                ));
            } else {
               $app->render('property.assessment.twig', array(
                    'statusmsg' => "Error:" . $result_assessment['status']['code'] . ", " . $result_assessment['status']['msg']
                    ));
            }
        }    
    });
    
    // get county tax assessment based on onboard property id
    $app->get('/avm', function () use ($app, $model) {

        $propertyid = isset($_GET['propertyid']) ? $_GET['propertyid'] : '';
        
        if (empty($propertyid)) {
            $app->render('property.avm.twig', array(
                    'statusmsg' => "Input parameter propertyid is required"
                 ));
        } else {
            $result_avm = $model->getAVM($_GET["propertyid"]);
    
            if ($result_avm['status']['code'] == 0) {
                $app->render('property.avm.twig', array(
                    'avm' => $result_avm['property'][0]['avm']
                ));
            } else {
               $app->render('property.avm.twig', array(
                    'statusmsg' => "Error:" . $result_avm['status']['code'] . ", " . $result_avm['status']['msg']
                    ));
            }
        }   
    });
});

/******************************************* RUN THE APP *******************************************************/

$app->run();

