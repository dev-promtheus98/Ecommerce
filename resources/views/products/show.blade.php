@extends('layouts.master')

@section('content')
<div class="col-md-12">
    <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
        <div class="col p-4 d-flex flex-column position-static">
            <strong class="d-inline-block mb-2 text-success">Design</strong>
            <h5 class="mb-0">{{ $product->title }}</h5>
            <div class="mb-1 text-muted">{{ $product->created_at->format('d/m/Y') }}</div>
            <p class="card-text mb-auto">{!! $product->description !!}</p>
            <strong class="card-text mb-auto">{{ $product->getPrice() }}</strong>
            <form action="{{ route('cart.store') }}" method="post">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" class="btn btn-dark btn-block">Ajouter au panier</button>
            </form>
        </div>
        <div class="col-auto d-none d-lg-block">
            <img src="{{ asset('storage/'. $product->image) }}" width="210" height="190" id="mainimage">
            <div class="mt-2">
                @if ($product->images)
                    <img src="{{ asset('storage/'. $product->image) }}" width="50" class="img-thumbnail" alt="">
                    @foreach (json_decode($product->images, true) as $image)
                        <img src="{{ asset('storage/'. $image) }}" width="50" class="img-thumbnail" alt="">
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endSection

@section('extra-js')
    <script>
        var mainimage = document.querySelector('#mainimage');
        var thumbnails = document.querySelectorAll('.img-thumbnail');

        thumbnails.forEach((element) => element.addEventListener('click', changeImage));

        function changeImage(e){
            mainimage.src = this.src;
        }
    </script>
@endsection
