<?php

class NewsItem
{
    private int $id;
    private string $header;
    private string $text;
    private DateTimeImmutable $date;

    /**
     * @return int news record id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id news record id
     * @return NewsItem this object (for chaining)
     */
    public function setId(int $id): NewsItem
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string news header
     */
    public function getHeader(): string
    {
        return $this->header;
    }

    /**
     * @param string $header news header
     * @return NewsItem this object (for chaining)
     */
    public function setHeader(string $header): NewsItem
    {
        $this->header = $header;
        return $this;
    }

    /**
     * @return string news content
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text news content
     * @return NewsItem this object (for chaining)
     */
    public function setText(string $text): NewsItem
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return DateTimeImmutable publishing timestamp
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @param DateTimeImmutable $date publishing timestamp
     * @return NewsItem this object (for chaining)
     */
    public function setDate(DateTimeImmutable $date): NewsItem
    {
        $this->date = $date;
        return $this;
    }
}