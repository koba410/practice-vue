<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'kana',
        'tel',
        'email',
        'postcode',
        'address',
        'birthday',
        'gender',
        'memo',
    ];

    /**
     * 顧客検索
     * @param Builder $query
     * @param string|null $input
     * @return Builder
     */
    public function scopeSearchCustomers($query, $input = null)
    {
        if (!empty($input)) {
            if (Customer::where('name', 'like', '%' . $input . '%')->orWhere('kana', 'like', '%' . $input . '%')->exists()) {
                return $query->where('name', 'like', '%' . $input . '%')->orWhere('kana', 'like', '%' . $input . '%');
            }
        }
    }
}
