<?php

namespace Yoda;

/**
 * @author Salomon Dion <dev.mrdion@gmail.com>
 */
class TemplateParser
{
    public static $START_DELIMITER = '{{';
    public static $END_DELIMITER = '}}';

    /**
     * Replace shortcodes by their corresponding value from the template variables.
     *
     * @param Template $template
     * @param null|string $startDelimiter
     * @param null|string $endDelimiter
     * @return null|string|string[]
     */
    public static function parse(Template $template, ?string $startDelimiter = null, ?string $endDelimiter = null)
    {
        $startDelimiter = $startDelimiter ?? self::$START_DELIMITER;
        $endDelimiter = $endDelimiter ?? self::$END_DELIMITER;
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
