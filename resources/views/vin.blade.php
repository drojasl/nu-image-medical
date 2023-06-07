@extends('layouts.vin')

@section('content')
<div class="container">
    <div class="flex justify-center flex-wrap">
        <form action="{{ route('vin.search') }}" class="space-y-1" method="POST">
            @csrf
            <label for="vin" class="w-full md:w-auto font-bold">{{ __('Insert VIN') }}</label>
            <input type="text" id="vin" name="vin" class="w-full md:w-auto border rounded-md px-2 py-1 mx-3">
            <button type="submit" class="w-full md:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md mx-3">{{ __('Search') }}</button>
        </form>
    </div>

    <div class="container my-4 lg:w-3/4" v-if="{{ json_encode($search) }}">
        <h1 class="my-4 text-blue-600 font-bold">VIN</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($vin as $key => $item)
                <div class="bg-blue-100 p-4 rounded">
                    <div class="text-blue-600 font-bold">
                        {{ __('vin.' . $key) }}
                    </div>
                    <div  class="text-blue-500">{{ $item }}</div>
                </div>
            @endforeach

        </div>

        <h1 class="my-4 text-green-600 font-bold">Salvage</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($salvage as $key => $item)
                <div class="bg-green-100 p-4 rounded">
                    <div class="text-green-600 font-bold">
                        {{ __('salvage.' . $key) }}
                    </div>
                    @if (is_array($item))
                        <ul>
                        @foreach($item as $subitem)
                            <li>{{ $subitem }}</li>
                        @endforeach
                        </ul>
                    @else
                        <div  class="text-green-500">{{ $item }}</div>
                    @endif
                </div>
            @endforeach

        </div>
    
    </div>
</div>
@endsection

@push('scripts')
<script>
    const app = new Vue({
        el: '#app',
        data: {
            myVar: ['Ola', 'k', 'Ase'],
            array: @json($vin)
        }
    });
</script>
@endpush