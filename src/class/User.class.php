<?

/**
 * Class User
 */
class User
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $firstName;

    /**
     * @var string
     */
    public $lastName;

    /**
     * @var string
     */
    public $age;

    /**
     * User constructor.
     * @param int $id
     * @param string $username
     * @param string $firstName
     * @param string $lastName
     * @param int $age
     */
    public function __construct($id, $username, $firstName, $lastName, $age)
    {
        $this->id = $id;
        $this->username = $username;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->age = $age;
    }

    /**
     * Return this object encode to json
     */
    public function toJson() {

        return json_encode($this);
    }

    /**
     * @param $json
     * @return null|User
     */
    public static function fromJson($json) {
        $obj = json_decode($json);
        if (isset($obj->id)
            && isset($obj->username)
            && isset($obj->firstName)
            && isset($obj->lastName)
            && isset($obj->firstName)) {

            return new User($obj->id, $obj->username, $obj->firstName, $obj->lastName, $obj->age);
        }

        return null;
    }

}