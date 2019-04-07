<?php

class Tweet
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
     * @var string
     */
    private $tweet;
    /**
     * @var DateTime
     */
    private $creationDate;

    public function __construct()
    {
        $this->id = -1;
        $this->userId = '';
        $this->tweet = '';
        $this->creationDate = '';
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
     * @return string
     */
    public function getTweet(): string
    {
        return $this->tweet;
    }

    /**
     * @param string $tweet
     */
    public function setTweet(string $tweet): void
    {
        $this->tweet = $tweet;

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
        $this->creationDate = new DateTime('now');
    }

    public function saveToDBTweets(PDO $conn)
    {

        if ($this->id === -1) {

            $stmt = $conn->prepare("INSERT INTO Tweets SET tweet = :tweet, userId = :userId, creationDate = :creationDate");

            try {
                $result = $stmt->execute([
                    'tweet' => $this->tweet,
                    'userId' => $this->userId,
                    'creationDate' => $this->creationDate
                ]);

            } catch (PDOException $e) {
                echo 'ERROR: ' . $e->getMessage();
                die;
            }

            //aktualizujemy id  naszego obiektu z -1 na aktualne z bzy danych.
            //Wiadomo wtedy ze to nie jest nowy obiekt

            $this->id = $conn->lastInsertId();
        } else {
            try {
                $stmt = $conn->prepare(
                    "INSERT INTO Tweets SET tweet = :tweet, userId = :userId, creationDate = :creationDate");
                $result = $stmt->execute([
                    'tweet' => $this->tweet,
                    'userId' => $this->userId,
                    'creationDate' => $this->creationDate,
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

    static public function loadTweetByUserId(PDO $conn, $userId): ?Tweet
    {
        $stmt = $conn->prepare('SELECT * FROM Tweets WHERE userId=:userId');
        $result = $stmt->execute(['userId' => $userId]);
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedTweet = new Tweet();
            $loadedTweet->id = $row['id'];
            $loadedTweet->userId = $row['userId'];
            $loadedTweet->tweet = $row['tweet'];
            $loadedTweet->creationDate = $row['creationDate'];
            return $loadedTweet;
        }
        return null;
    }

    static public function loadTweetById(PDO $conn, $id): ?Tweet
    {
        $stmt = $conn->prepare('SELECT * FROM Tweets WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedTweet = new Tweet();
            $loadedTweet->id = $row['id'];
            $loadedTweet->userId = $row['userId'];
            $loadedTweet->tweet = $row['tweet'];
            $loadedTweet->creationDate = $row['creationDate'];
            return $loadedTweet;
        }
        return null;
    }

    static public function loadAllTweets(PDO $conn)
    {
        $ret = [];
        $sql = "SELECT * FROM Tweets JOIN Users U on Tweets.userId = U.id";
        $result = $conn->query($sql);
        $user = new User();
        $username = $user->getUsername();
        if ($result !== false && $result->rowCount() != 0) {
            foreach ($result as $row) {
                $loadedTweet = new Tweet();
                $user = new User();
                $username = $user->getUsername();
                $loadedTweet->id = $row['id'];
                $loadedTweet->username = $username;
                $loadedTweet->userId = $row['userId'];
                $loadedTweet->tweet = $row['tweet'];
                $loadedTweet->creationDate = $row['creationDate'];
                $ret[] = $loadedTweet;
            }
        }
        return $ret;
    }

    static public function loadAllTweetsByUserId(PDO $conn, $userId)
    {
        $ret = [];
        $stmt = $conn->prepare("SELECT * FROM Tweets WHERE userId = :userId");
        $result= $stmt->execute(['userId' => $userId]);
        if ($result !== false && $stmt->rowCount() != 0) {
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($row as $tweet) {
                $loadedTweet = new Tweet();
                $loadedTweet->id = $tweet['id'];
                $loadedTweet->userId = $tweet['userId'];
                $loadedTweet->tweet = $tweet['tweet'];
                $loadedTweet->creationDate = $tweet['creationDate'];
                $ret[] = $loadedTweet;
            }
        }
        return $ret;
    }

    public function delete(PDO $conn)
    {
        if ($this->id != -1) {
            $stmt = $conn->prepare('DELETE FROM Tweets WHERE id=:id');
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

}
