<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    //protected $table = 'categories';
    protected $primaryKey = 'category_id';

    protected $fillable = ['name'];

    // Relaciones

    // Categoría tiene recursos
    public function resources()
    {
        return $this->hasMany(Resource::class, 'category_id', 'category_id');
    }
}
