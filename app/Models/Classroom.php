<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Classroom extends Model
{
    use HasFactory , SoftDeletes;

    public static string $disk = 'public';


    protected $fillable = [
        'name' , 'code' ,'section' , 'subject' , 'room' , 'cover_image_path' , 'theme' , 'user_id' ,
    ];

    public static function uploadCoverImage($file)
    {
        $path = $file->store('/covers', [
            'disk' => static::$disk
        ]);
        return $path;
    }

    public static function deleteCoverImage($path)
    {
        if(!$path || !Storage::disk(static::$disk)->exists($path)){
            return;
        }

        return Storage::disk(static::$disk)->delete($path);
    }
}
