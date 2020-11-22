error_exit() {
    echo ""
    echo "TESTS FAILED"
    exit 1
}

sucess_exit() {
    echo ""
    echo "TESTS PASSED"
    exit 0
}

show_next() {
    echo ""
    echo "====================================================================="
    echo "-- [ $1 ]"
    echo "====================================================================="
    echo ""
}

trap error_exit 0 

show_next "clean up.."
echo "" > route.cache.php

show_next "php-cs-fixer"
vendor/bin/php-cs-fixer fix lib
vendor/bin/php-cs-fixer fix src
vendor/bin/php-cs-fixer fix tests

show_next "csfix.php"
php tools/csfix.php lib
php tools/csfix.php src
php tools/csfix.php tests

show_next "phpcbf"
vendor/bin/phpcbf lib
vendor/bin/phpcbf src
vendor/bin/phpcbf tests

set -e

show_next "phpcs"
vendor/bin/phpcs lib
vendor/bin/phpcs src
vendor/bin/phpcs tests

show_next "phpstan"
vendor/bin/phpstan analyse --level 8 lib
vendor/bin/phpstan analyse --level 8 src
vendor/bin/phpstan analyse --level 8 tests

show_next "phpmd"
vendor/bin/phpmd lib text cleancode,codesize,controversial,design,naming,unusedcode
vendor/bin/phpmd src text cleancode,codesize,controversial,design,naming,unusedcode
vendor/bin/phpmd tests text cleancode,codesize,controversial,design,naming,unusedcode

show_next "phan"
vendor/bin/phan

show_next "test.php"
php test.php

trap sucess_exit 0