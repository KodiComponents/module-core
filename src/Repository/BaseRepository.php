<?php

namespace KodiCMS\CMS\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class BaseRepository implements \KodiCMS\CMS\Contracts\Repositories\BaseRepository
{
    use ValidatesRequests;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var array
     */
    protected $validationRules = [];

    /**
     * @param Model $model
     */
    public function __construct(Model $model = null)
    {
        $this->model = $model;
    }

    /**
     * @{@inheritdoc}
     */
    public function validationAttributes()
    {
        return [];
    }

    /**
     * @{@inheritdoc}
     */
    public function validationRules()
    {
        return [];
    }

    /**
     * @{@inheritdoc}
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @{@inheritdoc}
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * @{@inheritdoc}
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * @{@inheritdoc}
     */
    public function findOrFail($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @{@inheritdoc}
     */
    public function query()
    {
        return $this->model->query();
    }

    /**
     * @{@inheritdoc}
     */
    public function instance(array $attributes = [])
    {
        $model = $this->model;

        return new $model($attributes);
    }

    /**
     * @{@inheritdoc}
     */
    public function paginate($perPage = null)
    {
        return $this->model->paginate($perPage);
    }

    /**
     * @{@inheritdoc}
     */
    public function create(array $data = [])
    {
        return $this->model->create($data);
    }

    /**
     * @{@inheritdoc}
     */
    public function update($id, array $data = [])
    {
        $instance = $this->findOrFail($id);
        $instance->update($data);

        return $instance;
    }

    /**
     * @{@inheritdoc}
     */
    public function delete($id)
    {
        $model = $this->findOrFail($id);
        $model->delete();

        return $model;
    }

    /**
     * @{@inheritdoc}
     */
    public function validate(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()->make($request->all(), array_merge($this->validationRules(), $rules), $messages, array_merge($this->validationAttributes(), $customAttributes));

        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }
    }
}
