<?php

namespace App;

use App\DB;
use PDO;
use App\Illustration;

class Trick
{
    public int $id;
    public string $name;
    public string $description;
    public string $groupName;
    public string $slug;
    public int $userId;
    public $createdAt;
    public $updatedAt;

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getGroupName(): string
    {
        return $this->groupName;
    }

    /**
     * @param string $groupName
     */
    public function setGroupName(string $groupName): void
    {
        $this->groupName = $groupName;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function createTrick () {
        $sql = 'INSERT INTO tricks (name, description, groupName, slug, userId, createdAt)
                VALUES (:name, :description, :groupName, :slug, :userId, NOW())';
        $params = array(
            ':name' => $this->name,
            ':description' => $this->description,
            ':groupName' => $this->groupName,
            ':slug' => $this->slug,
            ':userId' => $this->userId
        );

        if (DB::exec($sql, $params) && $lastInsertId = DB::lastInsertId()) {
            $this->setId($lastInsertId);
            return true;
        }
        return false;
    }

    public function showById()
    {
        $sql = 'SELECT * FROM tricks
        WHERE id = :id';
        $params = array(':id' => $this->id);
        if ($result = DB::exec($sql, $params)) {
            $result = $result->fetchAll(\PDO::FETCH_OBJ);
            return reset($result);
        }
        return false;
    }

    public function showBySlug () {
        $sql = 'SELECT * FROM tricks
        WHERE slug = :slug';
        $params = array(':slug' => $this->slug);
        if ($result = DB::exec($sql, $params)) {
            $result = $result->fetchAll(\PDO::FETCH_OBJ);
            return reset($result);
        }
        return [];
    }

    public function updateTrick ($name, $description, $groupName, $trickId) {

        $this->setId($trickId);

        if ($this->showById()) {

            $slug = $this->createSlug($name);

            $sql = 'UPDATE tricks
        SET name = :name,
            description = :description,
            groupName = :groupName,
            slug = :slug,
            updatedAt = NOW()
        WHERE id = :id';

            $params = array(
                ':name' => $name,
                ':description' => $description,
                ':groupName' => $groupName,
                ':slug' => $slug,
                ':id' => $this->id
            );

            if (DB::exec($sql, $params)) {
                return true;
            }
        }
        return false;
    }

    public function deleteTrick () {
        if ($this->showById()) {

            $sql = 'DELETE FROM tricks WHERE id = :id';
            $params = array(
                ':id' => $this->id
            );

            if (DB::exec($sql, $params)) {
                return true;
            }
        }
        return false;
    }

    public function showAllTricks() {
        $sql = 'SELECT * FROM tricks';
        if ($result = DB::exec($sql)) {
            return $result->fetchAll(\PDO::FETCH_OBJ);
        }
    return false;
    }

    public function createSlug($string, $maxLength = 60): string
    {
        $slug = transliterator_transliterate('Any-Latin; Latin-ASCII', $string);
        $slug = mb_strtolower($slug, 'UTF-8');
        $slug = preg_replace('/[^\p{L}\p{N}]+/u', '-', $slug);
        $slug = trim($slug, '-');

        if (strlen($slug) > $maxLength) {
            $slug = substr($slug, 0, $maxLength);
            $slug = rtrim($slug, '-');
        }

        return $slug;
    }


    public function getFirstIllustration ($trickId) {
        $sql = "SELECT path 
              FROM illustrations 
              WHERE trickId = :trickId
              LIMIT 1";

        $params = array(
            ':trickId' => $trickId
        );

        if ($result = DB::exec($sql, $params)) {
            if ($result = $result->fetch(PDO::FETCH_ASSOC)) {
                return $result['path'];
            }
        }
        return Illustration::DEFAULT_TRICK_ILLUSTRATION_PATH;
    }

    public function getTrickAuthor ($trickId) {
        $sql = "SELECT users.name
                FROM tricks
                JOIN users ON tricks.userId = users.id
                WHERE tricks.id = :trickId ";

        $params = array(
            ':trickId' => $trickId
        );

        if ($result = DB::exec($sql, $params)) {
            $result = $result->fetch(PDO::FETCH_ASSOC);
            return $result['name'];
        }
        return '';
    }

    public function showFirstIllustrations (array $allTricks) {
        $allIllustrations = array();
        if ($allTricks) {
            foreach ($allTricks as $trick) {
                $allIllustrations[$trick->id] = $this->getFirstIllustration($trick->id);
            }
        }
        return $allIllustrations;
    }

    public function createTrickProcess($name, $description, $groupName, $userId) {

        $this->setName($name);
        $this->setDescription($description);
        $this->setGroupName($groupName);
        $this->setUserId($userId);
        $slug = $this->createSlug($name);
        $this->setSlug($slug);

        if (!$this->createTrick()) {
            return false;
        }
        return true;
    }

}