#!/usr/bin/env php
<?php

if ($argc < 2) {
    die(usage());
}

$filename = $argv[1];
if (!is_file($filename)) {
    die($filename . ' is not file');
} elseif (!is_readable($filename)) {
    die($filename . ' is not readable');
}

$xml = new SimpleXMLElement(file_get_contents($filename));

echo <<<HEAD
<!DOCTYPE html>
<html><head><meta charset="utf-8"></head>
HEAD;

foreach ($xml as $element) {
    if (!$element->title) {
        continue;
    }

    echo tag('h2', $element->title);

    if ($element->tag) {
        $tagList = [];
        foreach ($element->tag as $tagElement) {
            $tagList[] = $tagElement;
        }
        echo tag('i', implode(' • ', $tagList));
    }

    if ($element->content) {
        echo tag('p', $element->content);
    }

    echo shortTag('br') . shortTag('br') . "\n";
}

echo <<<FOOTER
</html>
FOOTER;


/**
 * Show usage information
 */
function usage()
{
    echo 'Usage: ' . __FILE__ . ' FILE' . "\n";
}

/**
 * Return HTML-tag
 * @param string $name
 * @param string $content
 * @param array $attributes Key-value array of attributes
 * @return string
 */
function tag($name, $content, array $attributes = [])
{
    $attributesPlain = [];
    foreach ($attributes as $key => $value) {
        $attributesPlain[] = sprintf(
            '%s="%s"',
            $key,
            $value
        );
    }

    return sprintf(
        '<%s %s>%s</%s>',
        $name,
        implode(' ', $attributesPlain),
        $content,
        $name
    );
}

/**
 * Return short HTML-tag
 * @param string $name
 * @param array $attributes Key-value array of attributes
 * @return string
 */
function shortTag($name, array $attributes = [])
{
    $attributesPlain = [];
    foreach ($attributes as $key => $value) {
        $attributesPlain[] = sprintf(
            '%s="%s"',
            $key,
            $value
        );
    }

    return sprintf(
        '<%s %s/>',
        $name,
        $attributesPlain
    );
}