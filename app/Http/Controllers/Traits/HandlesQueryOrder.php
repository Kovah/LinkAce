<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;

trait HandlesQueryOrder
{
    protected function getOrderDirection(Request $request, $default = 'desc'): string
    {
        $dir = $request->input('orderDir');
        return in_array($dir, ['asc', 'desc']) ? $dir : $default;
    }
}
