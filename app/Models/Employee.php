<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'contact_numbers', 'addresses'];
    
    protected $casts = [
        'contact_numbers' => 'array',
        'addresses' => 'array',
    ];
    
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
