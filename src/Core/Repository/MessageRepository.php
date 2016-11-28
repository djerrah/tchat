<?php
/**
 * Created by PhpStorm.
 * User: djerrah
 * Date: 26/11/16
 * Time: 14:10
 */

namespace Core\Repository;

/**
 * Class MessageRepository
 *
 * @package Core\Repository
 */
class MessageRepository  extends BaseRepository
{
    /**
     * @var string
     */
    protected $table='message';


    /**
     * @param array $data
     *
     * @return null
     */
    public function insert(array $data = [])
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $this->app->getSession()->get('user')->id;

        return parent::insert($data);
    }

    /**
     * @return array
     */
    public function findAll($lastId=0, $orderBy=[], $limite = 0)
    {
        $handler = $this->getConnection();


        $query = "
                  SELECT
                    message.id as message_id,
                    message.body as message_body,
                    message.created_at as message_created_at,
                    user.id as user_id,
                    user.online as user_online,
                    user.last_login as user_last_login,
                    user.username as user_username
                  FROM $this->table
                  INNER JOIN user ON message.created_by = user.id
                  WHERE message.id > $lastId
                  ";

        foreach($orderBy as $key => $value)
        {

            $query .= "\n ORDER BY  $key $value";
        }

        if($limite){
            $query .= "\n limit $limite";
        }

        $query = $handler->prepare($query);

        $query->execute();

        //$result = $query->fetchAll(\PDO::FETCH_CLASS, User::class);
        $results = $query->fetchAll(\PDO::FETCH_OBJ);

        foreach($results as $key=> $result)
        {
            $now = new \DateTime();
            $lastLogin = new \DateTime($result->user_last_login);

            $diff = $lastLogin->diff($now);

            $result->enLigne = $result->user_online && ($diff->y ==0 && $diff->m ==0 && $diff->d ==0 && $diff->h ==0 && $diff->m <=5);
        }

        return $results;
    }
}
