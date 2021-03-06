<?php

namespace App\Repository;

use App\Model\Blogger;
use App\Services\DbManager;

class BloggerRepository extends DbManager
{

    public function getInfoBloggerById(int $id)
    {
        $blogger = $this->dbConnect()->prepare('SELECT user.pseudo, blogger.description, blogger.country, blogger.profilePicture, blogger.idUser FROM Blogger, User WHERE blogger.idUser = user.id AND blogger.idUser = :id');
        $blogger->bindValue(':id', $id);
        $blogger->execute();
        $blogger->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Model\Post');
        return $blogger->fetch();
    }

    public function createUserProfil(int $id): void
    {
        $lastIdUser = $this->dbConnect()->prepare(
            'INSERT INTO Blogger (idUser) VALUES (:idUser)'
        );
        $lastIdUser->bindValue(':idUser', $id, \PDO::PARAM_INT);

        $lastIdUser->execute();
    }

    public function getIdBlogger(): array
    {
        $id = $this->dbConnect()->prepare('SELECT idUser FROM Blogger');
        $id->execute();
        return $id->fetchAll();
    }

    public function modifyProfil(int $id, Blogger $blogger): void
    {
        $modifyProfil = $this->dbConnect()->prepare(
            'UPDATE Blogger SET description = :description, country = :country, profilePicture = :profilPicture WHERE idUser = :id'
        );

        $modifyProfil->bindValue(':description', $blogger->getDescription(), \PDO::PARAM_STR);
        $modifyProfil->bindValue(':country', $blogger->getCountry(), \PDO::PARAM_STR);
        $modifyProfil->bindValue(':profilPicture', $blogger->getProfilePicture(), \PDO::PARAM_STR);
        $modifyProfil->bindValue(':id', $id, \PDO::PARAM_INT);
        $modifyProfil->execute();
    }

    public function getPostsFromBlogger(int $id): array
    {
        $postBlogger = $this->dbConnect()->prepare("SELECT post.id, user.firstname, post.title, post.lead, post.createdAt FROM post, user WHERE post.idUser = user.id AND user.id = :id AND isValid = 1 ORDER BY id LIMIT 5");
        $postBlogger->bindValue(':id', $id, \PDO::PARAM_INT);
        $postBlogger->execute();
        $postBlogger->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Model\Post');
        return $postBlogger->fetchAll();
    }
}
