<?php

namespace App\Repository\BaseSessions;

use App\Enum\SessionVariant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

abstract class BaseSessionsRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry, string $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }

    public function createSession(SessionVariant $variant, string $identifier_value, int $days, int $hours, int $minutes, string &$expiration_date): ?string
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT * FROM create_session(
                (SELECT {$variant->COLUMN()} FROM {$variant->TABLE()} WHERE {$variant->IDENTIFIER()} = :identifier_value),
                :variant,
                :days,
                :hours,
                :minutes,
                :timezone
            )
        ";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue('identifier_value', $identifier_value);
        $stmt->bindValue('variant', $variant->value);
        $stmt->bindValue('days', $days);
        $stmt->bindValue('hours', $hours);
        $stmt->bindValue('minutes', $minutes);
        $stmt->bindValue('timezone', 'UTC');

        $result = $stmt->executeQuery()->fetchAssociative();


        if (!$result) {
            return null;
        }

        $expiration_date = $result['expiration_date'];
        return $result['session_token'];

    }

    public function checkSession(SessionVariant $variant, string $identifier_value, string $token, int $days, int $hours, int $minutes, string &$expiration_date): bool
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT * FROM check_session(
                (SELECT {$variant->COLUMN()} FROM {$variant->TABLE()} WHERE {$variant->IDENTIFIER()} = :identifier_value),
                :variant,
                :token,
                :days,
                :hours,
                :minutes,
                :timezone
            )
        ";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue('identifier_value', $identifier_value);
        $stmt->bindValue('variant', $variant->value);
        $stmt->bindValue('token', $token);
        $stmt->bindValue('days', $days);
        $stmt->bindValue('hours', $hours);
        $stmt->bindValue('minutes', $minutes);
        $stmt->bindValue('timezone', 'UTC');

        $result = $stmt->executeQuery()->fetchAssociative();

        if (!$result) {
            return false;
        }

        $expiration_date = $result['expiration_date'];
        return $result['is_valid'];
    }

    public function deleteSession(SessionVariant $variant, string $identifier_value, string $token): bool
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT * FROM delete_session(
                (SELECT {$variant->COLUMN()} FROM {$variant->TABLE()} WHERE {$variant->IDENTIFIER()} = :identifier_value),
                :variant,
                :token
            )
        ";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue('identifier_value', $identifier_value);
        $stmt->bindValue('variant', $variant->value);
        $stmt->bindValue('token', $token);

        $result = $stmt->executeQuery()->fetchAssociative();


        if (!$result) {
            return false;
        }

        return $result['delete_session'];
    }
}