<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MediaController extends Controller
{

    public function index()
    {
        return Media::simplePaginate(5);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:png,jpg|max:10000',
            'resource_type' => 'required|in:user,organization,item',
            'resource_id' => 'required',
            'media_type' => 'required|in:picture,avatar,video,logo',
        ]);
//        dd('sdf');
        if ($validator->fails()) {
            return [
                'status_code' => 0,
                'message' => $validator->messages()->first(),
                'errors' => $validator->messages()
            ];
        }

        $item = new Media();
        $item->resource_id = $request->input('resource_id');
        $item->resource_type = $request->input('resource_type');
        $item->media_type = $request->input('media_type');

        // save file
        $path = $request->file('file')->store('public/medias');
        $item->filename = str_replace('public/medias/', '', $path);

        $item->save();

        return $item;
    }

    public function destroy(Media $media)
    {
        //
    }
}
