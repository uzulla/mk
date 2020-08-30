<?php

namespace Uz\Slim;

use Slim\Exception\InvalidMethodException;

class App extends \Slim\App
{
    /**
     * Run application
     *
     * This method traverses the application middleware stack and then sends the
     * resultant Response object to the HTTP client.
     *
     * @param bool|false $silent
     *
     * @return ResponseInterface
     *
     * @throws Exception
     * @throws Throwable
     */
    public function run($silent = false)
    {
        $response = $this->container->get('response');

        try {
            ob_start();
            logPsr7Req($this->container->get('request')); // TODO uzulla MODIFIED
            $response = $this->process($this->container->get('request'), $response);
        } catch (InvalidMethodException $e) {
            $response = $this->processInvalidMethod($e->getRequest(), $response);
        } finally {
            $output = ob_get_clean();
        }

        if (!empty($output) && $response->getBody()->isWritable()) {
            $outputBuffering = $this->container->get('settings')['outputBuffering'];
            if ($outputBuffering === 'prepend') {
                // prepend output buffer content
                $body = new \Slim\Http\Body(fopen('php://temp', 'r+'));
                $body->write($output . $response->getBody());
                $response = $response->withBody($body);
            } elseif ($outputBuffering === 'append') {
                // append output buffer content
                $response->getBody()->write($output);
            }
        }

        $response = $this->finalize($response); // TODO uzulla MODIFIED
        logPsr7Res($response);

        if (!$silent) {
            $this->respond($response);
        }

        return $response;
    }
}
