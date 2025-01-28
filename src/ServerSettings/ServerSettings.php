<?php

namespace Simp\Mail\ServerSettings;

use Simp\Environment\Environment;

class ServerSettings
{
    private ?string $host;

    private ?string $port;

    private ?string $username;

    private ?string $password;

    public function __construct(string|null $host = null, string|null $port = null, string|null $username = null, string|null $password = null, string|null $environment = null)
    {
        if ($environment !== null) {
            $config = Environment::load($environment);
            $this->host = $config['host'] ?? $host || null;
            $this->port = $config['port'] ?? $port || null;
            $this->username = $config['username'] ?? $username || null;
            $this->password = $config['password'] ?? $password || null;
        }
        else {
            $this->host = $host;
            $this->port = $port;
            $this->username = $username;
            $this->password = $password;
        }
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function getPort(): ?string
    {
        return $this->port;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public static function createFromEnvironment(string $key): ServerSettings
    {
        return new self(environment: $key);
    }

    /**
     * @param array $data host,port,username,password
     * @return ServerSettings
     */
    public static function createFromArray(array $data): ServerSettings
    {
        return new self(...$data);
    }
}