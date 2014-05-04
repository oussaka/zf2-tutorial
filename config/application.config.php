<?php
return array(
    'modules' => array(
        'Application',
        'Album',
//     	'AlbumRest',
        'Cms',
        'CacheResultSet',
//         'ZfrForum',
        // 'DoctrineModule',
        // 'DoctrineORMModule',
        'ZendDeveloperTools',
        'BjyProfiler',
        'RtExtends',
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
        'module_paths' => array(
            './module',
            './vendor',
        ),
        // Whether or not to enable a configuration cache.
        // If enabled, the merged configuration will be cached and used in
        // subsequent requests.
//         'config_cache_enabled' => true,
        // The key used to create the configuration cache file name.
//         'config_cache_key' => "2245023265ae4cf87d02c8b6ba991139",

        // Whether or not to enable a module class map cache.
        // If enabled, creates a module class map cache which will be used
        // by in future requests, to reduce the autoloading process.
//         'module_map_cache_enabled' => true,
        // The key used to create the class map cache file name.
//         'module_map_cache_key' => "496fe9daf9baed5ab03314f04518b928",


        // The path in which to cache merged configuration.
//         'cache_dir' => "./data/cache/modulecache",
    ),
);
