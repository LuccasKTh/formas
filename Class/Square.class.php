<?php

require_once("../../../Class/Measure.class.php");
require_once("../Class/Shape.class.php");

class Square extends Shape
{
    private int $height;

    public function __construct($id, $height, $backgroundType, $background, $color, Measure $measure) {
        parent::__construct($id, $backgroundType, $background, $color, $measure);
        $this->setHeight($height);
    }


    public function setHeight($newHeight)
    {
        if ($newHeight < 0) {
            throw new Exception("Error: Altura inválida!");
        } else {
            $this->height = $newHeight;
        }
    }
    
    public function getHeight()
    {
        return $this->height;    
    }

    public function store()
    {
        $sql = 'INSERT INTO square (height, backgroundType, background, color, id_measure) VALUES (:height, :backgroundType, :background, :color, :id_measure)';

        if ($this->getBackgroundType()) {
            if ($this->getBackground()['size']) {
                $background = $this->getBackground();
                $pathinfo = pathinfo($background['name']);
                $extension = $pathinfo['extension'];
                $finalName = time() . '.' . $extension;
        
                if (!file_exists('Storage/img')) {
                    mkdir($_SERVER['DOCUMENT_ROOT'].'/Storage/img', 0777, true);
                }
        
                $absolutePath = $_SERVER['DOCUMENT_ROOT'] . '/Storage/img/' . $finalName;
        
                if (move_uploaded_file($background['tmp_name'], $absolutePath)) {
                    $params = [
                        ':height' => $this->getHeight(),
                        ':backgroundType' => $this->getBackgroundType(),
                        ':background' => $finalName,
                        ':color' => $this->getColor(),
                        ':id_measure' => $this->getMeasure()->getId()
                    ];
            
                    return Database::executar($sql, $params);
                } else {
                    return false;
                }
            }
        }

        $params = [
            ':height' => $this->getHeight(),
            ':backgroundType' => $this->getBackgroundType(),
            ':background' => $this->getBackground(),
            ':color' => $this->getColor(),
            ':id_measure' => $this->getMeasure()->getId()
        ];

        return Database::executar($sql, $params);
    }

    public function update()
    {
        $sql = 'UPDATE square SET height = :height, backgroundType = :backgroundType, background = :background, color = :color, id_measure = :id_measure WHERE id = :id';

        $square = Square::show($this->getId());
        $background = $square->getBackground();

        if ($this->getBackgroundType()) {
            if ($this->getBackground()['size']) {
                if ($background) {
                    unlink($_SERVER['DOCUMENT_ROOT'] . "/Storage/img/$background");
                }
                $newBackground = $this->getBackground();
                $pathinfo = pathinfo($newBackground['name']);
                $extension = $pathinfo['extension'];

                if ($square->getBackground()) {
                    $time = explode('.', $background)[0];
                    $finalName = $time . '.' . $extension;
                } else {
                    $finalName = time() . '.' . $extension;
                }

                if (!file_exists('Storage/img')) {
                    mkdir($_SERVER['DOCUMENT_ROOT'].'/Storage/img', 0777, true);
                }
        
                $absolutePath = $_SERVER['DOCUMENT_ROOT'] . '/Storage/img/' . $finalName;
        
                if (move_uploaded_file($newBackground['tmp_name'], $absolutePath)) {
                    $params = [
                        ':id' => $this->getId(),
                        ':height' => $this->getHeight(),
                        ':backgroundType' => $this->getBackgroundType(),
                        ':background' => $finalName,
                        ':color' => $this->getColor(),
                        ':id_measure' => $this->getMeasure()->getId()
                    ];
                    return Database::executar($sql, $params);
                } else {
                    return false;
                }
            }
        }

        $params = [
            ':id' => $this->getId(),
            ':height' => $this->getHeight(),
            ':backgroundType' => $this->getBackgroundType(),
            ':background' => $background,
            ':color' => $this->getColor(),
            ':id_measure' => $this->getMeasure()->getId()
        ];

        return Database::executar($sql, $params);
    }

    public function destroy()
    {
        $sql = 'DELETE FROM square WHERE id = :id';

        unlink($_SERVER['DOCUMENT_ROOT'] . "/Storage/img/{$this->getBackground()}");
        
        $params = [':id' => $this->getId()];

        return Database::executar($sql, $params);
    }

    public static function index($tipo, $busca)
    {
        $sql = "SELECT * FROM square";
        if ($tipo > 0)
            switch ($tipo) {
                case 1:
                    $sql .= " WHERE id = :busca";
                    break;
                case 2:
                    $sql .= " WHERE color LIKE :busca";
                    $busca = "%{$busca}%";
                    break;
                case 3:
                    $sql .= " WHERE height LIKE :busca";
                    $busca = "%{$busca}%";
                    break;
                case 4:
                    $sql .= " INNER JOIN measure ON (square.id_measure = measure.id) WHERE measurement LIKE :busca";
                    $busca = "%{$busca}%";
                    break;
            }

        $params = [];
        if ($tipo > 0)
            $params = [':busca' => $busca];

        $comando = Database::executar($sql, $params);

        $squares = [];
        while ($registro = $comando->fetch()) {
            $measure = Measure::show($registro['id_measure']);
            $square = new Square(
                        $registro['id'], 
                        $registro['height'], 
                        $registro['backgroundType'],
                        $registro['background'], 
                        $registro['color'], 
                        $measure);

            array_push($squares, $square);
        }

        return $squares;
    }

    public static function show($id)
    {
        $conexao = Database::getInstance();

        $sql = "SELECT * FROM square WHERE id = :id";

        $comando = $conexao->prepare($sql);

        if ($id > 0) {
            $comando->bindValue(':id', $id);
        }
            
        $comando->execute();

        $registro = $comando->fetch();

        $measure = Measure::show($registro['id_measure']);

       $square = new Square(
                        $registro['id'], 
                        $registro['height'], 
                        $registro['backgroundType'], 
                        $registro['background'], 
                        $registro['color'], 
                        $measure);

        return $square;
    }

    public function draw()
    {
        $this->getBackgroundType()
            ? $type = "background-image: url(../../../Storage/img/".$this->getBackground().");
                       background-size: 100% 100%;'>"
            : $type = "background-color: ".$this->getColor()."'>";

        return "<div style='
                    width: ".$this->getHeight().$this->getMeasure()->getMeasurement()."; 
                    height: ".$this->getHeight().$this->getMeasure()->getMeasurement().";
                    $type
                </div>";    
    }
}