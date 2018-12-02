<?php

namespace Yoda;

use Yoda\Parsers\ParserInterface;
use Yoda\Parsers\SimpleParser;

/**
 * @author Salomon Dion <dev.mrdion@gmail.com>
 */
class TemplateParser
{
    public static $START_DELIMITER = '{{';
    public static $END_DELIMITER = '}}';
    /** @var ParserInterface */
    public static $parser;

    /**
     * Replace shortcodes by their corresponding value from the template variables.
     *
     * @param Template $template
     * @param string|null $startDelimiter
     * @param string|null $endDelimiter
     * @return null|string|string[]
     */
    public static function parse(Template $template, ?string $startDelimiter = null, ?string $endDelimiter = null)
    {
        $startDelimiter = $startDelimiter ?? self::$START_DELIMITER;
        $endDelimiter = $endDelimiter ?? self::$END_DELIMITER;

        if (self::$parser === null) {
            return SimpleParser::parse($template, $startDelimiter, $endDelimiter);
        }

        return self::$parser::parse($template, $startDelimiter, $endDelimiter);
    }

    /**
     * Set the parser to use.
     *
     * @param ParserInterface $parser
     */
    public static function setParser(ParserInterface $parser): void
    {
        self::$parser = $parser;
    }
}
