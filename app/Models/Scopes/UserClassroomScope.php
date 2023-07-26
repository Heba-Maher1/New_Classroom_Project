<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserClassroomScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if($id = Auth::id()){
            $builder
                ->where(function(Builder $query) use($id){
                    $query->where('user_id' , '=',$id)
                          ->orWhereExists(function(QueryBuilder $query) use ($id) {
                                $query->select(DB::raw('1'))
                                      ->from('classroom_user')
                                      ->whereColumn('classroom_id' , '=','classrooms.id')
                                      ->where('user_id' , '=',$id);
                          });
                });
        }






        // if($id = Auth::id())
        // {
        //     $builder->where('user_id' ,'=',$id);
        // }      
        
        
        // if ($id = Auth::id()) {
        //     $builder
        //     ->where(function(Builder $query) use($id){
        //        $query->where('user_id', '=', $id)
        //         ->orWhereExists(function (QueryBuilder $query) use($id) {
        //             $query->select(DB::raw('1'))
        //             ->from('classroom_user')
        //             ->whereColumn('classroom_id' , '=', 'classrooms.id')
        //             ->where('user_id' , '=',$id);
        //         });
        //     });
        // }
    }

    // select * from classrooms
    //where user_id = ?
    // or classrooms.id in (delect classroom_id from classroom_user where user_id = ?)
}
