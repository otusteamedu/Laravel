{{-- resources/views/admin/products/form.blade.php --}}

<div class="mb-4">
    <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
    <input type="text" id="title" name="title" value="{{ old('title', $product->title ?? '') }}"
           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('title') border-red-500 @enderror">
    @error('title')
    <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="alias" class="block text-gray-700 text-sm font-bold mb-2">Alias (URL Slug):</label>
    <input type="text" id="alias" name="alias" value="{{ old('alias', $product->alias ?? '') }}"
           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('alias') border-red-500 @enderror">
    @error('alias')
    <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Price:</label>
    <input type="number" step="0.01" id="price" name="price" value="{{ old('price', $product->price ?? '') }}"
           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('price') border-red-500 @enderror">
    @error('price')
    <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="order" class="block text-gray-700 text-sm font-bold mb-2">Order:</label>
    <input type="number" id="order" name="order" value="{{ old('order', $product->order ?? '0') }}"
           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('order') border-red-500 @enderror">
    @error('order')
    <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="text" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
    <textarea id="text" name="text" rows="5"
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('text') border-red-500 @enderror">{{ old('text', $product->text ?? '') }}</textarea>
    @error('text')
    <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
</div>

{{-- Поле для основного изображения (загрузка файла) --}}
<div class="mb-4">
    <label for="image_file" class="block text-gray-700 text-sm font-bold mb-2">Основное изображение:</label>
    <input type="file" id="image_file" name="image_file"
           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('image_file') border-red-500 @enderror">
    @error('image_file')
    <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
    @if(isset($product) && $product->image)
        <p class="text-sm text-gray-600 mt-1">Текущее изображение:</p>
        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->title }}" class="mt-2 h-32 w-32 object-cover rounded shadow">
        <p class="text-xs text-gray-500 mt-1">Загрузите новое изображение, чтобы заменить текущее.</p>
    @endif
</div>

{{-- Поле для дополнительных изображений (загрузка нескольких файлов) --}}
<div class="mb-4">
    <label for="images_files" class="block text-gray-700 text-sm font-bold mb-2">Дополнительные изображения:</label>
    <input type="file" id="images_files" name="images_files[]" multiple
           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('images_files.*') border-red-500 @enderror">
    @error('images_files.*')
    <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
    @if(isset($product) && is_array($product->images) && count($product->images) > 0)
        <p class="text-sm text-gray-600 mt-1">Текущие дополнительные изображения:</p>
        <div class="mt-2 flex flex-wrap gap-2">
            @foreach($product->images as $img)
                <img src="{{ Storage::url($img) }}" alt="Дополнительное изображение" class="h-24 w-24 object-cover rounded shadow">
            @endforeach
        </div>
        <p class="text-xs text-gray-500 mt-1">Загрузите новые изображения, чтобы добавить их к существующим.</p>
    @endif
</div>

<div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2">Status:</label>
    <label class="inline-flex items-center">
        <input type="checkbox" name="published" value="1" class="form-checkbox h-5 w-5 text-blue-600"
            {{ old('published', $product->published ?? false) ? 'checked' : '' }}>
        <span class="ml-2 text-gray-700">Published</span>
    </label>
</div>

<div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2">Sale Item:</label>
    <label class="inline-flex items-center">
        <input type="checkbox" name="is_sale" value="1" class="form-checkbox h-5 w-5 text-blue-600"
            {{ old('is_sale', $product->is_sale ?? false) ? 'checked' : '' }}>
        <span class="ml-2 text-gray-700">On Sale</span>
    </label>
</div>


<div class="mb-4">
    <label for="categories" class="block text-gray-700 text-sm font-bold mb-2">Categories:</label>
    <select name="categories[]" id="categories" multiple
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline h-40 @error('categories') border-red-500 @enderror">
        @foreach ($categories as $category)
            <option value="{{ $category->id }}"
                {{ (isset($product) && $product->categories->contains($category->id)) || (in_array($category->id, old('categories', []))) ? 'selected' : '' }}>
                {{ $category->title }}
            </option>
        @endforeach
    </select>
    @error('categories')
    <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
</div>

