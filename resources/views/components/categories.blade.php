@foreach ($categories as $category)
    <div class="{{ $category->isChild() ? 'ml-5' : null }}">
        <x-category :category="$category"/>
    </div>
@endforeach
