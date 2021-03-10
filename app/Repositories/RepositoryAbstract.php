<?php

namespace App\Repositories;

use Illuminate\Container\Container as App;

abstract class RepositoryAbstract implements RepositoryInterface
{
    protected $model;
    /**
     * @var $model
     */
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    public function makeModel()
    {
        $model = $this->app->make($this->model());

        return $this->model = $model->newQuery();
    }

    /**
     * @return mixed
     */
    abstract function model();

    /**
     * @param string[] $columns
     * @return mixed
     */
    public function all($columns = ['*'])
    {
        return $this->model->get($columns);
    }

    /**
     * @param $keyNeedUpdate
     * @param $data
     * @return mixed
     */
    public function updateOrCreate($keyNeedUpdate, $data)
    {
        return $this->updateOrCreate($keyNeedUpdate, $data);
    }

    public function paginate($perPage = 15, $columns = array('*'))
    {
        return $this->model->paginate($perPage, $columns);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(array $data, $id, $attribute = "id")
    {
        return $this->model->where($attribute, '=', $id)->update($data);
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    public function find($id, $columns = ["*"])
    {
        return $this->model->find($id, $columns);
    }

    public function findBy($attribute, $value, $columns = array('*'))
    {
        return $this->model->where($attribute, '=', $value)->first($columns);
    }
}
