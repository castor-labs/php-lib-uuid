<?php

use PhpCsFixer\Fixer\FunctionNotation\NativeFunctionInvocationFixer;

$header = <<<EOF
@project Castor UUID
@link https://github.com/castor-labs/php-lib-uuid
@package castor/uuid
@author Matias Navarro-Carter mnavarrocarter@gmail.com
@license MIT
@copyright 2024 CastorLabs Ltd

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF;

return (new PhpCsFixer\Config())
    ->setCacheFile('/tmp/php-cs-fixer')
    ->setRiskyAllowed(true)
    ->setRules([
        '@PhpCsFixer' => true,
        'declare_strict_types' => true,
        'header_comment' => ['header' => $header, 'comment_type' => 'PHPDoc'],
        'yoda_style' => false,
        'php_unit_internal_class' => false,
        'php_unit_test_class_requires_covers' => false,
        'native_function_invocation' => [
            'include' => [NativeFunctionInvocationFixer::SET_ALL],
            'scope' => 'namespaced',
        ]
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(["src", "tests", "include"])
    )
;
