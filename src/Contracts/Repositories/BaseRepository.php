<?php

namespace KodiCMS\CMS\Contracts\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

interface BaseRepository
{
    /**
     * @return Model
     */
    public function getModel();

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Model[]
     */
    public function all();

    /**
     * @param int $id
     *
     * @return Model|null
     */
    public function find($id);

    /**
     * @param int $id
     *
     * @return Model
     * @throws ModelNotFoundException
     */
    public function findOrFail($id);

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query();

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function instance(array $attributes = []);

    /**
     * @param int|null $perPage
     *
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function paginate($perPage = null);

    /**
     * @param array $data
     *
     * @return Model
     */
    public function create(array $data = []);

    /**
     * @param int   $id
     * @param array $data
     *
     * @return Model
     */
    public function update($id, array $data = []);

    /**
     * @param int $id
     *
     * @return Model
     */
    public function delete($id);

    /**
     * @return array
     */
    public function validationAttributes();

    /**
     * @return array
     */
    public function validationRules();

    /**
     * Validate the given request with the given rules.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  array                    $rules
     * @param  array                    $messages
     * @param  array                    $customAttributes
     *
     * @throws ValidationException
     * @return void
     */
    public function validate(Request $request, array $rules, array $messages = [], array $customAttributes = []);
}
