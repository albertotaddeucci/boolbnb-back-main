@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crea una Sponsorizzazione per {{ $apartment->title }}</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('admin.sponsor.store', ['apartment' => $apartment->slug]) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="sponsorship_id">Sponsorship</label>
            <select name="sponsorship_id" id="sponsorship_id" class="form-control">
                @foreach($sponsorships as $sponsorship)
                    <option value="{{ $sponsorship->id }}">
                        {{ $sponsorship->title }} - €{{ $sponsorship->price }} - {{ $sponsorship->h_duration }} ore
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="apartment_id">Appartamento</label>
            <select name="apartment_id" id="apartment_id" class="form-control">
                @foreach($apartments as $apart)
                    <option value="{{ $apart->id }}">
                        {{ $apart->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Crea Sponsorizzazione</button>
        <a class="btn my_btn" href="{{ route('admin.sponsor.show', $apartment->slug) }}">le tue sponsorizzazioni</a>



    </form>
</div>
@endsection

