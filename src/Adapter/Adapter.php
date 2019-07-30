<?php

namespace Dotmailer\Adapter;

use Psr\Http\Message\ResponseInterface;

interface Adapter
{
    /**
     * @param string $url
     * @param array $params
     *
     * @return ResponseInterface
     */
    public function get(string $url, array $params = []): ResponseInterface;

    /**
     * @param string $url
     * @param array $content
     *
     * @return ResponseInterface
     */
    public function post(string $url, array $content = []): ResponseInterface;

    /**
     * @param string $url
     * @param array $content
     *
     * @return ResponseInterface
     */
    public function put(string $url, array $content = []): ResponseInterface;

    /**
     * @param string $url
     *
     * @return ResponseInterface
     */
    public function delete(string $url): ResponseInterface;
    
    /**
     * @param string $url
     * @param string $filename
     *
     * @return ResponseInterface
     */
    public function postfile(string $url, string $filePath, string $fileName, string $mimeType): ResponseInterface;
}
