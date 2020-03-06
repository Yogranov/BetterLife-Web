<?php


namespace BetterLife\MailBox;


use BetterLife\BetterLife;
use BetterLife\User\User;
use BetterLife\System\Encryption;

class Message {

    const TABLE_NAME = "conMessages";
    const TABLE_KEY_COLUMN = "Id";

    private $id;
    private $conversationId;
    private $creator;
    private $content;
    private $createTime;

    public function __construct($id) {
        if(empty($id))
            throw new \Exception("{0} is illegal Id!", null, $id);

        $data = BetterLife::GetDB()->where(self::TABLE_KEY_COLUMN, $id)->getOne(self::TABLE_NAME);
        if(empty($data))
            throw new \Exception("Data empty, no message found!");

        $this->id = $data["Id"];
        $this->conversationId = $data["ConversationId"];
        $this->creator = User::getById($data["CreatorId"]);
        $this->content = Encryption::Decrypt($data["Content"]);
        $this->createTime = new \DateTime($data["CreateTime"]);

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
    public function getConversationId() {
        return $this->conversationId;
    }

    /**
     * @return User
     */
    public function getCreator() {
        return $this->creator;
    }

    /**
     * @return mixed
     */
    public function getContent() {
        return $this->content;
    }


    /**
     * @return \DateTime
     */
    public function getCreateTime(): \DateTime {
        return $this->createTime;
    }


}