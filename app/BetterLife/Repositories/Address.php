<?php


namespace BetterLife\Repositories;


use BetterLife\BetterLife;
use BetterLife\System\Exception;

class Address {

    private $address;
    private $city;
    private $cityId;

    public function __construct($address, $cityId) {
        if(empty($address) || empty($cityId))
            throw new Exception("One of the files are empty!");

        $data = BetterLife::GetDB()->where("Id", $cityId)->getOne("cities");
        if(empty($data))
            throw new Exception("no data return from DB!");

        $this->address = $address;
        $this->city = $data["HebrewName"];
        $this->cityId = $cityId;
    }

    /**
     * @return mixed
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * @return mixed
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * @return mixed
     */
    public function getCityId() {
        return $this->cityId;
    }


}