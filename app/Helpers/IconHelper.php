<?php

namespace App\Helpers;

class IconHelper
{
    public static function render($name)
    {
        return match ($name) {

            'dashboard' => '
                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 13h8V3H3v10zm10 8h8V3h-8v18z"/>
                </svg>
            ',

            'database' => '
                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24" stroke-width="1.5">
                    <ellipse cx="12" cy="5" rx="9" ry="3"/>
                    <path d="M3 5v6c0 1.7 4 3 9 3s9-1.3 9-3V5"/>
                </svg>
            ',

            'document' => '
                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M6 2h9l5 5v15H6z"/>
                </svg>
            ',

            'chart' => '
                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M4 19h16M6 17V9m6 8V5m6 12v-4"/>
                </svg>
            ',

            'calendar' => '
                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24" stroke-width="1.5">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                </svg>
            ',

            'trophy' => '
                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M8 21h8M12 17v4M6 4h12v3a6 6 0 01-12 0V4z"/>
                </svg>
            ',

            'megaphone' => '
                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 10v4l14 4V6z"/>
                </svg>
            ',

            'building' => '
                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24" stroke-width="1.5">
                    <rect x="4" y="2" width="16" height="20"/>
                </svg>
            ',

            'shield' => '
                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 2l8 4v6c0 5-3.5 9-8 10-4.5-1-8-5-8-10V6z"/>
                </svg>
            ',

            'report' => '
                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M4 4h16v16H4zM8 8h8M8 12h8M8 16h5"/>
                </svg>
            ',

            default => '',
        };
    }
}