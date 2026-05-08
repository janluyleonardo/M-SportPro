<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'type',
        'category',
        'custom_category',
        'invoice_number',
        'amount',
        'date',
        'description',
        'student_id',
        'user_id',
        'product_id',
        'quantity',
        'reference_id',
        'attachment'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
