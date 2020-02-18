<?php


namespace BetterLife\Article;


use BetterLife\BetterLife;
use BetterLife\System\Services;
use BetterLife\User\User;

class ArticleComment {
    const TABLE_NAME = "articleComms";
    const TABLE_KEY_COLUMN = "Id";

    private $id;
    private $articleId;
    private $content;
    private $creator;
    private $likes;
    private $createTime;

    private function __construct(array $data) {
        $this->id = $data["Id"];
        $this->articleId = $data["ArticleId"];
        $this->content = $data["Content"];
        $this->creator = User::getById($data["Creator"]);
        $this->likes = count(explode(',', $data["Likes"]))-1;
        $this->createTime = new \DateTime($data["CreateTime"]);
    }

    public static function getById(int $id) {
        if(empty($id))
            throw new \Exception("{0} is illegal Id!", null, $id);

        $data = BetterLife::GetDB()->where(self::TABLE_KEY_COLUMN, $id)->getOne(self::TABLE_NAME);
        if(empty($data))
            throw new \Exception("Data empty, no user found!");

        return new ArticleComment($data);
    }

    public function addOrRemoveLike(string $userId) {
        if(empty($userId))
            throw new \Exception("UserId not found");

        if(!User::checkIfUserExist($userId))
            throw new \Exception("User doesnt exist");

        $data = BetterLife::GetDB()->where(self::TABLE_KEY_COLUMN, $this->id)->getOne(self::TABLE_NAME, "Likes")["Likes"];

        $data = explode(',', $data);

        if(!in_array($userId, $data))
            array_push($data, $userId);
        else
            unset($data[array_search($userId, $data)]);

        try {
            $this->likes = count($data)-1;
            $data = implode(',', $data);
            BetterLife::GetDB()->where(self::TABLE_KEY_COLUMN, $this->id)->update(self::TABLE_NAME, ["Likes" => $data]);
        } catch (\Throwable $e) {
            echo "Error accord, please try again later";
        }

        return $this->likes;

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
    public function getArticleId() {
        return $this->articleId;
    }

    /**
     * @return mixed
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * @return User
     */
    public function getCreator(): User {
        return $this->creator;
    }

    /**
     * @return mixed
     */
    public function getLikes() {
        return $this->likes;
    }

    /**
     * @return \DateTime
     */
    public function getCreateTime(): \DateTime {
        return $this->createTime;
    }








}