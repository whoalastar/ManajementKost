<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'account_number',
        'account_holder',
        'description',
        'logo',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the logo URL.
     */
    public function getLogoUrlAttribute()
    {
        if (!$this->logo) {
            // Return a default icon or null. For now let's return a generic placeholder if needed
            // Or return null so view can decide.
            return null; 
        }
        
        // Assuming all uploads go to public/uploads
        // If we want to support external URLs later, we can add check here.
        return asset($this->logo);
    }
}
