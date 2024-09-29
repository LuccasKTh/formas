<?php

abstract class Shape
{
    private int $id;
    private int $backgroundType;
    private string $color;
    private $background;
    private ?Measure $measure;

    public function __construct(
        $id,
        $backgroundType, 
        $background, 
        $color, 
        Measure $measure
    ) {
        $this->setId($id);
        $this->setBackgroundType($backgroundType);
        $this->setBackground($background);
        $this->setColor($color);
        $this->setMeasure($measure);
    }

    public function setId($newId)
    {
        if ($newId < 0) {
            throw new Exception("Error: Id inválido!");
        } else {
            $this->id = $newId;
        }
    }

    public function setBackgroundType($newBackgroundType)
    {
        if ($newBackgroundType != true && $newBackgroundType != false) {
            throw new Exception("Error: Tipo de background inválido");
        } else {
            $this->backgroundType = $newBackgroundType;
        }
    }
    
    public function setColor($newColor)
    {
        $this->color = $newColor;
    }

    public function setBackground($newBackground)
    {
        $this->background = $newBackground;
    }

    public function setMeasure($newMeasure)
    {
        if ($newMeasure == null) {
            throw new Exception("Error: Medida inválida!");
        } else {
            $this->measure = $newMeasure;
        }
    }

    public function getId()
    {
        return $this->id;    
    }

    public function getBackgroundType()
    {
        return $this->backgroundType;
    }
    
    public function getBackground()
    {
        return $this->background; 
    }

    public function getColor()
    {
        return $this->color;    
    }

    public function getMeasure()
    {
        return $this->measure;    
    }
}