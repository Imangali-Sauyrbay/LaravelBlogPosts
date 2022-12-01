<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name'
    ];

    public static function isAdmin(int $role_id)
    {
        $admin = Role::where('name', 'admin')->first();
        return $admin->id == $role_id;
    }

    public function authors()
    {
        return $this->hasMany(Author::class);
    }
}
