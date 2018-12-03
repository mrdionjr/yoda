<?php

namespace Yoda;

use Yoda\Parsers\ParserInterface;
use Yoda\Parsers\SimpleParser;

/**
 * @author Salomon Dion <dev.mrdion@gmail.com>
 */
class TemplateParser
{
    /** @var ParserInterface */
    public static $parser;

    /**
     * Replace shortcodes by their corresponding value from the template variables.
     *
     * @param Template $template
     * @param array $options
     * @return null|string|string[]
     */
    public static function parse(Template $template, array $options = [])
    {
        ['start' => $start, 'end' => $end] = $template->getDelimiters();
        $options['pattern'] = '/' . $start . '\W*(.*?)\W*' . $end . '/';

        if (self::$parser === null) {
            return SimpleParser::parse($template, $options);
        }

        return self::$parser::parse($template, $options);
    }

    /**
     * Set the parser to use.
     *
     * @param ParserInterface $parser
     */
    public static function use(ParserInterface $parser): void
    {
        self::$parser = $parser;
    }
}
