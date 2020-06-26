<?php


namespace BetterLife\Mole;


use BetterLife\BetterLife;
use BetterLife\System\Services;
use BetterLife\User\Doctor;
use BetterLife\User\User;

class MoleDetails {

    const TABLE_NAME = "moleDetails";
    const TABLE_KEY_COLUMN = "Id";

    private $id;
    private $moleId;
    private $imgUrl;
    private $imgFigureUrl = NULL;
    private $imgSurfaceUrl = NULL;
    private $size;
    private $color;
    private $benignPred = NULL;
    private $malignantPred = NULL;
    private $createTime;
    private $doctorId = NULL;
    private $diagnosis = NULL;
    private $riskLevel = 0;
    private $diagnosisCreateTime = NULL;


    public function __construct($detailId) {
        if(empty($detailId))
            throw new \Exception("{0} is illegal Id!", null, $detailId);

        $data = BetterLife::GetDB()->where(self::TABLE_KEY_COLUMN, $detailId)->getOne(self::TABLE_NAME);
        if(empty($data))
            throw new \Exception("Data empty, no user found!");

        $this->id = $data["Id"];
        $this->moleId = $data["MoleId"];
        $this->imgUrl = $data["ImgUrl"];
        $this->imgFigureUrl = $data["ImgFigureUrl"];
        $this->imgSurfaceUrl = $data["ImgSurfaceUrl"];
        $this->size = $data["Size"];
        $this->color = $data["Color"];
        $this->benignPred = $data["BenignPred"];
        $this->malignantPred = $data["MalignantPred"];
        $this->createTime = new \DateTime($data["CreateTime"]);
        $this->doctorId = $data["DoctorId"];
        $this->riskLevel = new RiskLevel($data["RiskLevel"]);
        $this->diagnosis = $data["Diagnosis"];

        if($data["DiagnosisCreateTime"] != NULL)
            $this->diagnosisCreateTime = new \DateTime($data["DiagnosisCreateTime"]);

    }

    /**
     * @param $orderFiled
     * @param $orderDirection
     * @return MoleDetails[]
     * @throws \Exception
     */
    public static function getAllUncheckMole($orderFiled, $orderDirection) {
        $moles = array();
        $data = "";
        try {
            $data = BetterLife::GetDB()->orderBy($orderFiled, $orderDirection)->where("DoctorId", null, "IS")->get(self::TABLE_NAME);

        } catch (\Throwable $e) {
            echo "Error accord, please try again later";
        }

        foreach ($data as $moleDetail)
            array_push($moles, new MoleDetails($moleDetail["Id"]));

        return $moles;
    }

    public function getMoleLocation() {
        return BetterLife::GetDB()->where("Id", $this->moleId)->getOne(Mole::TABLE_NAME, "Location")["Location"];
    }

    /**
     * @return User
     * @throws \BetterLife\System\Exception
     */
    public function getPatientObj(){
        $userId = BetterLife::GetDB()->where("Id", $this->moleId)->getOne(Mole::TABLE_NAME, "UserId")["UserId"];
        return User::getById($userId);
    }
    ////// Getters ////////

    /**
     * @return mixed
     */
    public function getId() : int {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getMoleId() :int {
        return $this->moleId;
    }

    /**
     * @return mixed
     */
    public function getImgUrl() : string {
        return $this->imgUrl;
    }

    /**
     * @return mixed
     */
    public function getImgFigureUrl() :string  {
        return $this->imgFigureUrl;
    }

    /**
     * @return mixed
     */
    public function getImgSurfaceUrl() : string {
        return $this->imgSurfaceUrl;
    }

    /**
     * @return mixed
     */
    public function getSize() {
        return $this->size;
    }

    /**
     * @return mixed
     */
    public function getColor() : string {
        return $this->color;
    }

    /**
     * @return int
     */
    public function getBenignPred() : int {
        return $this->benignPred * 100;
    }

    /**
     * @return int
     */
    public function getMalignantPred() : int {
        return $this->malignantPred * 100;
    }

    /**
     * @return \DateTime
     */
    public function getCreateTime(): \DateTime {
        return $this->createTime;
    }

    /**
     * @return mixed
     */
    public function getDoctorId() {
        return $this->doctorId;
    }

    /**
     * @return mixed
     */
    public function getDiagnosis() {
        return $this->diagnosis;
    }

    /**
     * @return RiskLevel
     */
    public function getRiskLevel() : RiskLevel{
        return $this->riskLevel;
    }

    /**
     * @return \DateTime
     */
    public function getDiagnosisCreateTime() {
        return $this->diagnosisCreateTime;
    }

    public function getDoctor() {
        return Doctor::getById($this->doctorId);
    }

}