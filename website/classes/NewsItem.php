<?php


class NewsItem
{
    private int $id;
    private string $header;
    private string $text;
    private DateTimeImmutable $date;
    private string $imagePath;
    private array $keywords;

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

    /**
     * @return string image path
     */
    public function getImagePath(): string
    {
        return $this->imagePath;
    }

    /**
     * @param string $imagePath image path
     * @return NewsItem this object (for chaining)
     */
    public function setImagePath(string $imagePath): NewsItem
    {
        $this->imagePath = $imagePath;
        return $this;
    }

    /**
     * @return array keywords
     */
    public function getKeywords(): array
    {
        return [...$this->keywords];
    }

    /**
     * @param array $keywords
     * @return NewsItem this object (for chaining)
     */
    public function setKeywords(array $keywords): NewsItem
    {
        $this->keywords = [...$keywords];
        return $this;
    }
}