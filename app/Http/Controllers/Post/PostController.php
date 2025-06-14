<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\CarModel;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function getModel(Request $request)
    {
        return CarModel::where('brand_id', $request->getContent())->get();
    }
}
