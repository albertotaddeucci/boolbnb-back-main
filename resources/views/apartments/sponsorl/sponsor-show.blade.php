@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Sponsorizzazioni per {{ $apartment->title }}</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <table class="table">
        <thead>
            <tr>
                <th>Titolo Sponsorizzazione</th>
                <th>Data Inizio</th>
                <th>Data Fine</th>
                <th>Durata</th>
                <th>Durata Rimanente</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sponsorships as $sponsorship)
                <tr>
                    <td>{{ $sponsorship->title }}</td>
                    <td>{{ $sponsorship->pivot->start_sponsorship }}</td>
                    <td>{{ $sponsorship->pivot->end_sponsorship }}</td>
                    <td>{{ $sponsorship->h_duration }} ore</td>
                    <td>
                        <span id="timer-{{ $sponsorship->id }}"></span>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                function startTimer(duration, display) {
                                    var timer = duration, hours, minutes, seconds;
                                    setInterval(function () {
                                        hours = parseInt(timer / 3600, 10);
                                        minutes = parseInt((timer % 3600) / 60, 10);
                                        seconds = parseInt(timer % 60, 10);

                                        hours = hours < 10 ? "0" + hours : hours;
                                        minutes = minutes < 10 ? "0" + minutes : minutes;
                                        seconds = seconds < 10 ? "0" + seconds : seconds;

                                        display.textContent = hours + ":" + minutes + ":" + seconds;

                                        if (--timer < 0) {
                                            timer = 0;
                                        }
                                    }, 1000);
                                }

                                var now = new Date().getTime();
                                var end = new Date('{{ \Carbon\Carbon::parse($sponsorship->pivot->end_sponsorship) }}').getTime();
                                var duration = Math.floor((end - now) / 1000);
                                var display = document.querySelector('#timer-{{ $sponsorship->id }}');
                                if (duration > 0) {
                                    startTimer(duration, display);
                                } else {
                                    display.textContent = '00:00:00';
                                }
                            });
                        </script>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

