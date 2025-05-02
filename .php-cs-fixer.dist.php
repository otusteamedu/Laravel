<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude(['resources', 'storage', 'packages'])
    ->ignoreVCSIgnored(true);

$config = (new PhpCsFixer\Config())
    ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect());

return $config->setRules([
    '@PER-CS2.0' => true,
    'array_syntax' => true,
    'concat_space' => ['spacing' => 'one'],
    'function_declaration' => ['closure_fn_spacing' => 'one'],
    'new_with_parentheses' => ['anonymous_class' => false],
    'single_line_empty_body' => false,
    'trailing_comma_in_multiline' => ['after_heredoc' => true, 'elements' => ['arrays', 'match']],
])->setFinder($finder);
