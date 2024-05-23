<?php
use App\Models\Image;

function addImageToModel($model, $image, $isCover,$folder){

    $imagePath = $image->store($folder, 'public');
    if($isCover){
        $imageName = 'cover_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
    }else{
        $imageName = 'image_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
    }
    Storage::putFileAs('public/'.$folder, $image, $imageName);
    // Create the image record and save it
    $imageRecord = new Image(['path' => $folder.'/'. $imageName, 'is_cover' => $isCover]);
    $model->images()->save($imageRecord);
}

function updateImageInModel($model, $newImage, $isCover, $folder){
    if ($isCover) {
        // Delete cover images
        deleteCoverImage($model);
    }else{
        deleteImages($model);
        foreach($newImage as $image){
            addImageToModel($model, $image, $isCover, $folder);
        }
    }
}

function deleteImages($model){
    // Get the existing images associated with the model
    $existingImages = $model->images;

    // Delete each image from storage and database
    foreach ($existingImages as $image) {
        // Delete from storage
        if(!$image->is_cover){
            Storage::delete('public/' . $image->path);
            // Delete from database
            $image->delete();
        }
    }
}

function deleteCoverImage($model){
    $existingCoverImage = $model->images()->where('is_cover', true)->first();

        if ($existingCoverImage) {
            // Delete from storage
            Storage::delete('public/' . $existingCoverImage->path);

            // Delete from database
            $existingCoverImage->delete();
        }
}


