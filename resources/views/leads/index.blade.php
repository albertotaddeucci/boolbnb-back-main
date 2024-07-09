@extends('layouts.app')

@section('content')

<section id="main">
    <div class="container py-2">

        

        <div class="row">

        </div>
        
        <div class="position-relative" >
            <h1 class="text-center">Messaggi</h1>
            <div class="position-absolute top-0 ps-2 pt-1 pt-md-2 pt-lg-3">
                <a class="text-black" href="{{route('admin.apartments.show', $slug)}}"><i class="fa-solid fa-arrow-left"></i></a>
                
            </div>
        </div>
        
        <div class="container">
            <div class="row p-5 justify-content-center form-container rounded rounded-3">
                @forelse ($leads as $lead)
                <div id="" class="mb-3 border rounded rounded-2 bg-light">
                    <div class="row p-1 m-1">
                        <div class="row p-2">
                            <div class="col-12 col-lg-6">
                                <div class="pt-1 pt-lg-0">
                                    <div>   
                                        <strong>
                                            {{$lead->name}} {{$lead->surname}}
                                        </strong>
                                    </div>
                                    <div>
                                        <small>
                                            Email: {{$lead->mail_address}}
                                        </small>
                                    </div>
                                </div>
                                <div>
                                    <small>Ricevuto: {{$lead->created_at}}</small>
                                </div>
                            </div>

                            <div class="col-12 col-lg-6">
                                <h6 class="pt-3 pt-lg-0">
                                    <strong>Messaggio:</strong>
                                </h6>
                                <div>
                                    {{$lead->message}}
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center">
                    Nessun messaggio
                    <hr>
                </div>
                @endforelse
            </div>

        </div>
    </div>
</section>

@endsection
