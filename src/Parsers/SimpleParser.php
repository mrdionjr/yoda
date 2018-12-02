<?php

namespace Yoda\Parsers;


use Yoda\MissingVariableException;
use Yoda\Template;

class SimpleParser implements ParserInterface
{
    /**
     * Replace shortcodes by their corresponding value from the template variables.
     *
     * @param Template $template
     * @param string|null $startDelimiter
     * @param string|null $endDelimiter
     * @return mixed
     */
    public static function parse(Template $template, ?string $startDelimiter = null, ?string $endDelimiter = null)
    {
        $pattern = '/' . $startDelimiter . '\W*(.*?)\W*' . $endDelimiter . '/';
        $parsed = preg_replace_callback($pattern, function ($matches) use ($template) {
            [$shortcode, $key] = $matches;
            $variables = $template->getVariables();

            if (isset($variables[$key]) ) {
                return $variables[$key];
            }

            throw new MissingVariableException("Missing variable '{$key}' for shortcode '{$shortcode}'.", 1);

        }, $template->getContent());

        return $parsed;
    }
}