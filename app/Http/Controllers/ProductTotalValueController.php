<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ProductTotalValueController extends Controller
{
    public function __invoke(): Response
    {
        $total = Product::query()->sum(DB::raw('price * quantity'));

        return Response(['total' => $total]);
    }
}
