@props([
    'type' => 'text' ,  
    'name' ,
    'value' => '' ,   
    ])

<textarea 
           name="{{$name}}" 
           id="{{$id ?? $name}}" 
           {{ $attributes
           ->class(['form-control' , 'is-invalid'=> $errors->has($name)])}}
>{{ old($name , $value)}}</textarea>