<?php
require_once("../../classes/Measure.class.php");

class Quadrado 
{
    private $id;
    private $height;
    private $backgroundType;
    private $color;
    private $background;
    private $measure;

    public function __construct(
        int $id, 
        int $height, 
        bool $backgroundType, 
        string|int $background, 
        string $color, 
        Measure $measure = null
    ) {
        $this->setId($id);
        $this->setHeight($height);
        $this->setBackground($backgroundType);
        $this->setColor($color);
        $this->setBackground($background);
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
        if ($newColor == '') {
            throw new Exception("Error: Cor inválida!");
        } else {
            $this->color = $newColor;
        }
    }

    public function setHeight($newHeight)
    {
        if ($newHeight < 0) {
            throw new Exception("Error: Altura inválida!");
        } else {
            $this->height = $newHeight;
        }
    }

    public function setMeasure($newMeasure)
    {
        if ($newMeasure == null) {
            throw new Exception("Error: Medida inválida!");
        } else {
            $this->measure = $newMeasure;
        }
    }

    public function setBackground($newBackground)
    {
        if ($newBackground == null) {
            throw new Exception("Error: Imagem inválida!");
        } else {
            $this->background = $newBackground;
        }
    }

    public function getId()
    {
        return $this->id;    
    }

    public function getColor()
    {
        return $this->color;    
    }

    public function getHeight()
    {
        return $this->height;    
    }

    public function getMeasure()
    {
        return $this->measure;    
    }

    public function store()
    {
        $sql = 'INSERT INTO square (color, height, id_measure) VALUES (:color, :height, :id_measure)';

        $params = [
            ':color' => $this->color,
            ':height' => $this->height,
            ':id_measure' => $this->measure->getId()
        ];

        return Database::executar($sql, $params);
    }

    public function update()
    {
        $sql = 'UPDATE square SET color = :color, height = :height, id_measure = :id_measure WHERE id = :id';

        $params = [
            ':id' => $this->id,
            ':color' => $this->color,
            ':height' => $this->height,
            ':id_measure' => $this->measure->getId()
        ];

        return Database::executar($sql, $params);
    }

    public function destroy()
    {
        $sql = 'DELETE FROM square WHERE id = :id';
        
        $params = [':id' => $this->id];

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
            $square = new Quadrado(
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

       $square = new Quadrado(
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
        return "<div style='
                    width: ".$this->height.$this->measure->getMeasurement()."; 
                    height: ".$this->height.$this->measure->getMeasurement()."; 
                    background-color: ".$this->color."'>
                </div>";    
    }
}