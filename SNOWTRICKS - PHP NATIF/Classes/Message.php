<?php

namespace App;

class Message
{
    public int $id;
    public string $content;
    public string $created_at;
    public int $author_id;
    public int $trick_id;

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
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
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * @param string $created_at
     */
    public function setCreatedAt(string $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return int
     */
    public function getAuthorId(): int
    {
        return $this->author_id;
    }

    /**
     * @param int $author_id
     */
    public function setAuthorId(int $author_id): void
    {
        $this->author_id = $author_id;
    }

    /**
     * @return int
     */
    public function getTrickId(): int
    {
        return $this->trick_id;
    }

    /**
     * @param int $trick_id
     */
    public function setTrickId(int $trick_id): void
    {
        $this->trick_id = $trick_id;
    }

    public function createMessage ($content, $author_id, $trick_id) {

        $content = trim($content);
        $content = substr($content, 0, 1000);

        $sql = 'INSERT INTO messages (content, createdAt, authorId, trickId)
                VALUES (:content, NOW(), :authorId, :trickId)';
        $params = array(
            ':content' => $content,
            ':authorId' => $author_id,
            ':trickId' => $trick_id
        );

        if (DB::exec($sql, $params) && $lastInsertId = DB::lastInsertId()) {
            $this->setId($lastInsertId);
            return true;
        }
        return false;
    }

    public function showByTrickSlug(string $slug) {
        $sql = 'SELECT messages.id, messages.content, messages.createdAt,
            tricks.slug AS trickSlug,
            users.name AS authorName,
            users.photoPath AS authorPhoto
            FROM messages
            JOIN tricks ON messages.trickId = tricks.id
            JOIN users ON messages.authorId = users.id
            WHERE tricks.slug = :slug
            ORDER BY messages.createdAt DESC;';

        $params = array(':slug' => $slug);

        if ($result = DB::exec($sql, $params)) {

            if ($result->rowCount() >= 1) {
                return $result->fetchAll(\PDO::FETCH_OBJ);
            }
        }
        return [];
    }
}