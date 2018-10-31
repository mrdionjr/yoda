<?php

namespace Yoda;


class Template
{
    /**
     * @var string|string
     */
    private $content;

    /**
     * @var array
     */
    private $variables;

    /**
     * @var array
     */
    private $opts;

    /**
     * @var null|string
     */
    private $tplName;

    /**
     * Template constructor.
     *
     * @param string $content
     * @param array $variables
     * @param string|null $tplName
     * @param array $opts
     * @throws \Exception
     */
    public function __construct(
        string $content,
        array $variables = [],
        ?string $tplName = null,
        array $opts = ['isPath' => true]
    )
    {
        if ($opts['isPath'] && !file_exists($content)) {
            throw new \Exception('Template does not exists!');
        }

        $this->content = $content;
        $this->variables = $variables;
        $this->opts = $opts;
        $this->tplName = $tplName;
    }

    public function getContent(): string
    {
        if ($this->opts['isPath']) {
            return file_get_contents($this->content);
        }
        return $this->content;
    }

    /**
     * @return mixed
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
}
