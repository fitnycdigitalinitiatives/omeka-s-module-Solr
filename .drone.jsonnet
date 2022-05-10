local Pipeline(omekaVersion, phpVersion, dbImage) = {
    kind: 'pipeline',
    type: 'docker',
    name: 'omeka:' + omekaVersion + ' php:' + phpVersion + ' ' + dbImage,
    workspace: {
        path: 'omeka-s/modules/Solr',
    },
    steps: [
        {
            name: 'test',
            image: 'biblibre/omeka-s-ci:' + omekaVersion + '-php' + phpVersion,
            pull: 'always',
            commands: [
                'cp -rT /usr/src/omeka-s ../..',
                'git clone --depth 1 https://github.com/biblibre/omeka-s-module-Search.git ../Search',
                "echo 'host = \"db\"\\nuser = \"root\"\\npassword = \"root\"\\ndbname = \"omeka_test\"\\n' > ../../application/test/config/database.ini",
                'php ../../build/composer.phar install',
                'bash -c "cd ../.. && php /usr/local/libexec/wait-for-db.php"',
                '../../vendor/bin/phpunit',
                '../../node_modules/.bin/gulp test:module:cs',
            ],
        },
    ],
    services: [
        {
            name: 'db',
            image: dbImage,
            environment: {
                MYSQL_ROOT_PASSWORD: 'root',
                MYSQL_DATABASE: 'omeka_test',
            },
        },
    ],
};

[
    Pipeline('3.0.2', '7.4', 'mysql:5.7'),
    Pipeline('3.0.2', '7.4', 'mysql:8.0'),
    Pipeline('3.0.2', '7.4', 'mariadb:10.3'),
    Pipeline('3.0.2', '7.4', 'mariadb:10.4'),
    Pipeline('3.0.2', '7.4', 'mariadb:10.5'),
    Pipeline('3.0.2', '7.4', 'mariadb:10.6'),
    Pipeline('3.0.2', '7.4', 'mariadb:10.7'),

    Pipeline('3.1.2', '7.4', 'mysql:5.7'),
    Pipeline('3.1.2', '7.4', 'mysql:8.0'),
    Pipeline('3.1.2', '7.4', 'mariadb:10.3'),
    Pipeline('3.1.2', '7.4', 'mariadb:10.4'),
    Pipeline('3.1.2', '7.4', 'mariadb:10.5'),
    Pipeline('3.1.2', '7.4', 'mariadb:10.6'),
    Pipeline('3.1.2', '7.4', 'mariadb:10.7'),
    Pipeline('3.1.2', '8.0', 'mysql:5.7'),
    Pipeline('3.1.2', '8.0', 'mysql:8.0'),
    Pipeline('3.1.2', '8.0', 'mariadb:10.3'),
    Pipeline('3.1.2', '8.0', 'mariadb:10.4'),
    Pipeline('3.1.2', '8.0', 'mariadb:10.5'),
    Pipeline('3.1.2', '8.0', 'mariadb:10.6'),
    Pipeline('3.1.2', '8.0', 'mariadb:10.7'),

    Pipeline('3.2.0', '7.4', 'mysql:5.7'),
    Pipeline('3.2.0', '7.4', 'mysql:8.0'),
    Pipeline('3.2.0', '7.4', 'mariadb:10.3'),
    Pipeline('3.2.0', '7.4', 'mariadb:10.4'),
    Pipeline('3.2.0', '7.4', 'mariadb:10.5'),
    Pipeline('3.2.0', '7.4', 'mariadb:10.6'),
    Pipeline('3.2.0', '7.4', 'mariadb:10.7'),
    Pipeline('3.2.0', '8.0', 'mysql:5.7'),
    Pipeline('3.2.0', '8.0', 'mysql:8.0'),
    Pipeline('3.2.0', '8.0', 'mariadb:10.3'),
    Pipeline('3.2.0', '8.0', 'mariadb:10.4'),
    Pipeline('3.2.0', '8.0', 'mariadb:10.5'),
    Pipeline('3.2.0', '8.0', 'mariadb:10.6'),
    Pipeline('3.2.0', '8.0', 'mariadb:10.7'),
]
