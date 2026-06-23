@props(['title' => '', 'bodyClass' => null])

<x-base-layout :$title :bodyClass>
    <x-layouts.header> </x-layouts.header>
    {{ $slot }}

</x-base-layout>
