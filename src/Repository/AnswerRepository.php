<?php

namespace App\Repository;

use App\Model\Answer;
use App\Services\DbManager;

class AnswerRepository extends DbManager
{
    public function __construct()
    {
        $this->dbConnect();
    }

    public function addAnswer(Answer $answer): void
    {
        $addAnswer = $this->dbConnect()->prepare(
            'INSERT INTO Answer (idPost, idUser, answer, createdAt, isValid) 
            VALUES (:idPost, :idUser, :answer, :createdAt, :isValid)'
        );

        $addAnswer->bindValue(':idUser', $answer->getIdUser(), \PDO::PARAM_INT);
        $addAnswer->bindValue(':idPost', $answer->getIdPost(), \PDO::PARAM_INT);
        $addAnswer->bindValue(':answer', $answer->getAnswer(), \PDO::PARAM_STR);
        $addAnswer->bindValue(':createdAt', $answer->getCreatedAt(), \PDO::PARAM_STR);
        $addAnswer->bindValue(':isValid', $answer->getIsValid(), \PDO::PARAM_INT);
        $addAnswer->execute();
    }

    public function getAllAnswerFromOnePost(int $id): array
    {
        $answer = $this->dbConnect()->prepare(
            "SELECT answer.id, answer.idUser, answer.answer, answer.createdAt, 
            user.firstname FROM answer, user, post WHERE answer.idUser = user.id AND answer.idPost = :id AND answer.isValid = 1 GROUP BY answer.id"
        );
        $answer->bindValue(':id', $id, \PDO::PARAM_INT);
        $answer->execute();
        $answer->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, MODEL_ANSWER);
        return $answer->fetchAll();
    }

    public function getIdUserFromAnswer(int $id)
    {
        $idAnswer = $this->dbConnect()->prepare("SELECT id, idPost, idUser, answer from answer where id = :id");
        $idAnswer->bindValue(':id', $id, \PDO::PARAM_INT);
        $idAnswer->execute();
        $idAnswer->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, MODEL_ANSWER);
        return $idAnswer->fetch();
    }

    public function modifyAnswer(int $id, Answer $answer): void
    {
        $modifyAnswer = $this->dbConnect()->prepare(
            'UPDATE Answer SET answer = :answer, updatedAt = :updatedAt WHERE id = :id'
        );

        $modifyAnswer->bindValue(':answer', $answer->getAnswer(), \PDO::PARAM_STR);
        $modifyAnswer->bindValue(':updatedAt', $answer->getUpdateAt(), \PDO::PARAM_STR);
        $modifyAnswer->bindValue(':id', $id, \PDO::PARAM_INT);
        $modifyAnswer->execute();
    }

    public function getAnswerById(int $id)
    {
        $answer = $this->dbConnect()->prepare("SELECT idUser, idPost FROM Answer WHERE id = :id AND isValid = 1");
        $answer->bindValue(':id', $id);
        $answer->execute();
        $answer->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, MODEL_ANSWER);
        return $answer->fetch();
    }

    public function deleteAnswer($id)
    {
        $deletePost = $this->dbConnect()->prepare(
            'DELETE FROM Answer WHERE id = :id'
        );

        $deletePost->bindValue(':id', $id, \PDO::PARAM_INT);

        $deletePost->execute();
    }
}
