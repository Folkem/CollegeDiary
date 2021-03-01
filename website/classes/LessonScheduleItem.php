<?php


class LessonScheduleItem
{
    private int $id;
    private WorkDistributionRecord $discipline;
    private int $weekDay;
    private int $lessonNumber;
    private int $variantNumber;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): LessonScheduleItem
    {
        $this->id = $id;
        return $this;
    }

    public function getDiscipline(): WorkDistributionRecord
    {
        return $this->discipline;
    }

    public function setDiscipline(WorkDistributionRecord $discipline): LessonScheduleItem
    {
        $this->discipline = $discipline;
        return $this;
    }

    public function getWeekDay(): int
    {
        return $this->weekDay;
    }

    public function setWeekDay(int $weekDay): LessonScheduleItem
    {
        // todo: add week day validation (allowed values come only from WeekDay constants)
        $this->weekDay = $weekDay;
        return $this;
    }

    public function getLessonNumber(): int
    {
        return $this->lessonNumber;
    }

    public function setLessonNumber(int $lessonNumber): LessonScheduleItem
    {
        $this->lessonNumber = $lessonNumber;
        return $this;
    }

    public function getVariantNumber(): int
    {
        return $this->variantNumber;
    }

    public function setVariantNumber(int $variantNumber): LessonScheduleItem
    {
        $this->variantNumber = $variantNumber;
        return $this;
    }
}