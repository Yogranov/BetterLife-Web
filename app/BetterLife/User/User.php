<?php
namespace BetterLife\User;

use BetterLife\BetterLife;
use BetterLife\Mole\Mole;
use BetterLife\Repositories\Address;
use BetterLife\System\EmailsConstant;
use BetterLife\System\Exception;
use BetterLife\System\Services;
use BetterLife\System\SystemConstant;

class User {

    const TABLE_NAME = "users";
    const TABLE_KEY_COLUMN = "Id";


    protected $id;
    protected $email;
    protected $password;
    protected $personId;
    protected $firstName;
    protected $lastName;
    protected $sex;
    protected $phoneNumber;
    protected $BirthDate;
    protected $address;
    protected $roles;
    protected $haveHistory;
    protected $registerTime;
    protected $lastLogin;
    protected $token;

    /**
     * User constructor.
     * @param array $data
     * @throws Exception
     */
    private function __construct(array $data) {
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
        $this->sex = $data["Sex"];
        $this->token = $data["Token"];


        $this->roles = array();
        foreach (json_decode($data["Roles"]) as $role)
           array_push( $this->roles, new Role($role));

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

    public function sendEmail(string $message, string $subject) {
        if (empty($this->email))
            throw new \Exception("Email not exist!");

        $emailObject = BetterLife::GetEmail($subject, $message);
        $emailObject->addAddress($this->email);

        if (!$emailObject->send())
            throw new Exception($emailObject->ErrorInfo);
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
            "Sex" => $this->sex,
            "Token" => $this->token
        );

        $data["Roles"] = array();
        foreach ($this->roles as $role)
            array_push($data["Roles"], $role->getId());


        $data["Roles"] = json_encode($data["Roles"]);

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

    public function checkNewUser() {
        foreach ($this->roles as $role) {
            if($role->getId() == Role::NEW_USER_ID)
                return true;
        }
        return false;
    }

    /**
     * @return User
     */
    public static function GetUserFromSession() {
        try {
            return unserialize($_SESSION[SystemConstant::USER_SESSION_NAME]);

        } catch (\Throwable $e) {
            return Services::flashUser("אירעה שגיאה, מועברים לדף הראשי");
        }
    }


    public function sendResetPasswordEmail(){
        $recoverPassword = Services::GenerateRandomString(64);
        $subject = "בקשה לאיפוס סיסמה";
        $message = EmailsConstant::forgotPassword;


        $forgotLink = "{$this->email}_{$recoverPassword}";
        $forgotLinkEncode = base64_encode($forgotLink);
        Services::setPlaceHolder($message,"forgotLink",$forgotLinkEncode);

        try {
            BetterLife::GetDB()->where(self::TABLE_KEY_COLUMN, $this->id)->update(self::TABLE_NAME, ["RecoverToken" => $recoverPassword]);

            Services::setPlaceHolder($message,"userName",self::getFirstName());
            self::sendEmail($message, $subject);

            Services::flashUser("בקשתך התקבלה, בעוד רגע תקבל הודעה לדואר האלקטרוני על איפוס הסיסמה (עלול להגיע לספאם).");
        } catch (\Throwable $e){
            echo $e;
        }
    }

    public function resetPassword(string $password) {
        if(empty($password))
            throw new \Exception("Password cannot be found");

        BetterLife::GetDB()->where(self::TABLE_KEY_COLUMN, $this->id)->update(["Password" => $password]);
    }



    public function getFullName() {
        return $this->firstName . " " . $this->lastName;
    }


    /**
     * @return Mole[]
     * @throws Exception
     */
    public function getMoles() {
        $moles = array();

        $data = BetterLife::GetDB()->where("UserId", $this->id)->get("moles", null, "Id");


        foreach ($data as $item)
            array_push($moles, Mole::getById($item["Id"]));

        return $moles;
    }


    public static function checkIfUserExist($userId) {
        if(empty($userId))
            throw new \Exception("UserId not found");

        $data = BetterLife::GetDB()->where(self::TABLE_KEY_COLUMN, $userId)->getOne(self::TABLE_NAME, "Id");
        return empty($data) ? false : true;
    }

    public function checkRole($ids) {
        $flag = 1;
        if(is_array($ids)) {
            foreach ($this->getRoles() as $role) {
                foreach ($ids as $toRole) {
                    if($role->getId() == $toRole)
                        $flag = 0;
                }
            }
        } else {
            foreach ($this->getRoles() as $role)
                if($role->getId() == $ids)
                    $flag = 0;
        }
        if ($flag)
            return false;

        return true;
    }

    ////////// Getters & Setters /////////////
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
     * @return Role[]
     */
    public function getRoles() {
        return $this->roles;
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
    public function getRegisterTime() {
        return $this->registerTime;
    }

    /**
     * @return \DateTime
     */
    public function getLastLogin(): \DateTime {
        return $this->lastLogin;
    }

    /**
     * @return mixed
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void {
        $this->token = $token;
    }


    /**
     * @throws \Exception
     */
    public function setLastLogin(){
        $this->lastLogin = new \DateTime('now',new \DateTimeZone(SystemConstant::SYSTEM_TIMEZONE));
    }

}