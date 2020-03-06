<?php
namespace BetterLife\MailBox;


use BetterLife\BetterLife;
use BetterLife\Enum\Enum;
use BetterLife\User\User;
use BetterLife\MailBox\Message;
use BetterLife\System\SystemConstant;
use BetterLife\System\Encryption;

class Conversation {

    const TABLE_NAME = "conversations";
    const TABLE_KEY_COLUMN = "Id";

    private $id;
    private $creator;
    private $recipient;
    private $subject;
    private $views = array();
    private $createTime;
    private $messages = array();


    public function __construct($id) {
        if(empty($id))
            throw new \Exception("{0} is illegal Id!", null, $id);

        $data = BetterLife::GetDB()->where(self::TABLE_KEY_COLUMN, $id)->getOne(self::TABLE_NAME);
        if(empty($data))
            throw new \Exception("Data empty, no message found!");

        $this->id = $data["Id"];
        $this->creator = User::getById($data["CreatorId"]);
        $this->recipient = User::getById($data["RecipientId"]);
        $this->subject = Encryption::Decrypt($data["Subject"]);
        $this->createTime = new \DateTime($data["CreateTime"]);

        if(!empty($data["Views"]))
            foreach (json_decode($data["Views"]) as $view)
                array_push($this->views, $view);


        $messagesDB = BetterLife::GetDB()->orderBy("CreateTime", "ASC")->where("ConversationId", $this->id)->get(Message::TABLE_NAME, null, "Id");

        foreach ($messagesDB as $message)
            array_push($this->messages, new Message($message["Id"]));

    }


    public function newMessage(string $message, int $userId) {
        $dateTime = new \DateTime('now',new \DateTimeZone(SystemConstant::SYSTEM_TIMEZONE));
        $data = [
            "CreatorId" => $userId,
            "Content" => Encryption::Encrypt($message),
            "ConversationId" => $this->id,
            "CreateTime" => $dateTime->format("Y-m-d H:i:s")
        ];

        $this->setView($userId, true);

        BetterLife::GetDB()->insert(Message::TABLE_NAME, $data);
    }

    public function clearViews() {
        BetterLife::GetDB()->where(self::TABLE_KEY_COLUMN, $this->id)->update(self::TABLE_NAME, ["Views" => json_decode(array())]);
    }

    public function setView($userId, $clear = false){
        if($clear)
            $this->views = array();

        if(in_array($userId, $this->views))
           return;

        array_push($this->views, $userId);
        BetterLife::GetDB()->where(self::TABLE_KEY_COLUMN, $this->id)->update(self::TABLE_NAME, ["Views" => json_encode($this->views)]);
    }


    public function checkView(int $userId) {
        return in_array($userId, $this->views) ? true : false;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getCreator(): User {
        return $this->creator;
    }

    /**
     * @return User
     */
    public function getRecipient(): User {
        return $this->recipient;
    }

    /**
     * @return mixed
     */
    public function getSubject() {
        return $this->subject;
    }

    /**
     * @return array
     */
    public function getViews() {
        return $this->views;
    }


    /**
     * @return \DateTime
     */
    public function getCreateTime(): \DateTime {
        return $this->createTime;
    }

    /**
     * @return Message[]
     */
    public function getMessages(): array {
        return $this->messages;
    }



}