<div class="car-items-listing">
    @foreach ($cars as $car)
        <x-car-item :$car />
    @endforeach
</div>

{{ $cars->onEachSide(1)->links('pagination') }}