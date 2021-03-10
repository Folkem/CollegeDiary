<?php


class Lesson
{
    private int $id;
    private WorkDistributionRecord $discipline;
    private string $comment;
    private DateTimeImmutable $date;
    private int $type;
    
    public function getId(): int
    {
        return $this->id;
    }
    
    public function setId(int $id): Lesson
    {
        $this->id = $id;
        return $this;
    }
    
    public function getDiscipline(): WorkDistributionRecord
    {
        return $this->discipline;
    }
    
    public function setDiscipline(WorkDistributionRecord $discipline): Lesson
    {
        $this->discipline = $discipline;
        return $this;
    }
    
    public function getComment(): string
    {
        return $this->comment;
    }
    
    public function setComment(string $comment): Lesson
    {
        $this->comment = $comment;
        return $this;
    }
    
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }
    
    public function setDate(DateTimeImmutable $date): Lesson
    {
        $this->date = $date;
        return $this;
    }
    
    public function getType(): int
    {
        return $this->type;
    }
    
    public function setType(int $type): Lesson
    {
        $this->type = $type;
        return $this;
    }
}