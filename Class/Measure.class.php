<?php

class Measure 
{
    private int $id;
    private string $measurement;

    public function __construct($id, $measurement)
    {
        $this->setId($id);   
        $this->setMeasurement($measurement);   
    }

    public function setId($newId)
    {
        if ($newId < 0) {
            throw new Exception("Error: Id inválido!");
        } else {
            $this->id = $newId;
        }
    }

    public function setMeasurement($newMeasurement)
    {
        if ($newMeasurement == '') {
            throw new Exception("Error: Unidade de medida inválido!");
        } else {
            $this->measurement = $newMeasurement;
        }
    }

    public function getId()
    {
        return $this->id;    
    }

    public function getMeasurement()
    {
        return $this->measurement;
    }

    public function store()
    {
        $sql = 'INSERT INTO measure (measurement) VALUES (:measurement)';

        $parametros = [
            ':measurement' => $this->measurement
        ];

        return Database::executar($sql, $parametros);
    }

    public function update()
    {
        $sql = 'UPDATE measure SET measurement = :measurement WHERE id = :id';

        $parametros = [
            ':id' => $this->id,
            ':measurement' => $this->measurement
        ];

        return Database::executar($sql, $parametros);
    }

    public function destroy()
    {
        $sql = 'DELETE FROM measure WHERE id = :id';
        
        $parametros = ['id' => $this->id];

        try {
            return Database::executar($sql, $parametros);
        } catch (Throwable $th) {
            return false;
        }
    }

    public static function show($id)
    {
        $sql = "SELECT * FROM measure WHERE id = :id";

        $parametros = [':id' => $id];

        $measurement = Database::executar($sql, $parametros);

        $measurement = $measurement->fetch();

        $measurement = new Measure($measurement['id'], $measurement['measurement']);

        return $measurement;
    }

    public static function index($tipo, $busca)
    {
        $sql = "SELECT * FROM measure";
        if ($tipo > 0)
            switch ($tipo) {
                case 1:
                    $sql .= " WHERE id = :busca";
                    break;
                case 2:
                    $sql .= " WHERE measurement LIKE :busca";
                    $busca = "%{$busca}%";
                    break;
            }

        $parametros = [];
        if ($tipo > 0)
            $parametros = [':busca' => $busca];

        $comando = Database::executar($sql, $parametros);
        
        $measurements = [];
        while ($registro = $comando->fetch()) {
            $measurement = new Measure($registro['id'], $registro['measurement']);
            array_push($measurements, $measurement);
        }

        return $measurements;
    }
}
