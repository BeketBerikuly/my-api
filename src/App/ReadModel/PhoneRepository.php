<?php

namespace App\ReadModel;

class PhoneRepository
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function countAll(): int
    {
        return $this->pdo->query('SELECT COUNT(id) FROM phones')->fetchColumn();
    }

    public function all(int $offset, int $limit): array
    {
        $stmt = $this->pdo->prepare('
            SELECT
                p.*,
                (SELECT COUNT(*) FROM feedbacks f WHERE f.phone_id = p.id) feedbacks_count
            FROM phones p LIMIT :limit OFFSET :offset
        ');

        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);

        $stmt->execute();

        return array_map([$this, 'hydratePostList'], $stmt->fetchAll());
    }

    public function findCountry(string $phoneNumber): ?string
    {
        $stmt = $this->pdo->prepare('SELECT code, full_name FROM country_codes');
        $stmt->execute();

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            if (substr($phoneNumber, 0, strlen($row['code'])) == $row['code']) {
                return $row['full_name'];
            }
        }

        return null;
    }

    public function find(string $phoneNumber): ?array
    {
        $stmt = $this->pdo->prepare('SELECT p.* FROM phones p WHERE phone_number LIKE :phoneNumber');
        $stmt->bindValue(':phoneNumber', $phoneNumber . '%', \PDO::PARAM_INT);
        $stmt->execute();

        if (!$phones = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            return null;
        }

        $totalRating = 0;
        $ratingCount = 0;

        $stmt = $this->pdo->prepare('SELECT * FROM feedbacks WHERE phone_id = :phone_id ORDER BY id ASC');
        $stmt->bindValue(':phone_id', (int)$phones['id'], \PDO::PARAM_INT);
        $stmt->execute();

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $feedbacks['id'] = $row['id'];
            $feedbacks['text'] = $row['text'];
            $feedbacks['name'] = $row['name'] ?? 'анонимно';

            $totalRating += $row['ratings'];
            $ratingCount++;
        }

        $feedbacks['rating'] = round($totalRating / $ratingCount, 2);

        return $this->hydratePostDetail($phones, $feedbacks);
    }

    public function findFeedbacks(array $phoneNumber): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM feedbacks');
        $stmt->execute();

        $feedbacks = [];

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $feedbacks['id'] = $row['id'];
            $feedbacks['text'] = $row['text'];
            $feedbacks['name'] = $row['name'] ?? 'анонимно';
            $feedbacks['rating'] = $row['rating'];
        }

        return $feedbacks;
    }

    private function hydratePostList(array $row): array
    {
        return [
            'id' => (int)$row['id'],
            'phone_number' => $row['phone_number'],
            'feedbacks_count' => $row['feedbacks_count'],
        ];
    }

    private function hydratePostDetail(array $row, array $feedbacks): array
    {
        return [
            'id' => (int)$row['id'],
            'phone_number' => $row['phone_number'],
            'feedbacks' => array_map([$this, 'hydrateFeedback'], $feedbacks),
        ];
    }

    private function hydrateFeedback(array $row): array
    {
        return [
            'id' => (int)$row['id'],
            'name' => $row['name'],
            'text' => $row['text'],
        ];
    }
}
