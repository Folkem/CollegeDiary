<?php


class Homework
{
    private int $id;
    private WorkDistributionRecord $discipline;
    private string $text;
    private DateTimeImmutable $createdDate;
    private DateTimeImmutable $scheduledDate;
    
    public function getId(): int
    {
        return $this->id;
    }
    
    public function setId(int $id): Homework
    {
        $this->id = $id;
        return $this;
    }
    
    public function getDiscipline(): WorkDistributionRecord
    {
        return $this->discipline;
    }
    
    public function setDiscipline(WorkDistributionRecord $discipline): Homework
    {
        $this->discipline = $discipline;
        return $this;
    }
    
    public function getText(): string
    {
        return $this->text;
    }
    
    public function setText(string $text): Homework
    {
        $this->text = $text;
        return $this;
    }
    
    public function getCreatedDate(): DateTimeImmutable
    {
        return $this->createdDate;
    }
    
    public function setCreatedDate(DateTimeImmutable $createdDate): Homework
    {
        $this->createdDate = $createdDate;
        return $this;
    }
    
    public function getScheduledDate(): DateTimeImmutable
    {
        return $this->scheduledDate;
    }
    
    public function setScheduledDate(DateTimeImmutable $scheduledDate): Homework
    {
        $this->scheduledDate = $scheduledDate;
        return $this;
    }
}