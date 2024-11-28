<?php
namespace Prince\Task9;

declare(strict_types=1);

class User
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function search(?string $searchQuery = null): array
    {
        if (empty($searchQuery)) {
            $stmt = $this->pdo->query('SELECT * FROM users');
        } else {
            $stmt = $this->pdo->prepare(query: 'SELECT * FROM users WHERE name LIKE :query OR email LIKE :query');
            $stmt->execute(params: [':query' => '%' . $searchQuery . '%']);
        }

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare(query: 'DELETE FROM users WHERE id = :id');
        return $stmt->execute(params: [':id' => $id]);
    }

    public function add(string $name, string $email): int
    {
        $stmt = $this->pdo->prepare(query: 'INSERT INTO users (name, email) VALUES (:name, :email)');
        $stmt->execute(params: [':name' => $name, ':email' => $email]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, ?string $name = null, ?string $email = null): bool
    {
        $updates = [];
        $params = [':id' => $id];

        if ($name !== null) {
            $updates[] = 'name = :name';
            $params[':name'] = $name;
        }

        if ($email !== null) {
            $updates[] = 'email = :email';
            $params[':email'] = $email;
        }

        if (empty($updates)) {
            return false;
        }

        $sql = 'UPDATE users SET ' . implode(separator: ', ', array: $updates) . ' WHERE id = :id';
        $stmt = $this->pdo->prepare(query: $sql);

        return $stmt->execute(params: $params);
    }
}
