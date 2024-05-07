<?php

namespace App\Services;

use App\Models\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ImageService
{
    public function store($request, $storeData)
    {
        try {
            $file = $request->file('file');

            $location_name = 'images/'.strtolower(class_basename($storeData)).'/'; //change folder name according to the MODEL

            $name = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = env('PUBLIC_FILE_LOCATION') ? public_path('../'.$location_name ) : public_path($location_name );
            $file->move($destinationPath, $name);
            $location = $location_name . $name;

            $image = new Image();
            $image->url = $location;
            $image->type = $file->getClientOriginalExtension();
            $image->parentable_id = $storeData->id;     
            $image->parentable_type = get_class($storeData);
            $image->save();

        } catch (\Exception  $e) {
            Log::error('An error occurred: ' . $e->getMessage());
        }

        return true;
    }

    public function update($request, $storeData)
    {
       try {
            $file = $request->file('file');

            $storeData->load('image');
            $location_name = 'images/'.strtolower(class_basename($storeData)).'/'; //change folder name according to the MODEL

            $name = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = env('PUBLIC_FILE_LOCATION') ? public_path('../'.$location_name) : public_path($location_name);
            $file->move($destinationPath, $name);
            $location = $location_name . $name;
                
            if ($storeData->image) {
                    
                if (file_exists(public_path($storeData->image->url))) {
                    unlink(public_path($storeData->image->url));
                }
                    
                $storeData->image->url = $location;
                $storeData->image->save();
            } else {
                $image = new Image();
                $image->url = $location;
                $image->type = $file->getClientOriginalExtension();
                $image->parentable_id = $storeData->id;
                $image->parentable_type = get_class($storeData);
                $image->save();
            }

        } catch (\Exception  $e) {
            Log::error('An error occurred: ' . $e->getMessage());
        }

        return true;
    }

    
}