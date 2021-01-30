<?php


class NewsComment
{
    private int $id;
    private int $newsId;
    private User $user;
    private DateTimeImmutable $postDate;
    private string $comment;

    /**
     * @return int id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id news comment's id
     * @return NewsComment this object (for chaining)
     */
    public function setId(int $id): NewsComment
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getNewsId(): int
    {
        return $this->newsId;
    }

    /**
     * @param int $newsId
     * @return NewsComment
     */
    public function setNewsId(int $newsId): NewsComment
    {
        $this->newsId = $newsId;
        return $this;
    }

    /**
     * @return User user, that posted this comment
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user user, that posted this comment
     * @return NewsComment this object (for chaining)
     */
    public function setUser(User $user): NewsComment
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return DateTimeImmutable date, on which this comment was published
     */
    public function getPublishDate(): DateTimeImmutable
    {
        return $this->postDate;
    }

    /**
     * @param DateTimeImmutable $postDate date, on which this comment was published
     * @return NewsComment this object (for chaining)
     */
    public function setPublishDate(DateTimeImmutable $postDate): NewsComment
    {
        $this->postDate = $postDate;
        return $this;
    }

    /**
     * @return string comment
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param string $comment comment
     * @return NewsComment this object (for chaining)
     */
    public function setComment(string $comment): NewsComment
    {
        $this->comment = $comment;
        return $this;
    }
}