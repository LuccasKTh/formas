<?php

require_once "Measure.class.php";
require_once "Shape.class.php";

class Circle extends Shape
{
    private float $radius;

    public function __construct($id, $color, $image, Measure $measure, $radius)
    {
        parent::__construct($id, $color, $image, $measure);
        $this->setRadius($radius);
    }

    public function setRadius($newRadius)
    {
        $this->radius = $newRadius;
    }

    public function getRadius()
    {
        return $this->radius;
    }

    public function store()
    {
        $sql = 'INSERT INTO circle (color, image, id_measure, radius) VALUES (:color, :image, :id_measure, :radius)';

        $params = [
            ':color' => $this->getColor(),
            ':image' => $this->getImage(),
            ':id_measure' => $this->getMeasure()->getId(),
            ':radius' => $this->getRadius()
        ];

        return Database::executar($sql, $params);
    }

    public function update()
    {
        $sql = 'UPDATE circle SET color = :color, image = :image, id_measure = :id_measure, radius = :radius, WHERE id = :id';
    
        $params = [
            ':id' => $this->getId(),
            ':color' => $this->getColor(),
            ':background' => $this->getImage(),
            ':id_measure' => $this->getMeasure()->getId(),
            ':radius' => $this->getRadius()
        ];

        return Database::executar($sql, $params);
    }

    public function destroy()
    {
        $sql = 'DELETE FROM circle WHERE id = :id';

        unlink($_SERVER['DOCUMENT_ROOT'] . "/Storage/img/{$this->getImage()}");
        
        $params = [':id' => $this->getId()];

        return Database::executar($sql, $params);
    }

    public static function index($tipo, $busca)
    {
        $sql = "SELECT * FROM circle";
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
                    $sql .= " WHERE radius LIKE :busca";
                    $busca = "%{$busca}%";
                    break;
                case 4:
                    $sql .= " INNER JOIN measure ON (circle.id_measure = measure.id) WHERE measurement LIKE :busca";
                    $busca = "%{$busca}%";
                    break;
            }

        $params = [];
        if ($tipo > 0)
            $params = [':busca' => $busca];

        $comando = Database::executar($sql, $params);

        $circles = [];
        while ($registro = $comando->fetch()) {
            $measure = Measure::show($registro['id_measure']);
            $circle = new Circle(
                        $registro['id'], 
                        $registro['color'], 
                        $registro['image'], 
                        $measure,
                        $registro['radius']
                    );

            array_push($circles, $circle);
        }

        return $circles;
    }

    public static function show($id)
    {
        $conexao = Database::getInstance();

        $sql = "SELECT * FROM circle WHERE id = :id";

        $comando = $conexao->prepare($sql);

        if ($id > 0) {
            $comando->bindValue(':id', $id);
        }
            
        $comando->execute();

        $registro = $comando->fetch();

        $measure = Measure::show($registro['id_measure']);

        $circle = new Circle(
                        $registro['id'], 
                        $registro['color'], 
                        $registro['image'], 
                        $measure,
                        $registro['radius']
                    );

        return $circle;
    }

    public function draw()
    {
        $this->getColor()
            ? $type = "background-color: ".$this->getColor()."'>"
            : $type = "background-image: url(../../../Storage/img/".$this->getImage()."); background-size: 100% 100%;";

        return "<div style='
                    width: ".($this->getRadius()*2).$this->getMeasure()->getMeasurement()."; 
                    height: ".($this->getRadius()*2).$this->getMeasure()->getMeasurement().";
                    border-radius: 50%;
                    $type
                </div>";    
    }
}