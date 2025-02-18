<?php

namespace Infrastructure\Symfony\Services\Jwt;

use Domain\User\Service\TokenServiceInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTTokenService  implements TokenServiceInterface
{
    public function __construct(
        private readonly string $secretKey,
        private readonly string $algorithm
    ) {}

    public function generateToken(string $userId, int $expirationMinutes = 60): string
    {
        $issuedAt = new \DateTimeImmutable();
        $expire = $issuedAt->modify(sprintf('+%d minutes', $expirationMinutes));

        $payload = [
            'iat' => $issuedAt->getTimestamp(),
            'exp' => $expire->getTimestamp(),
            'userId' => $userId,
            'purpose' => 'password_reset'
        ];

        return JWT::encode($payload, $this->secretKey, $this->algorithm);
    }

    public function validateToken(string $token): ?string
    {
        try {
            $decoded = JWT::decode(
                $token,
                new Key($this->secretKey, $this->algorithm)
            );

            if ($decoded->purpose !== 'password_reset') {
                return null;
            }

            if ($decoded->exp < time()) {
                return null;
            }

            return $decoded->userId;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function invalidateToken(string $token): void
    {
        
    }
}