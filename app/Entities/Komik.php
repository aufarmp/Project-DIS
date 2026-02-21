<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Komik extends Entity
{
    // Konfigurasi untuk otomatis isi Tanggal
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    
    // Helper
    // Nanti di View bisa panggil: $komik->status_badge
    public function getStatusBadge()
    {
        $color = match ($this->attributes['status']) {
            'ongoing'   => 'success', // Hijau
            'completed' => 'primary', // Biru
            'hiatus'    => 'warning', // Kuning
            default     => 'secondary',
        };
        
        return "<span class='badge bg-{$color}'>" . strtoupper($this->attributes['status']) . "</span>";
    }
}