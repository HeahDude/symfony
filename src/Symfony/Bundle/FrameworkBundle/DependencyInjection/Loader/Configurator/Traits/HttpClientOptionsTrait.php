<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\DependencyInjection\Loader\Configurator\Traits;

trait HttpClientOptionsTrait
{
    protected $maxHostConnections;
    protected $options = [];

    final public function maxHostConnections(int $max)
    {
        $this->maxHostConnections = $max;

        return $this;
    }

    final public function authBasic(string $auth)
    {
        $this->options['auth_basic'] = $auth;

        return $this;
    }

    final public function authBearer(string $token)
    {
        $this->options['auth_bearer'] = $token;

        return $this;
    }

    final public function query(array $query)
    {
        $this->options['query'] = $query;

        return $this;
    }

    final public function headers(array $headers)
    {
        $this->options['headers'] = $headers;

        return $this;
    }

    final public function maxRedirects(int $max)
    {
        $this->options['max_redirects'] = $max;

        return $this;
    }

    final public function httpVersion(string $version)
    {
        $this->options['http_version'] = $version;

        return $this;
    }

    final public function baseUri(string $uri)
    {
        $this->options['base_uri'] = $uri;

        return $this;
    }

    final public function resolve(array $domainsToIps)
    {
        $this->options['resolve'] = $domainsToIps;

        return $this;
    }

    final public function proxy(string $url)
    {
        $this->options['proxy'] = $url;

        return $this;
    }

    final public function noProxy(string $urls)
    {
        $this->options['no_proxy'] = $urls;

        return $this;
    }

    final public function timeout(float $time)
    {
        $this->options['timeout'] = $time;

        return $this;
    }

    final public function bindto(string $to)
    {
        $this->options['bindto'] = $to;

        return $this;
    }

    final public function verifyPeer(bool $verify = true)
    {
        $this->options['verify_peer'] = $verify;

        return $this;
    }

    final public function verifyHost(bool $verify = true)
    {
        $this->options['verify_host'] = $verify;

        return $this;
    }

    final public function cafile(string $file)
    {
        $this->options['cafile'] = $file;

        return $this;
    }

    final public function capath(string $path)
    {
        $this->options['capath'] = $path;

        return $this;
    }

    final public function localCert(string $name)
    {
        $this->options['local_cert'] = $name;

        return $this;
    }

    final public function localPk(string $name)
    {
        $this->options['local_pk'] = $name;

        return $this;
    }

    final public function passphrase(string $phrase)
    {
        $this->options['passphrase'] = $phrase;

        return $this;
    }

    final public function ciphers(string $ciphers)
    {
        $this->options['ciphers'] = $ciphers;

        return $this;
    }

    final public function peerFingerprint(array $hashes)
    {
        $this->options['peer_fingerprint'] = $hashes;

        return $this;
    }
}
