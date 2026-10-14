<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /** このユーザーが管理する顧客 */
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    /** このユーザーの顧客に紐づく全タスク */
    public function tasks(): HasManyThrough
    {
        return $this->hasManyThrough(Task::class, Customer::class);
    }
}