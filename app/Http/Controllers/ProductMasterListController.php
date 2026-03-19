<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductMasterListResource;
use App\Jobs\ProcessProductStatusJob;
use App\Models\ProductMasterList;
use Illuminate\Http\Request;

class ProductMasterListController extends Controller
{
    public function index(Request $request)
    {
        return $this->apiResponse->collection(
            ProductMasterListResource::collection(
                ProductMasterList::filter($request->all())->latest()->paginate()
            )
        );
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:2048',
        ]);

        $path = $request->file('file')->store('uploads', env('FILESYSTEM_DISK'));

        try {
            ProcessProductStatusJob::dispatch($path);

            return $this->apiResponse->message(__('File uploaded. Processing in background.'))->success();
        } catch (\Throwable $th) {
            return $this->apiResponse->badRequest(__($th->getMessage()));
        }
    }
}
