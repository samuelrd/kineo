<?php
namespace Samuelrd\Kineo;

final class Url
{
    private string $scheme;
    private string $host;
    private string $path;
    private string $query;
    private string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
        $parts = parse_url($url);
        $this->scheme = $parts["scheme"] ?? "";
        $this->host = $parts["host"] ?? "";
        $this->path = $parts["path"] ?? "";
        $this->query = $parts["query"] ?? "";
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function toHttp(): string
    {
        $this->scheme = "http";
        $this->generateUrl();

        return $this->url;
    }

    public function toHttps(): string
    {
        $this->scheme = "https";
        $this->generateUrl();
        
        return $this->url;
    }

    public function addQueryParameter($key, $value): string
    {
        $parameters = $this->getQueryParameters();
        $parameters[$key] = $value;
        $this->generateUrl($parameters);

        return $this->url;
    }    
    
    public function removeQueryParameter($key): string
    {
        $parameters = $this->getQueryParameters();
        unset($parameters[$key]);
        $this->generateUrl($parameters);

        return $this->url;
    }

    private function getQueryParameters(): array
    {
        $queryParameters = [];
        foreach (explode("&", $this->query) as $parameter)
        {
            $keyValuePair = explode("=", $parameter);
            $queryParameters[$keyValuePair[0]] = $keyValuePair[1]; 
        }

        return $queryParameters;
    }

    private function generateUrl($queryParameters = null): void
    {
        $queryParameters =  $queryParameters ?? $this->getQueryParameters();
        $this->query = http_build_query($queryParameters);
        $this->url = "{$this->scheme}://{$this->host}{$this->path}?{$this->query}";
    }

    public function equals(Url $url, $strict = false)
    {
        $equals = $this->scheme == $url->getScheme() && $this->host == $url->getHost() && $this->path == $url->getPath();

        if ($strict)
            return $equals && $this->query == $url->getQuery();

        return $equals;
    }
}