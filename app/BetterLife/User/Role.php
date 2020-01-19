<?php


namespace BetterLife\User;


use BetterLife\BetterLife;
use BetterLife\System\Exception;

class Role {
    const TABLE_NAME = "roles";
    const TABLE_KEY_COLUMN = "Id";

    private $id;
    private $name;
    private $description;


    public function __construct(int $roleId) {
        if(empty($roleId))
            throw new Exception("{0} is illegal Id!", null, $roleId);

        $data = BetterLife::GetDB()->where(self::TABLE_KEY_COLUMN, $roleId)->getOne(self::TABLE_NAME);
        if(empty($data))
            throw new Exception("Data empty, no user found!");

        $this->id = $data["Id"];
        $this->name = $data["Name"];
        $this->description = $data["Description"];
    }

}