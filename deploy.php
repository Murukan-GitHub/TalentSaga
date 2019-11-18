<?php
/*
 * This file has been generated automatically.
 * Please change the configuration for correct use deploy.
 */

require 'recipe/laravel.php';

define('REPO', 'git@gitlab.com:suitmedia/talent-saga.git');

$servers = [

    'staging' => [
        'server' => '202.67.10.2',
        'user' => 'suitcore',
        'path' => '/home/suitcore/talent-saga-dev',
    ],

    'productionstaging' => [
        'server' => '202.67.10.2',
        'user' => 'suitcore',
        'path' => '/home/suitcore/talent-saga-dev',
    ],

    'production' => [
        'server' => '202.67.10.2',
        'user' => 'suitcore',
        'path' => '/home/suitcore/talent-saga-dev',
    ],

];

// Set configurations
env('composer_options', 'install --verbose --prefer-dist --optimize-autoloader --no-progress --no-interaction');

// If we don't need sudo, keep it false
set('writable_use_sudo', false);

set('repository', REPO);
set('shared_files', ['.env']);
set('shared_dirs', [
    'storage/app',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    
    // app shared
    'public/files',
    'public/uploads',
    'public/rawuploads',
    'public/.well-known',
]);
set('writable_dirs', ['bootstrap/cache', 'storage']);

// Configure servers
foreach ($servers as $name => $server) {
    
    server($name, $server['server'])
        ->user($server['user'])
        ->password()
        ->env('deploy_path', $server['path']);
}

/**
 * Restart php-fpm on success deploy.
 */

task('artisan:clear-compiled', function () {
    $output = run('{{bin/php}} {{deploy_path}}/current/artisan clear-compiled');
    writeln('<info>'.$output.'</info>');
})->desc('Clear compiled done');

task('artisan:optimize', function () {
    $output = run('{{bin/php}} {{deploy_path}}/current/artisan optimize');
    writeln('<info>'.$output.'</info>');
})->desc('Optimize done');

task('artisan:elfinder:publish', function () {
    $output = run('{{bin/php}} {{deploy_path}}/current/artisan elfinder:publish');
    writeln('<info>'.$output.'</info>');
})->desc('Publishing Elfinder');

task('artisan:cache:clear', function () {
    $output = run('{{bin/php}} {{deploy_path}}/current/artisan cache:clear');
    writeln('<info>'.$output.'</info>');
})->desc('Clear cache');

task('artisan:view:clear', function () {
    $output = run('{{bin/php}} {{deploy_path}}/current/artisan view:clear');
    writeln('<info>'.$output.'</info>');
})->desc('View cache');

task('php-fpm:restart', function () {
    $output = run('sudo /usr/sbin/service php5.6-fpm restart');
    writeln('<info>'.$output.'</info>');
})->desc('Restart PHP-FPM');

task('git:pull', function () {
    $output = run('cd {{deploy_path}}/current/ && git pull origin master');
    writeln('<info>'.$output.'</info>');
})->desc('Clear compiled done');

task('after-deploy', [
    'artisan:clear-compiled',
    'artisan:optimize',
    // 'artisan:elfinder:publish',
    // 'artisan:cache:clear',
    // 'artisan:view:clear',
    // 'php-fpm:restart',
])->desc('Deploying done.');

after('success', 'after-deploy');
