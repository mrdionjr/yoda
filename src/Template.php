<?php

namespace Yoda;


class Template
{
    /**
     * Plain string to parse.
     *
     * @var string
     */
    private $content;

    /**
     * Contains template variables.
     *
     * @var array
     */
    private $variables;

    /**
     * Template name.
     *
     * @var null|string
     */
    private $tplName;

    /**
     * Template constructor.
     *
     * @param string $content
     * @param array $variables
     * @param string|null $tplName
     */
    public function __construct(string $content, array $variables = [], ?string $tplName = null)
    {
        $this->content = $content;
        $this->variables = $variables;
        $this->tplName = $tplName;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return file_exists($this->content) ? file_get_contents($this->content) : $this->content;
    }

    /**
     * @return array
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->tplName;
    }

    public function __toString()
    {
        return $this->getName() ?? '';
    }

    public function __set($name, $value)
    {
        $this->variables[$name] = $value;
    }

    public function __get($name)
    {
        return $this->variables[$name];
    }

    public function __isset($name)
    {
        return array_key_exists($name, $this->variables);
    }
}
