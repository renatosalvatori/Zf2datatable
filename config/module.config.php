<?php
namespace Zf2datatable;
return array(
    'zf2datatable' => array(
        'settings' => array(
            'default' => array(
                // If no specific rendere given, use this renderes for HTTP / console
                'renderer' => array(
                    'http' => 'bootstrapTable',
                    'console' => 'zendTable'
                )
            ),
            'export' => array(
                // Export is enabled?
                'enabled' => true,

                'formats' => array(
                    'PHPExcel' => 'Excel',
                    'printHtml' => 'Print',
                    'tcpdf' => 'PDF',
                    'csv' => 'CSV'
                ),

                // type => Displayname (Toolbar - you can use here HTML too...)
                // 'printHtml' => 'Print',
                // 'tcpdf' => 'PDF',
                // 'csv' => 'CSV',
                // 'PHPExcel' => 'Excel',

                // The output+save directory
                'path' => realpath(dirname(__FILE__) . '/../../../data'),

                // mode can be:
                // direct = PHP handles header + file reading
                // @TODO iframe = PHP generates the file and a hidden <iframe> sends the document (ATTENTION: your webserver must enable "force-download" for excel/pdf/...)
                'mode' => 'direct'
            )
        ),

        // The cache is used to save the filter + sort and other things for exporting
        'cache' => array(
            'adapter' => array(
                'name' => 'Session',
                'options' => array(
                    'ttl' => 100000, // cache with 200 hours,
                    'session_container'=> new \Zend\Session\Container('zfcache')
                )
            ),
            'plugins' => array(
                'exception_handler' => array(
                    'throw_exceptions' => false
                ),
                //'Serializer'
            )
        ),
        /*'cache' => array(
            'adapter' => array(
                'name' => 'Filesystem',
                'options' => array(
                    'ttl' => 100000, // cache with 200 hours,
                    'cache_dir' => realpath(dirname(__FILE__) . '/../../../data')
                )
            ),
            'plugins' => array(
                'exception_handler' => array(
                    'throw_exceptions' => false
                ),
                'Serializer'
            )
        ),*/
        'renderer' => array(
            'bootstrapTable' => array(
                'parameterNames' => array(
                    // Internal => bootstrapTable
                    'currentPage' => 'currentPage',
                    'sortColumns' => 'sortByColumns',
                    'sortDirections' => 'sortDirections',
                    'massIds' => 'ids'
                ),
                'templatesOverwrite'=>array(
                    'layout'=>null,
                    'detail'=>null,
                    'formcrud'=>null
                )
            ),

            'jqGrid' => array(
                'parameterNames' => array(

                    // Internal => jqGrid
                    'currentPage' => 'currentPage',
                    'itemsPerPage' => 'itemsPerPage',
                    'sortColumns' => 'sortByColumns',
                    'sortDirections' => 'sortDirections',
                    'isSearch' => 'isSearch',

                    'columnsHidden' => 'columnsHidden',
                    'columnsGroupByLocal' => 'columnsGroupBy',

                    'massIds' => 'ids'
                )
            ),
            'zendTable' => array(
                'parameterNames' => array(

                    // Internal => ZendTable (console)
                    'currentPage' => 'page',
                    'itemsPerPage' => 'items',
                    'sortColumns' => 'sortBys',
                    'sortDirections' => 'sortDirs',

                    'filterColumns' => 'filterBys',
                    'filterValues' => 'filterValues'
                )
            ),

            'PHPExcel' => array(

                'papersize' => 'A4',

                // landscape / portrait (we preferr landscape, because datagrids are often wide)
                'orientation' => 'landscape',

                // The worksheet name (will be translated if possible)
                'sheetName' => 'Data',

                // If you only want to export data, set this to false
                'displayTitle' => true,

                'rowTitle' => 1,
                'startRowData' => 3
            ),

            'TCPDF' => array(

                'papersize' => 'A4',

                // landscape / portrait (we preferr landscape, because datagrids are often wide)
                'orientation' => 'landscape',

                'margins' => array(
                    'header' => 5,
                    'footer' => 10,

                    'top' => 20,
                    'bottom' => 11,
                    'left' => 10,
                    'right' => 10
                ),

                'icon' => array(

                    // milimeter...
                    'size' => 16
                ),

                'header' => array(

                    // define your logo here, please be aware of the relative path...
                    'logo' => '',
                    'logoWidth' => 35
                ),

                'style' => array(

                    'header' => array(
                        'font' => 'helvetica',
                        'size' => 11,

                        'color' => array(
                            0,
                            0,
                            0
                        ),
                        'background-color' => array(
                            255,
                            255,
                            200
                        )
                    ),

                    'data' => array(
                        'font' => 'helvetica',
                        'size' => 11,

                        'color' => array(
                            0,
                            0,
                            0
                        ),
                        'background-color' => array(
                            255,
                            255,
                            255
                        )
                    )
                )
            ),

            'csv' => array(

                // draw a header with all column labels?
                'header' => true,
                'delimiter' => ',',
                'enclosure' => '"'
            )
        ),
        'form' => array(
            'elements' => array(
                'DateTimePicker' => array(
                    'status' => 'enabled',
                    'options' => array(
                        'datetime_format_in' => 'd-m-Y H:i',
                        'datetime_format_out' => 'Y-m-d H:i:s',
                        'datetime_format_mask_in' => '/^\d{2}-\d{2}-\d{4} [0-2][0-3]:[0-5][0-9]:[0-5][0-9]$/',
                        'datetime_format_mask_out' => '/^\d{4}-\d{2}-\d{2} [0-2][0-3]:[0-5][0-9]:[0-5][0-9]$/',
                        'datetime_js_properties' => 'language:\'it\'',
                        'date_format_in' => 'd-m-Y',
                        'date_format_out' => 'Y-m-d',
                        'date_format_mask_in' => '/^\d{1,2}-\d{1,2}-\d{4}$/',
                        'date_format_mask_out' => '/^\d{4}-\d{1,2}-\d{1,2}$/',
                        'date_js_properties' => 'language:\'it\',pickTime: false'
                    )
                )
            )
        ),

        // General parameters
        'generalParameterNames' => array(
            'rendererType' => 'rendererType'
        )
    ),

    'service_manager' => array(

        'invokables' => array(

            // HTML renderer
            'Zf2datatable.renderer.bootstrapTable' => 'Zf2datatable\Renderer\BootstrapTable\Renderer',
            'Zf2datatable.renderer.bootstrapTable.form' => 'Zf2datatable\Renderer\BootstrapTable\FormRenderer',
            'Zf2datatable.renderer.bootstrapTable.view' => 'Zf2datatable\Renderer\BootstrapTable\ViewRenderer',
            'Zf2datatable.renderer.jqgrid' => 'Zf2datatable\Renderer\JqGrid\Renderer',

            // CLI renderer
            'Zf2datatable.renderer.zendTable' => 'Zf2datatable\Renderer\ZendTable\Renderer',

            // Export renderer
            'Zf2datatable.renderer.printHtml' => 'Zf2datatable\Renderer\PrintHtml\Renderer',
            'Zf2datatable.renderer.PHPExcel' => 'Zf2datatable\Renderer\PHPExcel\Renderer',
            'Zf2datatable.renderer.TCPDF' => 'Zf2datatable\Renderer\TCPDF\Renderer',
            'Zf2datatable.renderer.csv' => 'Zf2datatable\Renderer\Csv\Renderer',

            // Datasource
            'Zf2datatable.datasource.zendSelect' => 'Zf2datatable\DataSource\ZendSelect',
            'Zf2datatable.datasource.zendTableSelect' => 'Zf2datatable\DataSource\ZendTableGateway',

            // Datasources example
            'zf2datatable.examples.data.phpArray' => 'Zf2datatable\Examples\Data\PhpArray',
            'zf2datatable.examples.data.doctrine2' => 'Zf2datatable\Examples\Data\Doctrine2',
            'zf2datatable.examples.data.zendSelect' => 'Zf2datatable\Examples\Data\ZendSelect',
            'zf2datatable.examples.data.zendTableGateway' => 'Zf2datatable\Examples\Data\ZendTableGateway',

            // FORM
            'zf2datatable.form' => 'Zf2datatable\Form\EventsForm',

            //SERVICE INVOKABLES
            'doctrine2service'=>'\Zf2datatable\Service\Doctrine2Service',

            //LISTENER  AGGREGATE
            'zf2datatable.listener'=>'\Zf2datatable\Zf2listener\DatasourceListenerAggregate'
        )
        ,

        'Factories' => array(
            'zf2datatable\Datagrid' => 'Zf2datatable\Service\DatagridFactory',
            'zf2datatable_adapter' => 'Zf2datatable\Service\ZendDbAdapterFactory',
            'zf2datatable_logger' => 'Zf2datatable\Service\LoggerServiceFactory',
            'zf2session_container'=>'Zf2datatable\Service\SessionServiceFactory'
        ),

        'aliases' => array(
            'zf2datatablegrid' => 'zf2datatable\Datagrid'
        ),
        'services' => array(
            'logger_config' => array(
                'path_filename' => realpath(dirname(__FILE__) . '/../../../data/logs'),
                'log_filename' => 'LOGGER_' . date('ymd', time()),
                'priority' => '7'
            ),
            'cache_metadata'=>array('mode'=>'enabled'),  // nembled -disabled
        ),
        'shared'=>array(
            //'zf2datatable\Datagrid'=>false
        )
    ),

    'view_helpers' => array(
        'invokables' => array(
            'bootstrapTableRow' => 'Zf2datatable\Renderer\BootstrapTable\View\Helper\TableRow',
            'jqgridColumns' => 'Zf2datatable\Renderer\JqGrid\View\Helper\Columns',
            'formckeditor' => 'Zf2datatable\Form\View\Helper\FormCKEditor'
        )
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy'
        ),

        'template_map' => array(
            'zf2datatable/renderer/bootstrapTable/layout' => __DIR__ . '/../view/zf2datatable/renderer/bootstrapTable/layout.phtml',
            'zf2datatable/renderer/printHtml/layout' => __DIR__ . '/../view/zf2datatable/renderer/printHtml/layout.phtml',
            'zf2datatable/renderer/printHtml/table' => __DIR__ . '/../view/zf2datatable/renderer/printHtml/table.phtml',
            'zf2datatable/renderer/jqGrid/layout' => __DIR__ . '/../view/zf2datatable/renderer/jqGrid/layout.phtml',
            'error/500' => __DIR__ . '/../view/zf2datatable/error/500.phtml',
        ),

        'template_path_stack' => array(
            'Zf2datatable' => __DIR__ . '/../view'
        )
    ),

    /**
     * ONLY EXAMPLE CONFIGURATION BELOW!!!!!!
     */
    'controllers' => array(
        'invokables' => array(
            'Zf2datatable\Examples\Controller\Person' => 'Zf2datatable\Examples\Controller\PersonController',
            'Zf2datatable\Examples\Controller\PersonDoctrine2' => 'Zf2datatable\Examples\Controller\PersonDoctrine2Controller',
            'Zf2datatable\Examples\Controller\PersonZend' => 'Zf2datatable\Examples\Controller\PersonZendController',
            'Zf2datatable\Examples\Controller\Minimal' => 'Zf2datatable\Examples\Controller\MinimalController',
            'Zf2datatable\Examples\Controller\Category' => 'Zf2datatable\Examples\Controller\CategoryController'
        )
    ),

    'router' => array(
        'routes' => array(
            'Zf2datatable' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/zf2datatable',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Zf2datatable\Examples\Controller',
                        'controller' => 'person',
                        'action' => 'bootstrap'
                    )
                ),

                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ),
                            'defaults' => array()
                        ),

                        'may_terminate' => true,
                        'child_routes' => array(
                            'wildcard' => array(
                                'type' => 'Wildcard',
                                'may_terminate' => true,
                                'child_routes' => array(
                                    'wildcard' => array(
                                        'type' => 'Wildcard'
                                    )
                                )
                            )
                        )
                    )
                )
            )
        )
    ),

    'console' => array(
        'router' => array(
            'routes' => array(
                'datagrid-example' => array(
                    'options' => array(
                        'route' => 'show grid [--page=] [--items=] [--filterBys=] [--filterValues=] [--sortBys=] [--sortDirs=] [--controller=] [--action=]',
                        'defaults' => array(
                            'controller' => 'AdminApplication\Controller\Index',
                            'action' => 'language'
                        )
                    )
                ),

                'datagrid-crud-example' => array(
                    'options' => array(
                        'route' => 'crud grid  [--crudOpt=] [--crudFieldValue=] [--controller=] [--action=]',
                        'defaults' => array(
                            'controller' => 'AdminApplication\Controller\Index',
                            'action' => 'console'
                        )
                    )
                )
            )
        )
    ),

    /**
     * The ZF2 DbAdapter + Doctrine2 connection is must for examples!
     */
    'zf2datatable_dbAdapter' => array(
        'driver' => 'Pdo_Mysql', // The database driver. Mysqli, Sqlsrv, Pdo_Sqlite, Pdo_Mysql, Pdo=OtherPdoDriver
        'database' => 'datagrid', // generally required the name of the database (schema)
        'username' => 'root', // generally required the connection username
        'password' => 'poi890', // generally required the connection password
        'hostname' => 'localhost', // not generally required the IP address or hostname to connect to
                                   // 'port' => 1234, // not generally required the port to connect to (if applicable)
                                   // 'charset' => 'utf8', // not generally required the character set to use
        'options' => array(
            'buffer_results' => 1
        )
    ),
    'doctrine' => array(
        'connection' => array(
            /*'orm_zfcDatagrid' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOSqlite\Driver',
                'params' => array(
                    'charset' => 'utf8',
                    'path' => __DIR__ . '/../src/Zf2datatable/Examples/Data/examples.sqlite'
                )
            )*/
            'orm_zfcDatagrid' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'user'     => 'root',
                    'password' => 'poi890',
                    'dbname'   => 'derev',
					'charset' => 'utf8', // extra
					'driverOptions' => array(
							1002=>'SET NAMES utf8'
					)
                )
            )

        ),
        'configuration' => array(
                'orm_zfcDatagrid' => array(
                    'metadata_cache' => 'array',
                        'query_cache' => 'array',
                        'result_cache' => 'array',
                        'driver' => 'orm_zfcDatagrid',
                        'generate_proxies' => true,
                        'proxy_dir' => 'data/Zf2datatable/Proxy',
                        'proxy_namespace' => 'Zf2datatable\Proxy',
                        'filters' => array()
            )
        ),
        'driver' => array(
            'ZfcDatagrid_Driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../../../FileUpload/src/FileUpload/Entity',
                    __DIR__ . '/../../../Revolution/src/Revolution/Entity',
                    __DIR__ . '/../../../User/src/User/Entity',
                    __DIR__ . '/../../../Application/src/Application/Entity',

                )
            ),

            'orm_zfcDatagrid' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\DriverChain',
                'drivers' => array(
                    'Application\Entity'=> 'ZfcDatagrid_Driver',
                    'Revolution\Entity' => 'ZfcDatagrid_Driver',
                    'User\Entity'       => 'ZfcDatagrid_Driver',
                    'FileUpload\Entity' => 'ZfcDatagrid_Driver',
                )
            )
        ),

        // now you define the entity manager configuration
        'entitymanager' => array(
            // This is the alternative config
            'orm_zfcDatagrid' => array(
                    'connection' => 'orm_zfcDatagrid',
                    'configuration' => 'orm_zfcDatagrid'
            )
        ),
        'eventmanager' => array(
            'orm_zfcDatagrid' => array(
                    'subscribers' => array(
                        'Gedmo\Tree\TreeListener',
                        'Gedmo\Timestampable\TimestampableListener',
                        'Gedmo\Sluggable\SluggableListener',
                        'Gedmo\Loggable\LoggableListener',
                        'Gedmo\Sortable\SortableListener',
                        'Gedmo\IpTraceable\IpTraceableListener'
                    ),
            )
        ),

        'sql_logger_collector' => array(
            'orm_zfcDatagrid' => array()
        ),

        'entity_resolver' => array(
            'orm_zfcDatagrid' => array()
        )
    )
);
