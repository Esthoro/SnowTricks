<?php

namespace App;

class Video
{
    public int $id;
    public string $embedCode;

    public int $trickId;

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
     * @return int
     */
    public function getTrickId(): int
    {
        return $this->trickId;
    }

    /**
     * @param int $trickId
     */
    public function setTrickId(int $trickId): void
    {
        $this->trickId = $trickId;
    }

    /**
     * @return string
     */
    public function getEmbedCode(): string
    {
        return $this->embedCode;
    }

    /**
     * @param string $embedCode
     */
    public function setEmbedCode(string $embedCode): void
    {
        $this->embedCode = $embedCode;
    }

    public function createVideo($trickId, $videoEmbed): bool {
        $sql = 'INSERT INTO videos (embedCode, trickId) VALUES(:embedCode, :trickId)';
        $params = array(
            ':embedCode' => $videoEmbed,
            ':trickId' => $trickId
        );

        if (DB::exec($sql, $params)) {
            return true;
        }
        return false;
    }

    public function showByTrickId(int $trickId) {
        $sql = 'SELECT * FROM videos WHERE trickId = :trickId';

        $params = array(':trickId' => $trickId);

        if ($result = DB::exec($sql, $params)) {

            if ($result->rowCount() >= 1) {
                return $result->fetchAll(\PDO::FETCH_OBJ);
            }
        }
        return [];
    }

    public function setTrickVideos (array $videos, int $trickId) {
        foreach ($videos as $videoEmbed) {
            $videoEmbed = trim($videoEmbed);
            if (!empty($videoEmbed)) {
                if (!$this->createVideo($trickId, $videoEmbed)) {
                    return false;
                }
            }
        }
        return true;
    }

    public function delete()
    {
        if ($this->showById()) {

            $sql = 'DELETE FROM videos WHERE id = :id';
            $params = array(
                ':id' => $this->id
            );

            if (DB::exec($sql, $params)) {
                return true;
            }
        }
        return false;
    }

    private function showById()
    {
        $sql = 'SELECT * FROM videos WHERE id = :id';
        $params = array(':id' => $this->id);
        if ($result = DB::exec($sql, $params)) {
            $result = $result->fetchAll(\PDO::FETCH_OBJ);
            return reset($result);
        }
        return false;
    }

}