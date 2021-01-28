<?php


class Notification
{
    private int $id;
    private int $userId;
    private string $comment;
    private string $link;
    private bool $read;
    private DateTimeImmutable $date;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Notification
     */
    public function setId(int $id): Notification
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return Notification
     */
    public function setUserId(int $userId): Notification
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     * @return Notification
     */
    public function setComment(string $comment): Notification
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @param string $link
     * @return Notification
     */
    public function setLink(string $link): Notification
    {
        $this->link = $link;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRead(): bool
    {
        return $this->read;
    }

    /**
     * @param bool $read
     * @return Notification
     */
    public function setRead(bool $read): Notification
    {
        $this->read = $read;
        return $this;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @param DateTimeImmutable $date
     * @return Notification
     */
    public function setDate(DateTimeImmutable $date): Notification
    {
        $this->date = $date;
        return $this;
    }
}