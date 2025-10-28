<?php

declare(strict_types=1);

namespace Alura\Mvc\Repository;

use Alura\Mvc\Entity\Video;
use PDO;

class VideoRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function add(Video $video): bool
    {
        // Seu código de adicionar vídeo (INSERT)
        // Lembre-se de ATUALIZAR este INSERT para incluir :image_path
        $sql = 'INSERT INTO videos (url, title, image_path) VALUES (:url, :title, :image_path)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':url', $video->url);
        $stmt->bindValue(':title', $video->title);
        $stmt->bindValue(':image_path', $video->image_path);

        $result = $stmt->execute();
        
        if ($result === false) {
            return false;
        }

        $id = $this->pdo->lastInsertId();
        $video->setId((int)$id);

        return true;
    }

    public function remove(int $id): bool
    {
        // Seu código de remover
        $sql = 'DELETE FROM videos WHERE id = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function update(Video $video): bool
    {
        // Seu código de atualizar
        // Lembre-se de ATUALIZAR este UPDATE para incluir image_path
        $sql = 'UPDATE videos SET url = :url, title = :title, image_path = :image_path WHERE id = :id;';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':url', $video->url);
        $stmt->bindValue(':title', $video->title);
        $stmt->bindValue(':image_path', $video->image_path);
        $stmt->bindValue(':id', $video->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * @return Video[]
     */
    public function all(): array
    {
        // 1. GARANTA QUE VOCÊ ESTÁ SELECIONANDO A NOVA COLUNA (SELECT *)
        $videoList = $this->pdo->query('SELECT * FROM videos;')->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map(
            $this->hydrateVideo(...),
            $videoList
        );
    }

    public function find(int $id)
    {
        // Lembre-se de atualizar este SELECT também
        $stmt = $this->pdo->prepare('SELECT * FROM videos WHERE id = ?;');
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();

        return $this->hydrateVideo($stmt->fetch(PDO::FETCH_ASSOC));
    }

    private function hydrateVideo(array $videoData): Video
    {
        // 2. PASSE A NOVA INFORMAÇÃO PARA O CONSTRUTOR DA ENTIDADE
        $video = new Video(
            $videoData['url'], 
            $videoData['title'], 
            $videoData['image_path'] // <-- A MUDANÇA ESTÁ AQUI
        );
        $video->setId($videoData['id']);

        return $video;
    }
}
