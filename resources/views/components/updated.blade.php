@props(
    [
        'slot',
        'name',
        'date'
    ]
)

<span>
    {{ empty((string)$slot) ? 'Added' : $slot}} {{ $date }}
    <br>
    @isset($name)
        By {{ $name }}
    @endisset
</span>
