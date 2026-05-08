<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceSetting extends Model
{
    protected $fillable = ['prefix', 'next_number', 'resolution_number'];

    /**
     * Genera el siguiente número de factura y lo incrementa
     */
    public static function generateNext()
    {
        $setting = self::firstOrCreate([], [
            'prefix' => 'JFS-',
            'next_number' => 1001
        ]);

        $fullNumber = $setting->prefix . $setting->next_number;
        
        $setting->increment('next_number');
        
        return $fullNumber;
    }
}
