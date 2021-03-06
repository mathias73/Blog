<?php

namespace App\Repository;

use App\Services\DbManager;
use App\Model\User;

class UserRepository extends DbManager
{
    public function __construct()
    {
        $this->dbConnect();
    }

    public function addUser(User $user): void
    {
        $addUser = $this->dbConnect()->prepare(
            'INSERT INTO User (firstname, lastname, email, pseudo, password, type, createdAt) 
            VALUES (:firstname, :lastname, :email, :pseudo, :password, :type, :createdAt)'
        );

        $addUser->bindValue(':firstname', $user->getFirstname(), \PDO::PARAM_STR);
        $addUser->bindValue(':lastname', $user->getLastname(), \PDO::PARAM_STR);
        $addUser->bindValue(EMAIL, $user->getEmail(), \PDO::PARAM_STR);
        $addUser->bindValue(PSEUDO, $user->getPseudo(), \PDO::PARAM_STR);
        $addUser->bindValue(':password', $user->getPassword(), \PDO::PARAM_STR);
        $addUser->bindValue(':type', $user->getType(), \PDO::PARAM_STR);
        $addUser->bindValue(':createdAt', $user->getCreatedAt(), \PDO::PARAM_STR);

        $addUser->execute();
    }

    public function getUserByPseudo(string $pseudo)
    {
        $userPseudo = $this->dbConnect()->prepare("SELECT id, pseudo, password, type FROM User WHERE pseudo = :pseudo");
        $userPseudo->bindValue(PSEUDO, $pseudo);
        $userPseudo->execute();
        $userPseudo->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, MODEL_USER);
        return $userPseudo->fetch();
    }

    public function getUserByEmail(string $email)
    {
        $userEmail = $this->dbConnect()->prepare("SELECT id FROM User WHERE email = :email");
        $userEmail->bindValue(EMAIL, $email);
        $userEmail->execute();
        $userEmail->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, MODEL_USER);
        return $userEmail->fetch();
    }

    public function getUserById($id)
    {
        $userId = $this->dbConnect()->prepare("SELECT id, pseudo, password, type type FROM User WHERE id = :id");
        $userId->bindValue(':id', $id);
        $userId->execute();
        $userId->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, MODEL_USER);
        return $userId->fetch();
    }

    public function getPasswordHash(User $user)
    {
        $userHash = $this->dbConnect()->prepare("SELECT password FROM User WHERE pseudo = :pseudo");
        $userHash->bindValue(PSEUDO, $user->getPseudo());
        $userHash->execute();
        $userHash->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, MODEL_USER);
        $rslt = $userHash->fetch();
        return $rslt["password"] ?? null;
    }

    public function getLastUserId()
    {
        $id = $this->dbConnect()->prepare('SELECT MAX(id) FROM User');
        $id->execute();
        return $id->fetch();
    }

    public function newPassword(string $password, string $email): void
    {
        $newPassword = $this->dbConnect()->prepare("UPDATE User SET password = :password WHERE email = :email");
        $newPassword->bindValue(':password', $password);
        $newPassword->bindValue(EMAIL, $email);
        $newPassword->execute();
    }

    public function getAllUserPseudo(): array
    {
        $userPseudo = $this->dbConnect()->prepare("SELECT id, pseudo FROM User");
        $userPseudo->execute();
        $userPseudo->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, MODEL_USER);
        return $userPseudo->fetchAll();
    }
}
