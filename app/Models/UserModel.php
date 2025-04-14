<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;  // Tambahkan ini
use Illuminate\Foundation\Auth\User as Authenticatable; //implementasi class authenticatable


class UserModel extends Model
{
    use HasFactory;

    protected $table = 't_user';
    protected $primaryKey = 'user_id';
    protected $fillable = ['level_id', 'username', 'nama', 'password'];
    protected $hidden = ['password'];
    protected $casts = ['password'=> 'hashed'];
     public function level(): BelongsTo
 {
     return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
 }


    
}
