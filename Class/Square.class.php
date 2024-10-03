<?php

require_once "Measure.class.php";
require_once "Shape.class.php";

class Square extends Shape
{
    private int $height;

    public function __construct($id, $color, $image, Measure $measure, $height) {
        parent::__construct($id, $color, $image, $measure);
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
        $sql = 'INSERT INTO square (color, image, id_measure, height) VALUES (:color, :image, :id_measure, :height)';

        $params = parent::controlImage(Square::class);

        $params += [':height' => $this->getHeight()];

        return Database::executar($sql, $params);
    }

    public function update()
    {
        $sql = 'UPDATE square SET color = :color, image = :image, id_measure = :id_measure, height = :height WHERE id = :id';

        $params = parent::controlImage(Square::class);

        $params += [
            ':id' => $this->getId(),
            ':height' => $this->getHeight()
        ];

        return Database::executar($sql, $params);
    }

    public function destroy()
    {
        $sql = 'DELETE FROM square WHERE id = :id';

        unlink("../../../Storage/img/{$this->getImage()}");
        
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
                        $registro['color'], 
                        $registro['image'], 
                        $measure,
                        $registro['height']
                    );

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
                        $registro['color'], 
                        $registro['image'], 
                        $measure,
                        $registro['height']
                    );

        return $square;
    }

    public function draw()
    {
        $this->getColor()
            ? $type = "background-color: ".$this->getColor()
            : $type = "background-image: url(../../../Storage/img/".$this->getImage()."); background-size: 100% 100%";

        return "<div style='width: ".$this->getHeight().$this->getMeasure()->getMeasurement()."; height: ".$this->getHeight().$this->getMeasure()->getMeasurement()."; $type;'></div>";    
    }

    public function calculateArea() 
    {
        return $this->getHeight() * $this->getHeight();
    }

    public function calculatePerimeter()
    {
        return $this->getHeight() * 4;
    }
}