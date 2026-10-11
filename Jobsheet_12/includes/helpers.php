<?php
// [Jobsheet 11] Membungkus output data dari user sebelum dicetak ke HTML
// untuk mencegah celah keamanan XSS (Cross-Site Scripting).
function e($value)
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}