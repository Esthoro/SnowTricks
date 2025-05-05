<?php

namespace App;

class Illustration
{
    const DEFAULT_TRICK_ILLUSTRATION_PATH = 'trickDefaultPicture.webp';

    public int $id;
    public int $trickId;
    public string $path;

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
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

    public function createIllustration(): bool {
        $sql = 'INSERT INTO illustrations (path, trickId) VALUES(:path, :trickId)';
        $params = array(
            ':path' => $this->path,
            ':trickId' => $this->trickId
        );

        if ($result = DB::exec($sql, $params)) {
            return true;
        }
        return false;
    }

    public function showByTrickId(int $trickId) {
        $sql = 'SELECT * FROM illustrations WHERE trickId = :trickId';

        $params = array(':trickId' => $trickId);

        if ($result = DB::exec($sql, $params)) {

            if ($result->rowCount() >= 1) {
                return $result->fetchAll(\PDO::FETCH_OBJ);
            }
        }
        return [];
    }

    public function uploadTrickIllustrations($pictures, $trickId): bool
    {
        $uploadsDir = __DIR__ . '/../assets/uploads/';
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 1024 * 1024; // 1 Mo

        $isMultiple = is_array($pictures['tmp_name']);

        if (!$isMultiple) {
            $pictures = [
                'tmp_name' => [$pictures['tmp_name']],
                'name' => [$pictures['name']],
                'size' => [$pictures['size']],
                'error' => [$pictures['error']],
                'type' => [$pictures['type']]
            ];
        }

        if (empty($pictures['tmp_name'])) {
            return false;
        }

        foreach ($pictures['tmp_name'] as $key => $tmpName) {
            $fileType = mime_content_type($tmpName);
            $fileSize = $pictures['size'][$key];

            if (!in_array($fileType, $allowedTypes) || $fileSize > $maxSize) {
                return false;
            }

            $fileExtension = pathinfo($pictures['name'][$key], PATHINFO_EXTENSION);
            $fileName = uniqid('img_') . '.' . $fileExtension;

            if (!move_uploaded_file($tmpName, $uploadsDir . $fileName)) {
                return false;
            }

            $this->setTrickId($trickId);
            $this->setPath($fileName);
            if (!$this->createIllustration()) {
                return false;
            }
        }

        return true;
    }



    public function delete () {
        if ($this->showById()) {

            $sql = 'DELETE FROM illustrations WHERE id = :id';
            $params = array(
                ':id' => $this->id
            );

            if (DB::exec($sql, $params)) {
                return true;
            }
        }
        return false;
    }

    public function showById()
    {
        $sql = 'SELECT * FROM illustrations WHERE id = :id';
        $params = array(':id' => $this->id);
        if ($result = DB::exec($sql, $params)) {
            $result = $result->fetchAll(\PDO::FETCH_OBJ);
            return reset($result);
        }
        return false;
    }

}