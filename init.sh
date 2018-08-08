sed -i 's/{{library}}/'$(pwd | xargs basename)'/' README.md
sed -i 's/{{library}}/'$(pwd | xargs basename)'/' composer.json

mkdir -p src tests
composer install

cp vendor/jasny/php-code-quality/.gitignore .
cp vendor/jasny/php-code-quality/phpunit.xml.dist .
cp vendor/jasny/php-code-quality/phpcs.xml.dist ./phpcs.xml
cp vendor/jasny/php-code-quality/phpstan.neon.dist ./phpstan.neon
cp vendor/jasny/php-code-quality/travis.yml.dist ./.travis.yml
cp vendor/jasny/php-code-quality/bettercodehub.yml.dist ./.bettercodehub.yml

travis sync
travis enable
xdg-open https://scrutinizer-ci.com/g/new
xdg-open https://insight.sensiolabs.com/projects/new/github
xdg-open https://bettercodehub.com/repositories

xdg-open README.md
