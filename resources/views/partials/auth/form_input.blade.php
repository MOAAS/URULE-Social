<div class="form-group">

    <label for="{{ $name . '_input'  }}" class="d-block text-left">{{ $label }}</label>
    <input id="{{ $name . '_input' }}"
        type="{{ $type }}" 
        name="{{ $name }}"
        class="form-control @error($name) is-invalid @enderror"
        @isset($placeholder)
           placeholder="{{$placeholder}}"
        @endisset
        value="{{ $value ?? '' }}"
        {{ isset($required) && $required ? 'required' : '' }}
        {{ $type == "date" ? 'max=' . date('Y-m-d') : '' }}
    >
        
    @isset($note)
        <small class="form-text text-muted">{{ $note }}</small>
    @endisset  

    @error($name)
        <p class="my-2 text-danger font-weight-bold"> {{ $message }} </p>
    @enderror  
</div>