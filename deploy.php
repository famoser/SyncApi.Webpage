<?php
namespace Deployer;
require 'vendor/deployer/deployer/recipe/common.php';

// Configuration
set('repository', 'https://github.com/famoser/SyncApi.Webpage.git');
set('shared_dirs', ["app/logs","app/data"]);
set('writable_dirs', ['app/logs', 'app/cache', 'app/data']);
set('clear_paths', ["app/cache"]);
set('http_user', 'floria74');

//I need this config for my hoster
set(
    'composer_options',
    '{{composer_action}} --verbose --prefer-dist --no-progress --no-interaction --no-dev --optimize-autoloader --ignore-platform-reqs'
);

task('deploy:prod', function () {
    run('cd {{release_path}} && cd src/public && echo NUL > .prod');
});


// kill php processes to ensure symlinks are refreshed
task('deploy:refresh_symlink', function () {
    run("killall -9 php-cgi"); //kill all php processes so symlink is refreshed
})->desc('Refreshing symlink');



// import servers
serverList('servers.yml');

desc('Deploy your project');
task('deploy', [
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'deploy:vendors',
    'deploy:prod',
    'deploy:clear_paths',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success'
]);

//stages: dev, testing, production
set('default_stage', 'dev');

after('deploy', 'success');

after('deploy:symlink', 'deploy:refresh_symlink');