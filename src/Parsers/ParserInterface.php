<?php

namespace Yoda\Parsers;


use Yoda\Template;

interface ParserInterface
{
    /**
     * Replace shortcodes by their corresponding value from the template variables.
     *
     * @param Template $template
     * @param string|null $startDelimiter
     * @param string|null $endDelimiter
     * @return mixed
     */
    public static function parse(Template $template, ?string $startDelimiter = null, ?string $endDelimiter = null);
}