<?php 

namespace Suitcore\Repositories\Eloquent;

use StdClass;

class BaseRepository
{
    /**
    * Make a new instance of the entity to query on
    *
    * @param array $with
    */
    public function make(array $with = array())
    {
        return $this->model->with($with);
    }

    /**
    * Retrieve all entities
    *
    * @param array $with
    * @return Illuminate\Database\Eloquent\Collection
    */
    public function all(array $with = array())
    {
        $entity = $this->make($with);

        return $entity->get();
    }

    /**
    * Find a single entity
    *
    * @param int $id
    * @param array $with
    * @return Illuminate\Database\Eloquent\Model
    */
    public function find($id, array $with = array())
    {
        $entity = $this->make($with);

        return $entity->find($id);
    }

    /**
    * Get Results by Page
    *
    * @param int $page
    * @param int $limit
    * @param array $with
    * @return StdClass Object with $items and $totalItems for pagination
    */
    public function getByPage($page = 1, $limit = 10, $with = array())
    {
        return $this->make($with)->latest()->paginate($limit);
    }

    /**
    * Search for many results by key and value
    *
    * @param string $key
    * @param mixed $value
    * @param array $with
    * @return Illuminate\Database\Query\Builders
    */
    public function getBy($key, $value, array $with = array())
    {
        return $this->make($with)->where($key, '=', $value)->get();
    }
}
