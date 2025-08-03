    @props(['name', 'label' => null, 'value' => old($name), 'rows' => 4])

    <div class="mb-4">
        @if ($label)
            <label for="{{ $name }}"
                class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
        @endif

        <textarea name="{{ $name }}" id="{{ $name }}" rows="{{ $rows }}"
            {{ $attributes->merge(['class' => 'w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-500 focus:outline-none']) }}>{{ $value }}</textarea>
    </div>
