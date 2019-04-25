<?php


class Comment
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var int
     */
    private $userId;
    /**
     * @var int
     */
    private $postId;
    /**
     * @var DateTime
     */
    private $creationDate;
    /**
     * @var string
     */
    private $text;

    public function __construct()
    {
        $this->id = -1;
        $this->userId = '';
        $this->postId = '';
        $this->creationDate = '';
        $this->text = '';
    }

    public function saveToDB(PDO $conn)
    {

        if ($this->id === -1) {

            $stmt = $conn->prepare("INSERT INTO Comments SET text=:text, creationDate =:creationDate, userId =:userId, postId=:postId");

            try {
                $result = $stmt->execute([
                    'text' => $this->text,
                    'creationDate' => $this->creationDate,
                    'userId' => $this->userId,
                    'postId' => $this->postId
                ]);

            } catch (PDOException $e) {
                echo 'ERROR: ' . $e->getMessage();
                die;
            }
            $this->id = $conn->lastInsertId();
        } else {
            try {
                $stmt = $conn->prepare(
                    'UPDATE Comments SET text=:text, creationDate =:creationDate, userId =:userId, postId=:postId WHERE id=:id');
                $result = $stmt->execute([
                    'text' => $this->text,
                    'creationDate' => $this->creationDate,
                    'userId' => $this->userId,
                    'postId' => $this->postId,
                    'id' => $this->id
                ]);
            } catch (PDOException $e) {
                echo 'ERROR: ' . $e->getMessage();
                die;
            }
            if ($result === true) {
                return true;
            }
        }
    }

    static public function loadCommentById(PDO $conn, $id): ?Comment
    {
        $stmt = $conn->prepare('SELECT * FROM Comments WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedComment = new Comment();
            $loadedComment->id = $row['id'];
            $loadedComment->userId = $row['userId'];
            $loadedComment->postId = $row['postId'];
            $loadedComment->creationDate = $row['creationDate'];
            $loadedComment->text = $row['text'];
            return $loadedComment;
        }
        return null;
    }

    static public function loadAllCommentsByPostId(PDO $conn, $postId)
    {
        $ret = [];
        $stmt = $conn->prepare("SELECT * FROM Comments WHERE postId = :postId ORDER BY creationDate ASC");
        $result = $stmt->execute(['postId' => $postId]);
        if ($result !== false && $stmt->rowCount() != 0) {
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($row as $comment) {
                $loadedComment = new Comment();
                $loadedComment->id = $comment['id'];
                $loadedComment->userId = $comment['userId'];
                $loadedComment->postId = $comment['postId'];
                $loadedComment->creationDate = $comment['creationDate'];
                $loadedComment->text = $comment['text'];
                $ret[] = $loadedComment;
            }
        }
        return $ret;
    }

    static public function loadAllCommentsByUserId(PDO $conn, $userId)
    {
        $ret = [];
        $stmt = $conn->prepare("SELECT * FROM Comments WHERE userId = :userId");
        $result = $stmt->execute(['userId' => $userId]);
        if ($result !== false && $stmt->rowCount() != 0) {
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($row as $comment) {
                $loadedComment = new Comment();
                $loadedComment->id = $comment['id'];
                $loadedComment->userId = $comment['userId'];
                $loadedComment->postId = $comment['postId'];
                $loadedComment->creationDate = $comment['creationDate'];
                $loadedComment->text = $comment['text'];
                $ret[] = $loadedComment;
            }
        }
        return $ret;
    }

    public function delete(PDO $conn)
    {
        if ($this->id != -1) {
            $stmt = $conn->prepare('DELETE FROM Comments WHERE id=:id');
            try {
                $result = $stmt->execute(['id' => $this->id]);
            } catch (PDOException $e) {
                echo 'ERROR: ' . $e->getMessage();
                die;
            }
            if ($result === true) {
                $this->id = -1;
                return true;
            }
            return false;
        }
        return true;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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

    /**
     * @return int
     */
    public function getPostId(): int
    {
        return $this->postId;
    }

    /**
     * @param int $postId
     */
    public function setPostId(int $postId): void
    {
        $this->postId = $postId;
    }

    /**
     * @return string
     */
    public function getCreationDate(): string
    {
        return $this->creationDate;
    }

    /**
     * @param DateTime
     */
    public function setCreationDate(): void
    {
        $datetime = date_create()->format('Y-m-d H:i:s');
        $this->creationDate = $datetime;
    }


    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }


}