<?php

namespace Yoda\Parsers;


use Yoda\Template;

interface ParserInterface
{
    /**
     * Replace shortcodes by their corresponding value from the template variables.
     *
     * @param Template $template
     * @param array $options
     * @return mixed
     */
    public static function parse(Template $template, array $options = []);
}