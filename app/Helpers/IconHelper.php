<?php

namespace App\Helpers;

class IconHelper
{
    public static function render($name, $class = 'w-4 h-4')
    {
        return match ($name) {

            'plus' => "
                <svg class='{$class}' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'>
                    <path stroke-linecap='round' stroke-linejoin='round' d='M12 4v16m8-8H4'/>
                </svg>
            ",

            'edit' => "
                <svg class='{$class}' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'>
                    <path stroke-linecap='round' stroke-linejoin='round'
                          d='M15.232 5.232l3.536 3.536M9 13l6-6 3 3-6 6H9v-3z'/>
                </svg>
            ",

            'trash' => "
                <svg class='{$class}' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'>
                    <path stroke-linecap='round' stroke-linejoin='round'
                          d='M6 7h12M9 7v10m6-10v10M10 3h4'/>
                </svg>
            ",

            'search' => "
                <svg class='{$class}' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'>
                    <circle cx='11' cy='11' r='8'/>
                    <path stroke-linecap='round' stroke-linejoin='round' d='M21 21l-4.35-4.35'/>
                </svg>
            ",

            'refresh' => "
                <svg class='{$class}' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'>
                    <path stroke-linecap='round' stroke-linejoin='round'
                          d='M4 4v6h6M20 20v-6h-6'/>
                </svg>
            ",

            default => '',
        };
    }
}