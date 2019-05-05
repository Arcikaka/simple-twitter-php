<?php


class Message
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var int
     */
    private $sentBy;
    /**
     * @var int
     */
    private $sentTo;
    /**
     * @var string
     */
    private $message;
    /**
     * @var DateTime
     */
    private $creationDate;
    /**
     * @var int
     */
    private $seen;

    public function __construct()
    {
        $this->id = -1;
        $this->sentBy = '';
        $this->sentTo = '';
        $this->creationDate = '';
        $this->message = '';
        $this->seen = '';
    }

    public function saveToDB(PDO $conn)
    {

        if ($this->id === -1) {

            $stmt = $conn->prepare("INSERT INTO MessageMailbox SET message=:message, creationDate =:creationDate, sentBy =:sentBy, sentTo=:sentTo, seen =:seen");

            try {
                $result = $stmt->execute([
                    'message' => $this->message,
                    'creationDate' => $this->creationDate,
                    'sentBy' => $this->sentBy,
                    'sentTo' => $this->sentTo,
                    'seem' => $this->seen
                ]);

            } catch (PDOException $e) {
                echo 'ERROR: ' . $e->getMessage();
                die;
            }
            $this->id = $conn->lastInsertId();
        } else {
            try {
                $stmt = $conn->prepare(
                    'UPDATE MessageMailbox SET message=:message, creationDate =:creationDate, sentBy =:sentBy, sentTo=:sentTo WHERE id=:id');
                $result = $stmt->execute([
                    'message' => $this->message,
                    'creationDate' => $this->creationDate,
                    'sentBy' => $this->sentBy,
                    'sentTo' => $this->sentTo,
                    'seem' => $this->seen,
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

    static public function loadMessageById(PDO $conn, $id): ?Message
    {
        $stmt = $conn->prepare('SELECT * FROM MessageMailbox WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedMessage = new Message();
            $loadedMessage->id = $row['id'];
            $loadedMessage->sentBy = $row['sentBy'];
            $loadedMessage->sentTo = $row['sentTo'];
            $loadedMessage->creationDate = $row['creationDate'];
            $loadedMessage->message = $row['message'];
            $loadedMessage->seen = $row['seen'];
            return $loadedMessage;
        }
        return null;
    }

    static public function loadAllMessageSentByUserId(PDO $conn, $userId)
    {
        $ret = [];
        $stmt = $conn->prepare("SELECT * FROM MessageMailbox WHERE sentBy = :sentBy");
        $result = $stmt->execute(['sentBy' => $userId]);
        if ($result !== false && $stmt->rowCount() != 0) {
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($row as $message) {
                $loadedMessage = new Message();
                $loadedMessage->id = $message['id'];
                $loadedMessage->sentBy = $message['sentBy'];
                $loadedMessage->sentTo = $message['sentTo'];
                $loadedMessage->creationDate = $message['creationDate'];
                $loadedMessage->message = $message['message'];
                $loadedMessage->seen = $message['seen'];
                $ret[] = $loadedMessage;
            }
        }
        return $ret;
    }

    static public function loadAllMessageSentToUserId(PDO $conn, $userId)
    {
        $ret = [];
        $stmt = $conn->prepare("SELECT * FROM MessageMailbox WHERE sentTo = :sentTo");
        $result = $stmt->execute(['sentTo' => $userId]);
        if ($result !== false && $stmt->rowCount() != 0) {
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($row as $message) {
                $loadedMessage = new Message();
                $loadedMessage->id = $message['id'];
                $loadedMessage->sentBy = $message['sentBy'];
                $loadedMessage->sentTo = $message['sentTo'];
                $loadedMessage->creationDate = $message['creationDate'];
                $loadedMessage->message = $message['message'];
                $loadedMessage->seen = $message['seen'];
                $ret[] = $loadedMessage;
            }
        }
        return $ret;
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
    public function getSentBy(): int
    {
        return $this->sentBy;
    }

    /**
     * @param int $sentBy
     */
    public function setSentBy(int $sentBy): void
    {
        $this->sentBy = $sentBy;
    }

    /**
     * @return int
     */
    public function getSentTo(): int
    {
        return $this->sentTo;
    }

    /**
     * @param int $sentTo
     */
    public function setSentTo(int $sentTo): void
    {
        $this->sentTo = $sentTo;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return DateTime
     */
    public function getCreationDate(): DateTime
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
     * @return int
     */
    public function getSeen(): int
    {
        return $this->seen;
    }

    /**
     * @param int $seen
     */
    public function setSeen(int $seen): void
    {
        $this->seen = $seen;
    }

}