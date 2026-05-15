<?php
/**
 * jwt.php — Pure PHP JWT implementation (no Composer required)
 *
 * VULNERABILITY: Algorithm Confusion (RS256 → HS256)
 * The algorithm is read from the *unverified* token header.
 * When alg=HS256, the PUBLIC key is used as the HMAC secret.
 * An attacker who fetches /api/public-key.php can forge any payload.
 */

define('PRIVATE_KEY', file_get_contents('/app/keys/private.pem'));
define('PUBLIC_KEY',  file_get_contents('/app/keys/public.pem'));

function jwt_base64url_encode(string $data): string {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function jwt_base64url_decode(string $data): string {
    return base64_decode(strtr($data, '-_', '+/'));
}

function make_token(array $payload): string {
    $header  = jwt_base64url_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
    $payload = jwt_base64url_encode(json_encode($payload));
    $msg     = "$header.$payload";

    $pkey = openssl_pkey_get_private(PRIVATE_KEY);
    openssl_sign($msg, $sig, $pkey, OPENSSL_ALGO_SHA256);

    return "$msg." . jwt_base64url_encode($sig);
}

function verify_token(string $token): ?object {
    $parts = explode('.', $token);
    if (count($parts) !== 3) return null;

    [$b64header, $b64payload, $b64sig] = $parts;

    $header = json_decode(jwt_base64url_decode($b64header), true);
    if (!$header) return null;

    $alg = $header['alg'] ?? 'RS256';
    $msg = "$b64header.$b64payload";
    $sig = jwt_base64url_decode($b64sig);

    if ($alg === 'RS256') {
        // Normal RS256 verification
        $pkey = openssl_pkey_get_public(PUBLIC_KEY);
        $ok   = openssl_verify($msg, $sig, $pkey, OPENSSL_ALGO_SHA256);
        if ($ok !== 1) return null;

    } elseif ($alg === 'HS256') {
        // ⚠ VULNERABLE: public key used as HMAC secret
        // An attacker who has the public key can forge tokens with HS256
        $expected = hash_hmac('sha256', $msg, PUBLIC_KEY, true);
        if (!hash_equals($expected, $sig)) return null;

    } else {
        return null;
    }

    return json_decode(jwt_base64url_decode($b64payload));
}

function get_payload(): ?object {
    $token = $_COOKIE['token'] ?? '';
    if (!$token) return null;
    return verify_token($token);
}

function require_role(string $role): void {
    $payload = get_payload();
    if (!$payload) {
        header('Location: /login.php');
        exit;
    }
    if (($payload->role ?? '') !== $role) {
        http_response_code(403);
        die('<h2>403 — Access Denied</h2><p>Insufficient privileges.</p><a href="/dashboard.php">Back</a>');
    }
}
