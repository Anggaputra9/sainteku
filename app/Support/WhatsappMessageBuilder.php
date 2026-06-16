<?php

namespace App\Support;

class WhatsappMessageBuilder
{
    public static function fromNotificationData(array $data, string $recipientName): string
    {
        if (!empty($data['whatsapp_text'])) {
            return $data['whatsapp_text'];
        }

        $action   = $data['action'] ?? 'mengirim notifikasi';
        $item     = $data['item_name'] ?? '-';
        $type     = $data['type'] ?? 'Notifikasi';
        $sender   = $data['sender_name'] ?? 'Sistem';
        $url      = $data['url'] ?? null;

        $message  = "📢 *{$type}*\n\n";
        $message .= "Yth. *{$recipientName}*\n\n";
        $message .= "{$sender} {$action} *{$item}*.\n\n";

        if ($url) {
            $message .= "🔗 Buka di aplikasi:\n{$url}\n\n";
        }

        $message .= "_Sainteku_";

        return $message;
    }
}