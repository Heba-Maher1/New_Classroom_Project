<div class="form-floating mb-3">
    {{ $slot}}
    <label for="{{$name}}">{{$placeholder}}</label>
    <x-error-message name="{{$name}}" />
</div>