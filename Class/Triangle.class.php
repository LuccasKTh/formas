<?php 

require_once "Shape.class.php";
require_once "Measure.class.php";

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

    public static function index($tipo, $busca)
    {
        $sql = "SELECT * FROM triangle";
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
                    $sql .= " INNER JOIN measure ON (triangle.id_measure = measure.id) WHERE measurement LIKE :busca";
                    $busca = "%{$busca}%";
                    break;
            }

        $params = [];
        if ($tipo > 0)
            $params = [':busca' => $busca];

        $comando = Database::executar($sql, $params);

        $triangles = [];
        while ($registro = $comando->fetch()) {
            $measure = Measure::show($registro['id_measure']);
            switch (self::verifyTriangleType($registro['leftSide'], $registro['rightSide'], $registro['bottomSide'])) {
                case 'Equ':
                    $triangle = self::makeTriangle(TriangleEquilateral::class, $registro, $measure);
                    break;

                case 'Iso':
                    $triangle = self::makeTriangle(TriangleIsoscoles::class, $registro, $measure);
                    break;

                case 'Esc':
                    $triangle = self::makeTriangle(TriangleScalene::class, $registro, $measure);
                    break;
            }

            array_push($triangles, $triangle);
        }

        return $triangles;
    }

    public static function makeTriangle($class, $register, $measure)
    {
        return new $class(
                $register['id'], 
                $register['color'], 
                $register['image'], 
                $measure, 
                $register['leftSide'], 
                $register['rightSide'], 
                $register['bottomSide']);
    }

    public static function verifyTriangleType($a, $b, $c)
    {
        if ($a == $b && $b == $c && $c == $a) {
            return 'Equ';
        } elseif ($a == $b || $b == $c || $c == $a) {
            return 'Iso';
        }
        return 'Esc';
    }

    public function store()
    {
        $sql = 'INSERT INTO triangle (color, image, id_measure, leftSide, rightSide, bottomSide) VALUES (:color, :image, :id_measure, :leftSide, :rightSide, :bottomSide)';

        $params = parent::controlImage(Triangle::class);

        $params += [
            ':leftSide' => $this->getLeftSide(),
            ':rightSide' => $this->getRightSide(),
            ':bottomSide' => $this->getBottomSide()
        ];

        return Database::executar($sql, $params);
    }

    public function update()
    {
        $sql = 'UPDATE triangle SET color = :color, image = :image, id_measure = :id_measure, leftSide = :leftSide, rightSide = :rightSide, bottomSide = :bottomSide WHERE id = :id';

        $params = parent::controlImage(Triangle::class);

        $params += [
            ':id' => $this->getId(),
            ':leftSide' => $this->getLeftSide(),
            ':rightSide' => $this->getRightSide(),
            ':bottomSide' => $this->getBottomSide()
        ];

        return Database::executar($sql, $params);
    }

    public function destroy()
    {
        $sql = 'DELETE FROM triangle WHERE id = :id';

        unlink("../../../Storage/img/{$this->getImage()}");
        
        $params = [':id' => $this->getId()];

        return Database::executar($sql, $params);
    }

    public static function show($id)
    {
        $conexao = Database::getInstance();

        $sql = "SELECT * FROM triangle WHERE id = :id";

        $comando = $conexao->prepare($sql);

        if ($id > 0) {
            $comando->bindValue(':id', $id);
        }
            
        $comando->execute();

        $registro = $comando->fetch();

        $measure = Measure::show($registro['id_measure']);

        switch (self::verifyTriangleType($registro['leftSide'], $registro['rightSide'], $registro['bottomSide'])) {
            case 'Equ':
                $triangle = new TriangleEquilateral($registro['id'], $registro['color'], $registro['image'], $measure, $registro['leftSide'], $registro['rightSide'], $registro['bottomSide']);
                break;

            case 'Iso':
                $triangle = new TriangleIsoscoles($registro['id'], $registro['color'], $registro['image'], $measure, $registro['leftSide'], $registro['rightSide'], $registro['bottomSide']);
                break;

            case 'Esc':
                $triangle = new TriangleScalene($registro['id'], $registro['color'], $registro['image'], $measure, $registro['leftSide'], $registro['rightSide'], $registro['bottomSide']);
                break;
        }

        return $triangle;
    }

    public function calculateArea() 
    {
        $a = $this->getLeftSide();
        $b = $this->getRightSide();
        $c = $this->getBottomSide();

        $p = ($a + $b + $c) / 2;

        $result = sqrt($p * ($p - $a) * ($p - $b) * ($p - $c));

        return $result;
    }

    public function calculatePerimeter()
    {
        return $this->getLeftSide() + $this->getRightSide() + $this->getBottomSide();
    }

}