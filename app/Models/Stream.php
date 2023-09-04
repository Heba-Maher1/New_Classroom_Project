<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Stream extends Model
{
    use HasFactory , HasUuids;

    public $incrementing = false ; // stop using auto increment for id beacause we make if uuid

    protected $keyType = 'string' ; // instead of integer of id we turned it to string

    protected $fillable = [
         'classroom_id' , 'user_id' , 'content' ,'link'
    ];

    // HasUuids trait instead of this code => auto generate of primary key as uuid
    // protected static function booted()
    // {
    //     static::creating(function(Stream $stream) {
    //         $stream->id = Str::uuid();
    //     });
    // }

    // public function uniqueIds() // by default return the primary key , if i have multiple columns uuid and i want laravel to auto generate
    // {
    //     return [
    //         'id' , 'uuid' , ''
    //     ];
    // }
    public function getUpdatedAtColumn()
    {
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    

}
