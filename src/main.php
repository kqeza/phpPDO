<?php

namespace Prince\Task9;

try {
    $pdo = new \PDO(dsn: 'sqlite::memory:');
    $pdo->setAttribute(attribute: \PDO::ATTR_ERRMODE, value: \PDO::ERRMODE_EXCEPTION);

    $pdo->exec(statement: "
        CREATE TABLE users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE
        );
    ");

    $user = new User(pdo: $pdo);

    $newUserId = $user->add(name: 'John Doom', email: 'john.doom@example.com');
    echo "Пользователь добавлен с id: $newUserId\n";

    echo "Ищем пользовтелей с 'John' в их имени или email:\n";
    $searchResults = $user->search(searchQuery: 'John');
    print_r(value: $searchResults);

    echo "все пользователи:\n";
    $allUsers = $user->search();
    print_r(value: $allUsers);

    echo "Обновляем даные о пользователе с ID $newUserId:\n";
    $user->update(id: $newUserId, name: 'Johnny Doom', email: null);

    $updatedUser = $user->search(searchQuery: 'Johnny');
    print_r(value: $updatedUser);

    echo "Удаляем пользователей ID $newUserId...\n";
    $user->delete(id: $newUserId);

    echo "Все пользователи после удаления:\n";
    $allUsersAfterDeletion = $user->search();
    print_r(value: $allUsersAfterDeletion);

} catch (\PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
