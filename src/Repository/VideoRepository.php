<?php
declare(strict_types=1);
namespace Alura\Mvc\Repository;

use Alura\Mvc\Modelo\Video; // Importa a "Planta"
use PDO;

class VideoRepository
{
    public function __construct(private PDO $pdo) {}

    public function add(Video $video): bool
    {
        $sql = 'INSERT INTO videos (url, title, file_path) VALUES (?, ?, ?)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $video->url);
        $stmt->bindValue(2, $video->title);
        $stmt->bindValue(3, $video->file_path);
        return $stmt->execute();
    }

    public function remove(int $id): bool
    {
        $sql = 'DELETE FROM videos WHERE id = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $id);
        return $stmt->execute();
    }

    public function update(Video $video): bool
    {
        $sql = 'UPDATE videos SET url = :url, title = :title, file_path = :file_path WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':url', $video->url);
        $stmt->bindValue(':title', $video->title);
        $stmt->bindValue(':file_path', $video->file_path);
        $stmt->bindValue(':id', $video->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /** @return Video[] */
    public function all(): array
    {
        $videoList = $this->pdo->query('SELECT * FROM videos;')->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($d) => $this->hydrateVideo($d), $videoList);
    }

    public function find(int $id): Video|false
    {
        $stmt = $this->pdo->prepare('SELECT * FROM videos WHERE id = ?;');
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data === false ? false : $this->hydrateVideo($data);
    }

    private function hydrateVideo(array $data): Video
    {
        return new Video(
            $data['url'],
            $data['title'],
            $data['id'],
            $data['file_path']
        );
    }
}