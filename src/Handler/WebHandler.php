<?php declare(strict_types=1);

namespace Error\Handler;

use Throwable;

class WebHandler implements HandlerInterface
{
    use ExceptionMessageTrait, ExceptionStackTrait;

    protected $debug;

    protected $resources;

    public function __construct(bool $debug = false)
    {
        $this->debug = $debug;
        $this->resources = \dirname(__DIR__) . '/Resources';
    }

    protected function render(Throwable $e): string
    {
        \ob_start();

        $stack = $this->getStack($e);

        require $this->resources.'/'.($this->debug ? 'debug' : 'message').'.html';

        return \ob_get_clean() ?: '';
    }

    public function handle(Throwable $e): void
    {
        \header('Content-Type: text/html', true, 500);
        echo $this->render($e);
    }
}
