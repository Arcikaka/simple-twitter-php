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
        $datetime = date_create()->format('Y-m-d H:i:s');
        $this->creationDate = $datetime;
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

            //update id for this new one saved in our db
            //now we knows that this isn't new object

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
        $sql = "SELECT * FROM Tweets";
        $result = $conn->query($sql);
        if ($result !== false && $result->rowCount() != 0) {
            foreach ($result as $row) {
                $loadedTweet = new Tweet();
                $loadedTweet->id = $row['id'];
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
