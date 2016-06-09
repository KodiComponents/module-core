<?php

namespace KodiCMS\CMS\Configuration;

trait ManagesModelOptions
{
    /**
     * The user model class name.
     *
     * @var string
     */
    public static $userModel = 'KodiCMS\Users\Model\User';

    /**
     * The team model class name.
     *
     * @var string
     */
    public static $roleModel = 'KodiCMS\Users\Model\Role';

    /**
     * Set the user model class name.
     *
     * @param  string $userModel
     *
     * @return void
     */
    public static function useUserModel($userModel)
    {
        static::$userModel = $userModel;
    }

    /**
     * Get the user model class name.
     *
     * @return string
     */
    public static function userModel()
    {
        return static::$userModel;
    }

    /**
     * Get a new user model instance.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public static function user()
    {
        return new static::$userModel;
    }

    /**
     * Set the role model class name.
     *
     * @param  string $roleModel
     *
     * @return void
     */
    public static function useRoleModel($roleModel)
    {
        static::$roleModel = $roleModel;
    }

    /**
     * Get the team model class name.
     *
     * @return string
     */
    public static function roleModel()
    {
        return static::$roleModel;
    }

    /**
     * Get a new team model instance.
     *
     * @return \KodiCMS\Users\Model\Role
     */
    public static function role()
    {
        return new static::$roleModel;
    }
}
