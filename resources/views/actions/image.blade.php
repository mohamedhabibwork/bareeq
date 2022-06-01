@if (is_array($image))
    @foreach($image as $img)
        <img loading="lazy" src="{{ $img }}" alt="{{ $id }}" width="{{  $width ?? 100 }}" height="{{ $height ?? 100 }}"/>
    @endforeach
@else
    <img loading="lazy" src="{{ $image }}" alt="{{ $id }}" width="{{  $width ?? 100 }}" height="{{ $height ?? 100 }}"/>
@endif
