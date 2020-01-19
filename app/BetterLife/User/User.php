<?php
namespace BetterLife\User;


use BetterLife\BetterLife;
use BetterLife\Repositories\Address;
use BetterLife\System\Exception;

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
        $this->role = new Role($data["Role"]);
        $this->registerTime = $data["RegisterTime"];
        $this->lastLogin = new \DateTime($data["LastLogin"]);
        $this->licenceNumber = $data["LicenceNumber"];

        if($data["Sex"])
            $this->sex = "נקבה";
        else
            $this->sex = "זכר";

        if(!is_null($data["HaveHistory"]))
            if($data["HaveHistory"])
                $this->haveHistory = true;
            else
                $this->haveHistory = false;
        else
            $data["HaveHistory"] = null;

    }

    public static function getById(int $id) {
        if(empty($id))
            throw new Exception("{0} is illegal Id!", null, $id);

        $data = BetterLife::GetDB()->where(self::TABLE_KEY_COLUMN, $id)->getOne(self::TABLE_NAME);
        if(empty($data))
            throw new Exception("Data empty, no user found!");

        return new User($data);
    }

}