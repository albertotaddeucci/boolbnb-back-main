@extends('layouts.app')

@section('content')

<section>
    <div class="row m-0">
        <div class="d-none d-md-block col-md-3 col-lg-2">
            <div id="lateral-nav" class="row p-2">
                <div class="col-12">
                    <div class="row align-items-center mb-3">
                        <div class="col-4 col-md-12">
                            <div id="img-user" class="p-3">
                                <img src="https://static.vecteezy.com/system/resources/thumbnails/005/129/844/small_2x/profile-user-icon-isolated-on-white-background-eps10-free-vector.jpg" alt="" class="img-fluid rounded-circle">
                            </div>
                        </div>
                        <div class="col-8 col-md-12 fw-bold text-center pt-4">
                            <h3>Benvenuto {{ Auth::user()->name }}</h3>
                        </div>
                    </div>
                    <div id="nav-list" class="row mt-5">
                        <div class="col-12 py-2">
                            <a class="btn my_bg_color fw-bold w-100" href="{{ url('http://localhost:5174/') }}">BoolBnB</a>
                        </div>
                        <div class="col-12 py-2">
                            <a class="btn my_bg_color fw-bold w-100" href="{{ url('profile') }}">{{__('Profilo')}}</a>
                        </div>
                        <div class="col-12 py-2">
                            <a class="btn my_bg_color fw-bold w-100" href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-10 col-md-9  flex-grow-1">
            <h2 class="fs-4 text-secondary ms-4 pt-4 pb-2 text-center text-md-start">Appartamenti</h2>
            <div class="container">
                <div class="row justify-content-center py-2 row-gap-2 p-1">
                    <div class="col-12 col-lg-6">
                    <a class="btn my_bg_color fw-bold col-12" href="{{route('admin.apartments.create')}}">Aggiungi un nuovo appartamento</a>

                    </div>
                    <div class="col-12 col-lg-6">
                    <a class="btn my_bg_color fw-bold col-12" href="{{ route('admin.sponsor.index') }}">Vedi i tuoi appartamenti sponsorizzati</a>

                    </div>
                </div>
                </div>
                <div class="row p-1">
                    @forelse ($apartments as $apartment)
                    <div class="col-lg-6 col-md-12">

                        {{-- tile --}}
                        <div id="tile" class="row py-3 px-1">
                            <div id="img-container" class="col-6 col-sm-4 col-lg-4  rounded-2">
                                <img src="{{asset('storage/' . $apartment->image)}}" class="img-fluid">
                            </div>
                            <div class="col-6 col-sm-8 col-lg-8 text-center text-sm-start">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="fw-bold pt-1 pb-2 pb-sm-1">{{$apartment->title}}</div>
                                        <div class="address d-none d-sm-block pb-sm-1">{{$apartment->address}}</div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-center justify-content-sm-end">
                                        <div>
                                            <a class="btn my_btn" href="{{route('admin.apartments.show', $apartment)}}">Visualizza</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center">
                        Nessun appartamento disponibile
                        <hr>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
