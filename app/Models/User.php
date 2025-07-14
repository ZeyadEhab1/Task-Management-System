<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];


    protected function casts(): array

    {
        return ['password' => 'hashed',];
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'user_id');
    }
}
