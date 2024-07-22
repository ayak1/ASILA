<?php
use App\Models\Locale;

function getLocale($lang) {
    return Locale::where('code', $lang ?? request()->header('Accept-Language') ?? 'ar')->first();
}

