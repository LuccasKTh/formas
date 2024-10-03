<?php

abstract class Shape
{
    private int $id;
    private string|int $color;
    private $image;
    private ?Measure $measure;

    public function __construct(
        $id,
        $color, 
        $image, 
        Measure $measure
    ) {
        $this->setId($id);
        $this->setColor($color);
        $this->setImage($image);
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
    
    public function setColor($newColor)
    {
        $this->color = $newColor;
    }

    public function setImage($newImage)
    {
        $this->image = $newImage;
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
    
    public function getImage()
    {
        return $this->image; 
    }

    public function getColor()
    {
        return $this->color;    
    }

    public function getMeasure()
    {
        return $this->measure;    
    }

    public function controlImage($class)
    {
        $imageName = 0;
        if ($this->getId() != 0) {
            $square = $class::show($this->getId());
            $imageName = $square->getImage();
            unlink('../../../Storage/img/'.$imageName);
        }

        $color = 0;
        if ($this->getImage()['size']) {
            $image = $this->getImage();
            $pathInfo = pathinfo($image['name']);
            $extension = $pathInfo['extension'];
            $imageName = $imageName ? $imageName : time().'.'.$extension;

            if (!file_exists('../../../Storage/img/')) {
                mkdir('../../../Storage/img/', 0777, true);
            }

            $path = '../../../Storage/img/'.$imageName;

            move_uploaded_file($image['tmp_name'], $path);
        } else {
            $color = $this->getColor();
            unlink('../../../Storage/img/'.$imageName);
            $imageName = 0;
        }

        return[
            ':color' => $color,
            ':image' => $imageName,
            ':id_measure' => $this->getMeasure()->getId()
        ];
    }

    // public function draw()
    // {
    //     $this->getBackgroundType()
    //         ? $type = "background-image: url(../../../Storage/img/".$this->getBackground().");
    //                    background-size: 100% 100%;'>"
    //         : $type = "background-color: ".$this->getColor()."'>";

    //     return "<div style='
    //                 width: ".$this->getHeight().$this->getMeasure()->getMeasurement()."; 
    //                 height: ".$this->getHeight().$this->getMeasure()->getMeasurement().";
    //                 $type
    //             </div>";    
    // }
}