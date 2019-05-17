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
    private $sendBy;
    /**
     * @var int
     */
    private $sendTo;
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
        $this->sendBy = '';
        $this->sendTo = '';
        $this->creationDate = '';
        $this->message = '';
        $this->seen = '';
    }

    public function saveToDB(PDO $conn)
    {

        if ($this->id === -1) {

            $stmt = $conn->prepare("INSERT INTO MessageMailbox SET message=:message, creationDate =:creationDate, sendBy =:sentBy, sendTo=:sentTo, seen =:seen");

            try {
                $result = $stmt->execute([
                    'message' => $this->message,
                    'creationDate' => $this->creationDate,
                    'sentBy' => $this->sendBy,
                    'sentTo' => $this->sendTo,
                    'seen' => $this->seen
                ]);

            } catch (PDOException $e) {
                echo 'ERROR: ' . $e->getMessage();
                die;
            }
            $this->id = $conn->lastInsertId();
        } else {
            try {
                $stmt = $conn->prepare(
                    'UPDATE MessageMailbox SET message=:message, creationDate =:creationDate, sendBy =:sentBy, sendTo=:sentTo, seen=:seen WHERE id=:id');
                $result = $stmt->execute([
                    'message' => $this->message,
                    'creationDate' => $this->creationDate,
                    'sentBy' => $this->sendBy,
                    'sentTo' => $this->sendTo,
                    'seen' => $this->seen,
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
            $loadedMessage->sendBy = $row['sendBy'];
            $loadedMessage->sendTo = $row['sendTo'];
            $loadedMessage->creationDate = $row['creationDate'];
            $loadedMessage->message = $row['message'];
            $loadedMessage->seen = $row['seen'];
            return $loadedMessage;
        }
        return null;
    }

    static public function loadAllMessageSendByUserId(PDO $conn, $userId)
    {
        $ret = [];
        $stmt = $conn->prepare("SELECT * FROM MessageMailbox WHERE sendBy = :sentBy");
        $result = $stmt->execute(['sentBy' => $userId]);
        if ($result !== false && $stmt->rowCount() != 0) {
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($row as $message) {
                $loadedMessage = new Message();
                $loadedMessage->id = $message['id'];
                $loadedMessage->sendBy = $message['sendBy'];
                $loadedMessage->sendTo = $message['sendTo'];
                $loadedMessage->creationDate = $message['creationDate'];
                $loadedMessage->message = $message['message'];
                $loadedMessage->seen = $message['seen'];
                $ret[] = $loadedMessage;
            }
        }
        return $ret;
    }

    static public function loadAllMessageSendToUserId(PDO $conn, $userId)
    {
        $ret = [];
        $stmt = $conn->prepare("SELECT * FROM MessageMailbox WHERE sendTo = :sentTo");
        $result = $stmt->execute(['sentTo' => $userId]);
        if ($result !== false && $stmt->rowCount() != 0) {
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($row as $message) {
                $loadedMessage = new Message();
                $loadedMessage->id = $message['id'];
                $loadedMessage->sendBy = $message['sendBy'];
                $loadedMessage->sendTo = $message['sendTo'];
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
    public function getSendBy(): int
    {
        return $this->sendBy;
    }

    /**
     * @param int $sendBy
     */
    public function setSendBy(int $sendBy): void
    {
        $this->sendBy = $sendBy;
    }

    /**
     * @return int
     */
    public function getSendTo(): int
    {
        return $this->sendTo;
    }

    /**
     * @param int $sendTo
     */
    public function setSendTo(int $sendTo): void
    {
        $this->sendTo = $sendTo;
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