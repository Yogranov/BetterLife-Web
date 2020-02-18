<?php
namespace BetterLife\User;

use BetterLife\BetterLife;

class Role {
    const TABLE_NAME = "roles";
    const TABLE_KEY_COLUMN = "Id";

    const NEW_USER_ID = 1;
    const PATIENT_ID = 2;
    const DOCTOR_ID = 3;
    const CONTENT_WRITER = 4;
    const ADMIN_ID = 5;


    private $id;
    private $name;
    private $description;


    public function __construct(int $roleId) {
        if(empty($roleId))
            throw new \Exception("{0} is illegal Id!", null, $roleId);

        $data = BetterLife::GetDB()->where(self::TABLE_KEY_COLUMN, $roleId)->getOne(self::TABLE_NAME);
        if(empty($data))
            throw new \Exception("Data empty, no user found!");

        $this->id = $data["Id"];
        $this->name = $data["Name"];
        $this->description = $data["Description"];
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
    public function getName() {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }


}