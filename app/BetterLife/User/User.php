<?php
namespace BetterLife\User;


use BetterLife\BetterLife;
use BetterLife\Enum\EUserRoles;
use BetterLife\Repositories\Address;
use BetterLife\System\Exception;
use BetterLife\System\Services;
use BetterLife\System\SystemConstant;

class User {

    const TABLE_NAME = "users";
    const TABLE_KEY_COLUMN = "Id";


    private $id;
    private $email;
    private $password;
    private $personId;
    private $firstName;
    private $lastName;
    private $sex;
    private $phoneNumber;
    private $BirthDate;
    private $address;
    private $role;
    private $haveHistory;
    private $licenceNumber;
    private $registerTime;
    private $lastLogin;

    public function __construct(array $data) {
        $this->id = $data["Id"];
        $this->email = $data["Email"];
        $this->password = $data["Password"];
        $this->personId = $data["PersonId"];
        $this->firstName = $data["FirstName"];
        $this->lastName = $data["LastName"];
        $this->phoneNumber = $data["PhoneNumber"];
        $this->BirthDate = new \DateTime($data["BirthDate"]);
        $this->address = new Address($data["Address"], $data["City"]);
        $this->registerTime = $data["RegisterTime"];
        $this->lastLogin = new \DateTime($data["LastLogin"]);
        $this->licenceNumber = $data["LicenceNumber"];
        $this->sex = $data["Sex"];


        $this->role = array();
        foreach (json_decode($data["Role"]) as $role)
           array_push( $this->role, EUserRoles::search($role));

        if(!is_null($data["HaveHistory"]))
            if($data["HaveHistory"])
                $this->haveHistory = true;
            else
                $this->haveHistory = false;
        else
            $this->haveHistory = null;

    }

    public static function getById(int $id) {
        if(empty($id))
            throw new Exception("{0} is illegal Id!", null, $id);

        $data = BetterLife::GetDB()->where(self::TABLE_KEY_COLUMN, $id)->getOne(self::TABLE_NAME);
        if(empty($data))
            throw new Exception("Data empty, no user found!");

        return new User($data);
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getPersonId() {
        return $this->personId;
    }

    /**
     * @return mixed
     */
    public function getFirstName() {
        return $this->firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName() {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getSex(): string {
        return $this->sex;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber() {
        return $this->phoneNumber;
    }

    /**
     * @return \DateTime
     */
    public function getBirthDate(): \DateTime {
        return $this->BirthDate;
    }

    /**
     * @return Address
     */
    public function getAddress(): Address {
        return $this->address;
    }

    /**
     * @return EUserRoles
     */
    public function getRole(): EUserRoles {
        return $this->role;
    }

    /**
     * @return null
     */
    public function getHaveHistory() {
        return $this->haveHistory;
    }

    /**
     * @return mixed
     */
    public function getLicenceNumber() {
        return $this->licenceNumber;
    }

    /**
     * @return mixed
     */
    public function getRegisterTime() {
        return $this->registerTime;
    }

    /**
     * @return \DateTime
     */
    public function getLastLogin(): \DateTime {
        return $this->lastLogin;
    }

    public function setLastLogin(){
        $this->lastLogin = new \DateTime('now',new \DateTimeZone(SystemConstant::SYSTEM_TIMEZONE));
    }

    public function save() {
        $data = array(
            "Email" => $this->email,
            "FirstName" => $this->firstName,
            "LastName" => $this->lastName,
            "PhoneNumber" => $this->phoneNumber,
            "Address" => $this->address->getAddress(),
            "City" => $this->address->getCityId(),
            "LastLogin" => $this->lastLogin->format("Y-m-d H:i:s"),
            "LicenceNumber" => $this->licenceNumber,
            "Sex" => $this->sex
        );
        $data["Role"] = array();
        foreach ($this->role as $key => $item)
            array_push($data["Role"], $key);

        if(is_null($this->haveHistory))
            $data["HaveHistory"] = null;
        else if($this->haveHistory)
            $data["HaveHistory"] = 1;
        else
            $data["HaveHistory"] = 0;


        try {
            BetterLife::GetDB()->where(self::TABLE_KEY_COLUMN, $this->id)->update(self::TABLE_NAME, $data);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }


    /**
     * @return User
     */
    public static function GetUserFromSession() {
        return unserialize($_SESSION[SystemConstant::USER_SESSION_NAME]);
    }
}