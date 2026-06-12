<?php
declare(strict_types=1);

final class MapiClient
{
    private string $baseUrl;
    private string $domainKey;
    private string $domain;
    private int $timeout;

    public function __construct(array $config)
    {
        $this->baseUrl = rtrim((string) ($config['mapi_base_url'] ?? 'https://www.matkaapi.com'), '/');
        $this->domainKey = trim((string) ($config['domain_key'] ?? ''));
        $this->domain = trim((string) ($config['domain'] ?? ''));
        $this->timeout = (int) ($config['timeout'] ?? 30);
        if ($this->timeout < 5) {
            $this->timeout = 30;
        }
    }

    public function getDomainKey(): string
    {
        return $this->domainKey;
    }

    public function resolveDomain(): string
    {
        if ($this->domain !== '') {
            return $this->normalizeDomain($this->domain);
        }
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $host = preg_replace('/:\d+$/', '', (string) $host);
        return $this->normalizeDomain($host);
    }

    /**
     * @param array<string, scalar|null> $params
     */
    public function get(string $endpoint, array $params = []): array
    {
        if ($this->domainKey === '') {
            return [
                'status' => false,
                'message' => 'domain_key is empty — edit config.php',
            ];
        }

        $params['domain_key'] = $this->domainKey;
        $domain = $this->resolveDomain();
        if ($domain !== '' && !isset($params['domain'])) {
            $params['domain'] = $domain;
        }

        $endpoint = ltrim($endpoint, '/');
        $url = $this->baseUrl . '/mapi/' . $endpoint;
        $url .= '?' . http_build_query($params);

        return $this->request($url);
    }

    /**
     * @param array<string, scalar|null> $params
     */
    public function post(string $endpoint, array $params = []): array
    {
        if ($this->domainKey === '') {
            return [
                'status' => false,
                'message' => 'domain_key is empty — edit config.php',
            ];
        }

        $params['domain_key'] = $this->domainKey;
        $domain = $this->resolveDomain();
        if ($domain !== '' && !isset($params['domain'])) {
            $params['domain'] = $domain;
        }

        $endpoint = ltrim($endpoint, '/');
        $url = $this->baseUrl . '/mapi/' . $endpoint;

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_USERAGENT => 'MatkaAPI-Client/2.0',
            CURLOPT_HTTPHEADER => ['X-Matka-Source: server'],
        ]);

        $body = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        if ($body === false) {
            return ['status' => false, 'message' => 'Request failed: ' . $err];
        }

        if ($code < 200 || $code >= 300) {
            return [
                'status' => false,
                'message' => 'HTTP ' . $code,
                'raw' => substr((string) $body, 0, 500),
            ];
        }

        $decoded = json_decode((string) $body, true);
        if (!is_array($decoded)) {
            return ['status' => false, 'message' => 'Invalid JSON from MAPI'];
        }

        return $decoded;
    }

    private function request(string $url): array
    {
        if (!function_exists('curl_init')) {
            return ['status' => false, 'message' => 'PHP curl extension is required'];
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_USERAGENT => 'MatkaAPI-Client/2.0',
            CURLOPT_HTTPHEADER => ['X-Matka-Source: server'],
        ]);

        $body = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        if ($body === false) {
            return ['status' => false, 'message' => 'Request failed: ' . $err, 'url' => $url];
        }

        if ($code < 200 || $code >= 300) {
            return [
                'status' => false,
                'message' => 'HTTP ' . $code,
                'url' => $url,
                'raw' => substr((string) $body, 0, 500),
            ];
        }

        $decoded = json_decode((string) $body, true);
        if (!is_array($decoded)) {
            return ['status' => false, 'message' => 'Invalid JSON from MAPI', 'url' => $url];
        }

        return $decoded;
    }

    private function normalizeDomain(string $domain): string
    {
        $domain = strtolower(trim($domain));
        $domain = preg_replace('#^https?://#i', '', $domain);
        $domain = preg_replace('#^www\.#i', '', $domain);
        return rtrim($domain, '/');
    }
}
