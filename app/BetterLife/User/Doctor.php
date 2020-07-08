<?php


namespace BetterLife\User;

use BetterLife\BetterLife;
use BetterLife\Mole\MoleDetails;
use BetterLife\Repositories\Address;
use BetterLife\System\Exception;

class Doctor extends User {
    const TABLE_NAME = "doctors";
    const TABLE_KEY_COLUMN = "UserId";

    private $licenseNumber;
    private $imgUrl;
    private $title;
    private $about;


    private function __construct(array $data, array $docData) {

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

        $this->licenseNumber = $docData["LicenseNumber"];
        $this->imgUrl = $docData["UserId"];
        $this->title = $docData["Title"];
        $this->about = $docData["About"];

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

    public static function getById($id) {
        if(empty($id))
            throw new Exception("{0} is illegal Id!", null, $id);

        $userData = BetterLife::GetDB()->where(User::TABLE_KEY_COLUMN, $id)->getOne(User::TABLE_NAME);
        $docData = BetterLife::GetDB()->where(self::TABLE_KEY_COLUMN, $id)->getOne(self::TABLE_NAME);

        if(empty($userData) || empty($docData))
            throw new Exception("Data empty, no user found!");

        return new Doctor($userData, $docData);
    }


    /**
     * @return Doctor[]
     * @throws Exception
     */
    public static function getAllDoctors() {
        $doctors = array();
        $data = "";
        try {
            $data = BetterLife::GetDB()->where("Roles", "%" . Role::DOCTOR_ID . "%", "LIKE")->get(User::TABLE_NAME);
        } catch (\Throwable $e) {
            echo "Error accord, please try again later";
        }
        foreach ($data as $user)
            array_push($doctors, self::getById($user["Id"]));

        return $doctors;
    }

    public function countDiagnosis() {
        return count(BetterLife::GetDB()->where("DoctorId", $this->id)->get(MoleDetails::TABLE_NAME, null, "Id"));
    }

    /**
     * @return MoleDetails
     * @throws Exception
     */
    public function lastMole() {
        $id = BetterLife::GetDB()->where("DoctorId", $this->id)->orderBy("Id", "DESC")->getOne(MoleDetails::TABLE_NAME, "Id")["Id"];
        if ($id != null)
            return new MoleDetails($id);
        else
            throw new \Exception("No data available");
    }

    /**
     * @return mixed
     */
    public function getLicenseNumber() {
        return $this->licenseNumber;
    }

    /**
     * @return mixed
     */
    public function getImgUrl() {
        return $this->imgUrl;
    }

    /**
     * @return mixed
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getAbout() {
        return $this->about;
    }


}