<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserModel extends Authenticatable
{
    use HasFactory;

    protected $table = 't_user';
    protected $primaryKey = 'user_id';
    protected $fillable = ['level_id', 'username', 'nama', 'password'];
    protected $hidden = ['password'];
    protected $casts = ['password' => 'hashed'];
    public $timestamps = false;

    public function level(): BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }

    public function rolename(): string
    {
        return $this->level->level_nama;
    }

    public function has_role($role): bool
    {
        return $this->level->level_kode == $role;
    }

    public function get_role()
    {
        return $this->level->level_kode;
    }
}
