<?php 

abstract class Triangle extends Shape 
{
    private int $leftSide;
    private int $rightSide;
    private int $bottomSide;

    public function __construct($id, $color, $image, Measure $measure, $leftSide, $rightSide, $bottomSide)
    {   
        parent::__construct($id, $color, $image, $measure);
        $this->setleftSide($leftSide);
        $this->setRightSide($rightSide);
        $this->setBottomSide($bottomSide);
    }

    public function setleftSide($newLeftSide)
    {
        $this->leftSide = $newLeftSide;
    }

    public function setRightSide($newRightSide)
    {
        $this->rightSide = $newRightSide;
    }

    public function setBottomSide($newBottomSide)
    {
        $this->bottomSide = $newBottomSide;
    }

    public function getLeftSide()
    {
        return $this->leftSide;    
    }

    public function getRightSide()
    {
        return $this->rightSide;    
    }

    public function getBottomSide()
    {
        return $this->bottomSide;    
    }
}