<?php
/**
 * Created by PhpStorm.
 * User: djerrah
 * Date: 26/11/16
 * Time: 14:10
 */

namespace Core\Repository;

use Config\App;

/**
 * Class BaseRepository
 *
 * @package Core\Repository
 */
class BaseRepository 
{
    /**
     * @var
     */
    protected $table;

    /**
     * @var App
     */
    protected $app;

    private static $handler;

    private $dbhost = "192.168.56.2";
    private $dbname = "tchat";
    private $dbuser = "root";
    private $dbpswd = "root";
    private $port = 3306;


    /**
     *
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }


    /**
     * @return \PDO
     */
    public function getConnection()
    {
        if (!self::$handler) {
            try {
                self::$handler = new \PDO(
                    "mysql:host=$this->dbhost;port=$this->port;dbname=$this->dbname",
                    $this->dbuser,
                    $this->dbpswd,
                    [\PDO::ATTR_PERSISTENT => false]
                );

            } catch (\Exception $e) {
                echo $e->getMessage();
                die;
            }
        }

        return self::$handler;
    }

    /**
     * @return array
     */
    public function findAll()
    {
        $handler = $this->getConnection();


        $query = "SELECT * FROM $this->table;";

        $query = $handler->prepare($query);

        $query->execute();

        //$result = $query->fetchAll(\PDO::FETCH_CLASS, User::class);
        $result = $query->fetchAll(\PDO::FETCH_OBJ);


        return $result;
    }

    /**
     * @param array $criteria
     *
     * @return array
     */
    public function findByCriteria(array $criteria = [])
    {
        $handler = $this->getConnection();

        $wheres = [];
        foreach ($criteria as $key => $value) {
            $wheres[] = sprintf("$key = :$key");
        }

        $query = "SELECT * FROM $this->table ";

        if (count($wheres)) {
            $query .= sprintf("WHERE %s", implode("and", $wheres));
        }

        $query = $handler->prepare($query);

        $query->execute($criteria);

        //$result = $query->fetchAll(\PDO::FETCH_CLASS, User::class);
        $result = $query->fetchAll(\PDO::FETCH_OBJ);


        return $result;
    }

    /**
     * @param array $criteria
     *
     * @return null
     */
    public function findOneByCriteria(array $criteria = [])
    {
        $handler = $this->getConnection();

        $wheres = [];
        foreach ($criteria as $key => $value) {
            $wheres[] = sprintf("$key = :$key");
        }

        $query = "SELECT * FROM $this->table ";

        if (count($wheres)) {
            $query .= sprintf("WHERE %s", implode("and", $wheres));
        }

        $query .= " limit 1";

        $query = $handler->prepare($query);

        $query->execute($criteria);

        //$result = $query->fetchAll(\PDO::FETCH_CLASS, User::class);
        $results = $query->fetchAll(\PDO::FETCH_OBJ);

        if (count($results)) {
            return $results[0];
        }

        return null;
    }

    /**
     * @param array $data
     *
     * @return null
     */
    public function insert(array $data = [])
    {
        $handler = $this->getConnection();

        $query = sprintf(
            "INSERT INTO $this->table (`%s`) VALUES (%s)",
            implode('`, `', array_keys($data)),
            implode(', ', array_pad([], count($data), '?'))
        );

        $query = $handler->prepare($query);

        $query->execute(array_values($data));
        //var_dump($query);die;

        $lastId = $handler->lastInsertId();

        return $this->findOneByCriteria(['id' => $lastId]);
    }

    /**
     * @param array $data
     * @param array $criteria
     *
     * @return bool|null
     */
    public function update(array $data, array $criteria)
    {
        if (isset($data['id'])) {
            $id = $data['id'];
            unset($data['id']);
            if (!isset($criteria['id'])) {
                $criteria['id'] = $id;
            }
        }

        $handler = $this->getConnection();

        $updateFields   = [];
        $criteriaFields = [];


        foreach ($data as $key => $value) {
            if (trim($value) ||$key == 'online') {
            $updateFields[] = sprintf("`%s`='%s'", $key, trim($value));
            }
        }

        foreach ($criteria as $key => $value) {
            if (trim($value)) {
                $criteriaFields[] = sprintf("`%s`='%s'", $key, trim($value));
            }
        }
        if (count($updateFields) && count($criteriaFields)) {

            $query = sprintf(
                "UPDATE $this->table SET %s WHERE %s",
                implode(', ', $updateFields),
                implode('and', $criteriaFields)
            );

            //var_dump($query);die;
            $query = $handler->prepare($query);


            $query->execute();

            return $this->findOneByCriteria($criteria);
        }

        return false;
    }

}
