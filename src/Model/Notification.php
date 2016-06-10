<?php

namespace KodiCMS\CMS\Model;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use KodiCMS\CMS\Events\NotificationCreated;

/**
 * Class Notification
 *
 * @package App
 * @property int             $id
 * @property int             $user_id
 * @property Authenticatable $user
 * @property int             $created_by
 * @property Authenticatable $creator
 * @property bool            $read
 * @property string          $icon
 * @property string          $action_text
 * @property string          $action_url
 * @property string          $body
 * @property Carbon          $created_at
 * @property Carbon          $updated_at
 * @method static \Illuminate\Database\Query\Builder|\KodiCMS\CMS\Model\Notification whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\KodiCMS\CMS\Model\Notification whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\KodiCMS\CMS\Model\Notification whereCreatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\KodiCMS\CMS\Model\Notification whereIcon($value)
 * @method static \Illuminate\Database\Query\Builder|\KodiCMS\CMS\Model\Notification whereBody($value)
 * @method static \Illuminate\Database\Query\Builder|\KodiCMS\CMS\Model\Notification whereActionText($value)
 * @method static \Illuminate\Database\Query\Builder|\KodiCMS\CMS\Model\Notification whereActionUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\KodiCMS\CMS\Model\Notification whereRead($value)
 * @method static \Illuminate\Database\Query\Builder|\KodiCMS\CMS\Model\Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\KodiCMS\CMS\Model\Notification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\KodiCMS\CMS\Model\Notification unread()
 * @method static \Illuminate\Database\Query\Builder|\KodiCMS\CMS\Model\Notification forUser($userId)
 */
class Notification extends Model
{

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function (Notification $notification) {
            event(new NotificationCreated($notification));
        });
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    /**
     * The guarded attributes on the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'read' => 'boolean',
    ];

    /**********************************************************************
     * Relations
     **********************************************************************/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\CMS::userModel());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(\CMS::userModel(), 'created_by');
    }

    /**********************************************************************
     * Scopes
     **********************************************************************/

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeUnread($query)
    {
        return $query->where('read', false);
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
