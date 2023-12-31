<?php

namespace App\Models;

use App\Models\Scopes\UserClassroomScope;
use App\Observers\ClassroomObserver;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Classroom extends Model
{
    use HasFactory , SoftDeletes;

    public static string $disk = 'public';


    protected $fillable = [
        'name' , 'code' ,'section' , 'subject' , 'room' , 'cover_image_path' , 'theme' , 'user_id' ,
    ];

    protected $appends = [
        'cover_image_url'
    ];
    protected $hidden = [
        'cover_image_path','deleted_at'
        // 'name'
    ]; // doesnt return in json data we use it if there is any sensitive information like password

    protected static function booted()
    {

        static::observe(ClassroomObserver::class);
        // static::addGlobalScope('user' , function(Builder $query){
        //     $query->where('user_id' , '=', Auth::id());
        // });

        static::addGlobalScope(new UserClassroomScope);

        static::creating(function(Classroom $classroom){
            $classroom->code = Str::random(8);
            $classroom->user_id = Auth::id();
        });

        // static::forceDeleted(function(Classroom $classroom){
        //     static::deleteCoverImage($classroom->cover_image_path);
        // });

        // static::deleted(function(Classroom $classroom){
        //     $classroom->status = 'deleted';
        //     $classroom->save();
        // });

        // static::restored(function(Classroom $classroom){
        //     $classroom->status = 'active';
        //     $classroom->save();
        // });
    }

    public function classworks(): HasMany
    {
        // return $this->hasMany(Classwork::class);
        return $this->hasMany(Classwork::class , 'classroom_id' , 'id');
    }

    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class , 'classroom_id' , 'id');
    }

    public function user(){

        return $this->belongsTo(User::class);

    }
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class ,       
            'classroom_user', 
            'classroom_id' , 
            'user_id' ,  
            'id' ,   
            'id', 
        )->withPivot(['role' , 'created_at']);

    }

    public function teacher()
    {
        return $this->users()->wherePivot('role' ,'=' , 'teacher'); 
    }

    public function students()
    {
        return $this->users()->wherePivot('role' ,'=' , 'student'); 
    }

    public function stream(){
        return $this->hasMany(Stream::class)->latest();
    }

    public function messages()
    {
        return $this->morphMany(Message::class , 'recipient');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function getRouteKeyName()
    {
        return 'id';
    }

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

    public function scopeActive(Builder $query)
    {
      $query->where('status' , '=' , 'active');
    }

    public function scopeRecent(Builder $query)
    {
      $query->orderBy('updated_at' , 'DESC');
    }

    public function scopeStatus(Builder $query , $status = 'active')
    {
      $query->where('status' , '=' , $status );
    }

    public function join($user_id , $role = 'student')
    {

        $exists = $this->users()
        ->wherePivot('user_id' ,'=' , $user_id)
        ->exists();
        
        if ($exists){

            throw new Exception('User already joined the classroom');
        }
        //using relation 
        $this->users()->attach($user_id ,[
            'role' => $role,
            'created_at' => now(),
        ]);

    }

    public function getNameAttribute($value)
    {
        return strtoupper($value);
    }

    public function getCoverImageUrlAttribute()
    {
        if($this->cover_image_path){
            return Storage::disk('public')->url($this->cover_image_path);
        }
        return 'https://placehold.co/800x300';
    }

    public function getUrlAttribute()
    {
        return route('classrooms.show' , $this->id);
    }

}
