<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GlobalNotification extends Notification
{
    use Queueable;

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database']; // Wajib ke database
    }

    public function toDatabase($notifiable)
    {
        $senderName = auth()->user()->name ?? 'Sistem';
        
        // Ambil huruf pertama buat Avatar (Misal "Angga" -> "A")
        $initial = strtoupper(substr($senderName, 0, 1));

        return [
            'userName'     => $senderName,
            'userInitial'  => $initial,
            'action'       => $this->data['action'],        // cth: 'mengajukan review untuk'
            'item_name'    => $this->data['item_name'],     // cth: 'Soal UTS'
            'type'         => $this->data['type'],          // cth: 'Tashih' atau 'Dokumen'
            
            // Konfigurasi Navigasi (URL atau Buka Modal)
            'target_url'   => $this->data['url'] ?? '#', 
            'reference_id' => $this->data['reference_id'] ?? null, 
            'click_action' => $this->data['click_action'] ?? 'redirect',
            
            'status'       => $this->data['status'] ?? 'online', 
        ];
    }
}