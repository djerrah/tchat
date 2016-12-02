<?php
/**
 * Created by PhpStorm.
 * User: djerrah
 * Date: 26/11/16
 * Time: 14:10
 */

namespace Core\Repository;

/**
 * Class UserRepository
 *
 * @package Core\Repository
 */
class UserRepository extends BaseRepository
{

    /**
     * @var string
     */
    protected $table = 'user';


    /**
     * @param array $data
     *
     * @return null
     */
    public function insert(array $data = [])
    {
        $data['last_login'] = date('Y-m-d H:i:s');
        $data['online']     = 1;

        return parent::insert($data);
    }

    /**
     * @param array $data
     * @param array $criteria
     *
     * @return bool|null
     */
    public function update(array $data, array $criteria)
    {
        $data['last_login'] = date('Y-m-d H:i:s');

        if(!isset($data['online'])) {
            $data['online'] = 1;
        }
        return parent::update($data, $criteria);
    }
}
