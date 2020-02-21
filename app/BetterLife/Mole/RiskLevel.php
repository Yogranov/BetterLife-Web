<?php

namespace BetterLife\Mole;

use BetterLife\BetterLife;

class RiskLevel {
    const TABLE_NAME = "riskLevel";
    const TABLE_KEY_COLUMN = "Id";

    private $id;
    private $name;

    public function __construct(int $riskId) {
        if(empty($riskId))
            throw new \Exception("{0} is illegal Id!", null, $riskId);

        $data = BetterLife::GetDB()->where(self::TABLE_KEY_COLUMN, $riskId)->getOne(self::TABLE_NAME);
        if(empty($data))
            throw new \Exception("Data empty, no user found!");

        $this->id = $data["Id"];
        $this->name = $data["Name"];
    }

    /**
     * @return RiskLevel[]
     * @throws \Exception
     */
    public static function getAll() {
        $risks = array();
        $data = "";
        try {
            $data = BetterLife::GetDB()->get(self::TABLE_NAME);
        } catch (\Throwable $e) {
            echo "Error accord, please try again later";
        }
        unset($data[0]);
        foreach ($data as $risk)
            array_push($risks, new RiskLevel($risk["Id"]));

        return $risks;

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