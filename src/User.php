<?php
//object of the class is equal one row in our table
//that's why we have identical variables as set in the table
class User
{
    /**
     * @var int
     */
    private $id; //only getter, no one can set id
    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $hashPass;


    public function __construct()
    {
        $this->id = -1; //we set id to -1 to knows that this is new object. Auto Increment never set id like this
        //the rest we set to empty, because in default they are null
        $this->username = "";
        $this->email = "";
        $this->hashPass = "";
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getHashPass(): string
    {
        return $this->hashPass;
    }

    /**
     * @param string $hashPass
     */
    public function setHashPass(string $hashPass): void
    {
        //hasło hashujemy
        $hashPass = password_hash($hashPass, PASSWORD_BCRYPT);
        $this->hashPass = $hashPass;
    }

    public function saveToDB(PDO $conn)
    {

        //we check if this is new object with id -1

        if ($this->id === -1) {
            //we save record to db
            //here we prepare query
            $stmt = $conn->prepare("INSERT INTO Users SET username = :username, email = :email,hashPass = :hashPass");

            //save to db
            try {
                $result = $stmt->execute([
                    'username' => $this->username,
                    'email' => $this->email,
                    'hashPass' => $this->hashPass
                ]);

            } catch (PDOException $e) {
                echo 'ERROR: ' . $e->getMessage();
                die;
            }

            //now we can update id to new one set in our db
            //now we now, that this isn't new object

            $this->id = $conn->lastInsertId();
        } else {
            try {
                $stmt = $conn->prepare(
                    'UPDATE Users SET username=:username, email=:email,
             hashPass=:hashPass WHERE id=:id');
                $result = $stmt->execute(
                    ['username' => $this->username,
                        'email' => $this->email,
                        'hashPass' => $this->hashPass,
                        'id' => $this->id,
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

    static public function loadUserById(PDO $conn, $id): ?User
    {
        $stmt = $conn->prepare('SELECT * FROM Users WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->hashPass = $row['hashPass'];
            $loadedUser->email = $row['email'];
            return $loadedUser;
        }
        return null;
    }

    static public function loadUserByEmail(PDO $conn, $email): ?User
    {
        $stmt = $conn->prepare('SELECT * FROM Users WHERE email=:email');
        $result = $stmt->execute(['email' => $email]);
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->hashPass = $row['hashPass'];
            $loadedUser->email = $row['email'];
            return $loadedUser;
        }
        return null;
    }

    static public function loadAllUsers(PDO $conn)
    {
        $ret = [];
        $sql = "SELECT * FROM Users";
        $result = $conn->query($sql);
        if ($result !== false && $result->rowCount() != 0) {
            foreach ($result as $row) {
                $loadedUser = new User();
                $loadedUser->id = $row['id'];
                $loadedUser->username = $row['username'];
                $loadedUser->hashPass = $row['hashPass'];
                $loadedUser->email = $row['email'];
                $ret[] = $loadedUser;
            }
        }
        return $ret;
    }

    public function delete(PDO $conn)
    {
        if ($this->id != -1) {
            $stmt = $conn->prepare('DELETE FROM Users WHERE id=:id');
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