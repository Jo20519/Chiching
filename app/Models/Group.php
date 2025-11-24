<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'created_by',
        'contribution_amount',
    ];

    public function members()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('role', 'active')
                    ->withTimestamps();
    }

    public function contributions()
    {
        return $this->hasMany(Contribution::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(WithdrawalRequest::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
