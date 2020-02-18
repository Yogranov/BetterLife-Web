<?php


namespace BetterLife\Article;
use BetterLife\BetterLife;
use BetterLife\System\Services;
use BetterLife\User\User;

class Article {

    const TABLE_NAME = "articles";
    const TABLE_KEY_COLUMN = "Id";

    private $id;
    private $title;
    private $imgUrl;
    private $creator;
    private $content;
    private $isPublish;
    private $createTime;
    private $likes;
    private $views;

    private function __construct(array $data) {
        $this->id = $data["Id"];
        $this->title = $data["Title"];
        $this->imgUrl = $data["ImgUrl"];
        $this->creator = User::getById($data["Creator"]);
        $this->content = $data["Content"];
        $this->isPublish = $data["Publish"] ? true : false;
        $this->createTime = new \DateTime($data["CreateTime"]);
        $this->likes = count(explode(',', $data["Likes"]))-1;
        $this->views = $data["Views"];
    }

    public static function getById(int $id) {
        if(empty($id))
            throw new \Exception("{0} is illegal Id!", null, $id);

        $data = BetterLife::GetDB()->where(self::TABLE_KEY_COLUMN, $id)->getOne(self::TABLE_NAME);
        if(empty($data))
            throw new \Exception("Data empty, no user found!");

        return new Article($data);
    }

    /**
     * @return Article[]
     * @throws \Exception
     */
    public static function getAllActiveArticles() {
        $articles = array();
        $data = "";
        try {
            $data = BetterLife::GetDB()->where("Publish", 1)->get(self::TABLE_NAME);
        } catch (\Throwable $e) {
            echo "Error accord, please try again later";
        }

        foreach ($data as $article)
            array_push($articles, self::getById($article["Id"]));

        return $articles;
    }

    /**
     * @return Article[]
     */
    public static function getAllArticles() {
        $articles = array();
        $data = "";
        try {
            $data = BetterLife::GetDB()->get(self::TABLE_NAME);
        } catch (\Throwable $e) {
            echo "Error accord, please try again later";
        }

        foreach ($data as $article)
            array_push($articles, self::getById($article["Id"]));

        return $articles;
    }

    /**
     * @return ArticleComment[]
     * @throws \BetterLife\System\Exception
     */
    public function getAllComments() {
        $comments = array();
        $data = BetterLife::GetDB()->where("ArticleId", $this->id)->get(ArticleComment::TABLE_NAME, null, "Id");
        foreach ($data as $comment)
            array_push($comments, ArticleComment::getById($comment["Id"]));


        return $comments;
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


    public function increaseViews() {
        BetterLife::GetDB()->where(self::TABLE_KEY_COLUMN, $this->id)->update(self::TABLE_NAME, ["Views" => $this->views+1]);
    }


    public function hideArticle() {
        BetterLife::GetDB()->where(self::TABLE_KEY_COLUMN, $this->id)->update(self::TABLE_NAME, ["Publish" => 0]);
    }

    public function showArticle() {
        BetterLife::GetDB()->where(self::TABLE_KEY_COLUMN, $this->id)->update(self::TABLE_NAME, ["Publish" => 1]);
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
    public function getTitle() {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getImgUrl() {
        return $this->imgUrl;
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
    public function getContent() {
        return htmlspecialchars_decode($this->content);
    }

    /**
     * @return \DateTime
     */
    public function getCreateTime(): \DateTime {
        return $this->createTime;
    }

    /**
     * @return bool
     */
    public function isPublish(): bool {
        return $this->isPublish;
    }

    /**
     * @return mixed
     */
    public function getLikes() {
        return $this->likes;
    }

    /**
     * @return mixed
     */
    public function getViews() {
        return $this->views;
    }





}