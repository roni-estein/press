<?php


use RoniEstein\Press\MarkdownParser;

test('simple markdown is parsed')
    ->expect(MarkdownParser::parse('# Heading'))
    ->toEqual('<h1>Heading</h1>');