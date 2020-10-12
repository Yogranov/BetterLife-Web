<?php

namespace BetterLife\Mole;

use BetterLife\BetterLife;
use BetterLife\System\Exception;
use BetterLife\System\Services;
use BetterLife\User\User;

class Mole {

    const TABLE_NAME = "moles";
    const TABLE_KEY_COLUMN = "Id";

    private $id;
    private $userId;
    private $location;
    private $removed;
    private $createTime;
    private $details = array();


    public function __construct($data) {
        $this->id = $data["Id"];
        $this->userId = $data["UserId"];
        $this->location = $data["Location"];
        $this->removed = $data["Removed"];
        $this->createTime = new \DateTime($data["CreateTime"]);

        $dbDetails = BetterLife::GetDB()->where("MoleId", $this->id)->get("moleDetails");

        foreach ($dbDetails as $detail)
            array_push($this->details, new MoleDetails($detail["Id"]));

    }

    public static function getById(int $id) {
        if(empty($id))
            throw new Exception("{0} is illegal Id!", null, $id);

        $data = BetterLife::GetDB()->where(self::TABLE_KEY_COLUMN, $id)->getOne(self::TABLE_NAME);
        if(empty($data))
            throw new Exception("Data empty, no user found!");

        return new Mole($data);
    }

    public function remove() {
        BetterLife::GetDB()->where(self::TABLE_KEY_COLUMN, $this->id)->update(self::TABLE_NAME, ["Removed" => 1]);
    }

    ////// Getters //////
    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * @return mixed
     */
    public function getLocation() {
        return $this->location;
    }

    /**
     * @return mixed
     */
    public function getRemoved(){
        return $this->removed;
    }

    /**
     * @return \DateTime
     */
    public function getCreateTime(): \DateTime {
        return $this->createTime;
    }

    /**
     * @return MoleDetails[]
     */
    public function getDetails() {
        return $this->details;
    }


    /**
     * @return MoleDetails
     */
    public function getLastDetails() {
        return end($this->details);
    }

    public function convertToJson() {
        $tmp = [
            "Id" => $this->id,
            "Location" => $this->location,
            "CreateTime" => $this->createTime->format('Y-m-d H:i'),
            "details" => []
        ];

        foreach (array_reverse($this->details) as $detail) {
            $detailsTmp = [
                "imgUrl" => $detail->getImgUrl(),
                "size" => $detail->getSize(),
                "color" => $detail->getColor(),
                "benignPred" => $detail->getBenignPred(),
                "malignantPred" => $detail->getMalignantPred(),
                "createTime" => $detail->getCreateTime()->format("Y-m-d H:i"),
            ];

            try {
                $detailsTmp +=[
                    "doctor" => $detail->getDoctor()->getFullName(),
                    "diagnosis" => $detail->getDiagnosis(),
                    "riskLevel" => $detail->getRiskLevel()->getName(),
                    "diagnosisCreateTime" => $detail->getDiagnosisCreateTime()->format("Y-m-d H:i")
                ];
            } catch (Exception $e){}


            array_push($tmp["details"], $detailsTmp);

        }

        $json = json_encode($tmp);
        return $json;

    }

}