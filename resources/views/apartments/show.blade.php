@extends('layouts.app')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Ottiene l'ID dell'appartamento dal backend Laravel
        const apartmentId = {{$apartment->id}};
        let defaultYear = '2024'; // Anno predefinito
        let selectedYear = defaultYear; // Anno selezionato inizialmente

        // Funzione per ottenere i dati delle visite per un determinato anno
        const getVisitsData = (year) => {
            return axios.get(`/api/visits/${apartmentId}?year=${year}`);
        };

        // Funzione per ottenere i dati dei messaggi per un determinato anno
        const getMessagesData = (year) => {
            return axios.get(`/api/messages/${apartmentId}/${year}`);
        };

        // Funzione per ottenere le etichette (mesi) per un determinato anno
        const getLabelsForYear = (year) => {
            return ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'];
        };

        // Funzione per generare un colore casuale in formato RGBA
        const getRandomColor = () => {
            return `rgba(${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, 0.2)`;
        };

        // Funzione per aggiornare il grafico delle visite
        const updateChart = (year) => {
            getVisitsData(year)
                .then(res => {
                    const visitsData = res.data.result; // Dati delle visite
                    const labels = getLabelsForYear(year); // Etichette dei mesi
                    const data = Array(12).fill(0); // Inizializza i dati a zero

                    // Assegna i dati delle visite ai mesi corretti
                    visitsData.forEach(visit => {
                        const monthIndex = visit.month - 1; 
                        data[monthIndex] = visit.total_visits;
                    });

                    // Aggiorna il grafico con i nuovi dati
                    myChart.data.labels = labels;
                    myChart.data.datasets[0].data = data;
                    myChart.update();
                })
                .catch(error => {
                    console.error('Errore nel caricamento dei dati delle visite:', error);
                });
        };

        // Funzione per aggiornare il grafico dei messaggi
        const updateMessageChart = (year) => {
            getMessagesData(year)
                .then(res => {
                    const messageData = res.data.result; // Dati dei messaggi
                    const labels = getLabelsForYear(year); // Etichette dei mesi
                    const data = Array(12).fill(0); // Inizializza i dati a zero

                    // Assegna i dati dei messaggi ai mesi corretti
                    for (const [month, count] of Object.entries(messageData)) {
                        const monthIndex = parseInt(month) - 1;
                        data[monthIndex] = count;
                    }

                    // Aggiorna il grafico con i nuovi dati
                    myMessageChart.data.labels = labels;
                    myMessageChart.data.datasets[0].data = data;
                    myMessageChart.update();
                })
                .catch(error => {
                    console.error('Errore nel caricamento dei dati dei messaggi:', error);
                });
        };

        // Funzione per configurare un nuovo grafico
        const setupChart = (canvasId, label, backgroundColor) => {
            return new Chart(
                document.getElementById(canvasId).getContext('2d'),
                {
                    type: 'bar',
                    data: {
                        labels: getLabelsForYear(defaultYear), // Etichette dei mesi
                        datasets: [{
                            label: label,
                            backgroundColor: backgroundColor,
                            borderColor: 'rgb(255, 99, 132)',
                            borderWidth: 1,
                            hoverBackgroundColor: 'rgba(255, 99, 132, 0.4)',
                            hoverBorderColor: 'rgb(255, 99, 132)',
                            data: Array(12).fill(0), // Dati iniziali a zero
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true // La scala Y parte da zero
                            }
                        }
                    }
                }
            );
        };

        // Crea i grafici delle visite e dei messaggi
        let myChart = setupChart('myChart', 'Statistiche Visualizzazioni', Array(12).fill('').map(() => getRandomColor()));
        let myMessageChart = setupChart('messageChart', 'Statistiche Messaggi', Array(12).fill('').map(() => getRandomColor()));

        // Funzione per gestire il cambio di anno e aggiornare i grafici
        const handleYearChange = () => {
            updateChart(selectedYear);
            updateMessageChart(selectedYear);
            document.getElementById('yearLabel').textContent = selectedYear; // Aggiorna l'etichetta dell'anno
        };

        // Gestisce il clic sul pulsante per l'anno precedente
        document.getElementById('prevYear').addEventListener('click', () => {
            selectedYear = selectedYear === '2024' ? '2023' : '2022'; // Cambia l'anno selezionato
            handleYearChange(); // Aggiorna i grafici
        });

        // Gestisce il clic sul pulsante per l'anno successivo
        document.getElementById('nextYear').addEventListener('click', () => {
            selectedYear = selectedYear === '2022' ? '2023' : '2024'; // Cambia l'anno selezionato
            handleYearChange(); // Aggiorna i grafici
        });

        // Imposta l'etichetta dell'anno inizialmente
        document.getElementById('yearLabel').textContent = defaultYear;
        // Aggiorna i grafici con i dati dell'anno predefinito
        updateChart(defaultYear);
        updateMessageChart(defaultYear);


        // Codice relativo ai pagamenti
        let form = document.getElementById('payment-form');
        let client_token = "{{ $clientToken }}"; // Token per Braintree    
    
        braintree.dropin.create({
            authorization: client_token,
            container: '#dropin-container'
        }, function (createErr, instance) {
            if (createErr) {
                console.error(createErr);
                return;
            }
    
            form.addEventListener('submit', function (event) {
                event.preventDefault();
    
                instance.requestPaymentMethod(function (err, payload) {
                    if (err) {
                        console.error(err);
                        return;
                    }
    
                    let nonceInput = document.createElement('input');
                    nonceInput.name = 'payment_method_nonce';
                    nonceInput.type = 'hidden';
                    nonceInput.value = payload.nonce; // Aggiunge il nonce al form
                    form.appendChild(nonceInput);
    
                    form.submit(); // Invia il form
                });
            });
        });  

        // Gestisce il clic sul pulsante per mostrare/nascondere il box dei pagamenti
        let btn_sponsor = document.getElementById('cta-sponsor');
        btn_sponsor.addEventListener('click', function() {
            document.getElementById('box-payment').classList.toggle('d-none'); // Mostra/nasconde il box
        });

      
        
        
        

    });

    function change(value){

    console.log(value)

    const divs = document.querySelectorAll('.text-hide');

    divs.forEach(div => {
        div.classList.add('d-none');
    });


    document.querySelector('#box-description #text-description-' + value).classList.remove('d-none')
    }
    
    
</script>



<div class="container p-2">

    <div class=" apart-container p-3 my-md-3">
        <div class="row">
            <div class="col-12">

                {{-- title --}}
                <h2 class="card-title pb-1">
                    <strong>{{$apartment->title}}</strong>                
                </h2>
                {{-- room position --}}
                <div class="position pb-3">
                    {{$apartment->address}}
                </div>

                {{-- image --}}
                <img src="{{asset('storage/' . $apartment->image)}}" class="card-img-top rounded-2"
                    alt="immagine dell'appartamento" style="max-width: 100%; max-height: 300px; object-fit: cover"
                >

            </div>

            <div class="col-12 pt-2">
                {{-- room infos --}}
                <div class="row p-2 mt-1 justify-content-lg-center ">
                    <div class="col-3 col-lg-1 m2 position-relative service  text-center">
                       <small>
                        {{$apartment->squared_meters}} m²
                        </small>                             
                    </div>
                    
                    <div class="col-3 col-lg-1 rooms position-relative service text-center ">
                       <small>
                        {{$apartment->n_rooms}} camere
                        </small> 
                    </div>
                    
                    <div class="col-3 col-lg-1 beds position-relative service text-center ">
                       <small>
                        {{$apartment->n_beds}} letti
                        </small> 
                    </div>
                    
                    <div class="col-3 col-lg-1 bathrooms position-relative service text-center ">
                       <small>
                        {{$apartment->n_bathrooms}} bagni
                        </small> 
                    </div>
                    
                </div>

            </div>


            
            
        </div>
        
        <div class="row justify-content-center mt-3">

            <div class="col-12 col-lg-6 text-center">
                @if (session('success'))
                <div class="alert alert-success">
                    <strong>
                        {{ session('success') }}
                    </strong>   
                </div>
                @endif

            </div>
            

        </div>

        <div class="row mt-3">

            <div class="col-12 col-md-6 col-lg-4">


                {{-- room description --}}
                @if($apartment->description)
                <p>
                    {{$apartment->description}}
                </p>                  
                @else
                    <p>Nessuna descrizione disponibile</p>
                @endif

            </div>


            <div class="col-12 col-md-6 col-lg-4 my-3 my-md-2">
                
                    
                <div class="bg-white rounded-2 p-2">
                    <strong class="p-1">
                        Servizi disponibili
                    </strong>
                    <ul id="services-list" class="d-flex gap-4  pt-2 overflow-x-auto" >
                        @foreach($apartment->services as $service)
                        <li class="d-flex align-items-center flex-column flex-shrink-0">
                            <div>
                                <i class='{{$service->icon}}'></i>
                            </div>
                            <div>
                                {{$service->name}}
        
                            </div>
                        </li>
                        @endforeach
                    </ul>

                </div>

            </div>

            <div class="col-12 col-md-12 col-lg-4  text-white" >

                <a href="{{route('admin.leads.index', $apartment->id)}}" class="btn btn-cta mt-2 mb-3 w-100"><i class="fa-solid fa-message me-3"></i><strong>Messaggi ricevuti</strong></a>
                
                
                <button id="cta-sponsor" class="btn btn-cta mb-4 w-100" data-bs-toggle="modal"
                data-bs-target="#showPayment"
                >
                    <strong><i class="fa-solid fa-crown me-3 "></i>Attiva la sponsorizzazione</strong>
                </button>

                <div id="box-payment" class="d-none">

                    {{-- <button type="button" class="btn btn-outline-danger w-100" data-bs-toggle="modal"
                    data-bs-target="#showPayment">
                        Attiva
                    </button> --}}

                    <div class="modal fade" id="showPayment" tabindex="-1" aria-hidden="true" >
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header " style="background-color: #0067697b; color:#4f4f4f">
                                    <h1 class="modal-title fs-5">Attiva la sponsorizzazione</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" style="background-color: #00676939;">
                                    <form id="payment-form" action="{{ route('admin.payment.process') }}"  method="POST">
                                        @csrf
                                        <input type="hidden"  name="apartment_id" value="{{ $apartment->id }}">
                                        <select id="sponsorship_id" class="form-select mb-3"  name="sponsorship_id" onclick="change(value)">
                                            <option >Seleziona sponsorizzazione</option>
                                            @foreach ($sponsorships as $sponsorship)
                                            <option  class="option" value="{{$sponsorship->id}}">{{$sponsorship->title}}</option>
                            
                                            @endforeach
                    
                                        </select>
                    
                                        <div id="box-description" class="box-description" >
                    
                    
                                            <div id="text-description-1" class="text-hide d-none ">
                                                <strong>Costo</strong>: {{$sponsorships[0]->price}}€ <br>
                                                <strong>Durata</strong>: 24 ore <br>
                                                {{$sponsorships[0]->description}}
                                            </div>
                                            <div id="text-description-2" class="text-hide d-none">
                                                <strong>Costo</strong>: {{$sponsorships[1]->price}}€ <br>
                                                <strong>Durata</strong>: 48 ore <br>
                                                {{$sponsorships[1]->description}}
                                            </div>
                                            <div id="text-description-3" class="text-hide d-none">
                                                <strong>Costo</strong>: {{$sponsorships[2]->price}}€ <br>
                                                <strong>Durata</strong>: 144 ore <br>
                                                {{$sponsorships[2]->description}}
                                            </div>
                                            
                                
                                        </div>
                    
                                        
                                        <div id="dropin-container"></div>
                    
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-payment fw-bold my-2">Acquista</button>
                    
                                        </div>
                    
                    
                                    </form>
                                   
                                </div>
                                
                            </div>
                        </div>
                    </div>

            
    
                </div>


            </div>   

        </div>


        <div class="row justify-content-center p-2 gap-1">            
            

           
            <!-- Modal -->
            <div class="modal fade" id="deleteRoomModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5">{{$apartment->title}}</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Sei sicuro di voler eliminare l'appartamento {{ $apartment->title }}?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                            <form action="{{route('admin.apartments.destroy', $apartment)}}" method="POST">
                                @csrf
                                @method("DELETE")
                                <button class="btn btn-danger">Elimina</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        
        <h4 class="pt-1 stats position-relative">Statistiche</h4>
        <div class="d-flex justify-content-center align-items-center position-relative">
            <span id="prevYear" class="year-nav">&lt;</span>
            <span id="yearLabel" class="mx-3"></span>
            <span id="nextYear" class="year-nav">&gt;</span>
        </div>

        <div class="row">
            <div class="col-12 col-lg-6">
                <canvas id="myChart" width="400" height="200"></canvas>

            </div>
            <div class="col-12 col-lg-6">
                <canvas id="messageChart" width="400" height="200"></canvas>

            </div>


        </div>

        
        

        <div class="row pt-4">

            <div class="col-6">
                {{-- link to room edit page --}}
                <a href="{{route('admin.apartments.edit', $apartment)}}" class="btn cta-warning w-100"><strong>Modifica</strong></a>

            </div>
            <div class="col-6">
                <!-- Button trigger modal -->
                <button type="button" class="btn cta-danger w-100 shake-horizontal" data-bs-toggle="modal"
                    data-bs-target="#deleteRoomModal">
                    <strong>Elimina</strong>
                </button>

            </div>

        </div>


    </div>
</div>

<script>



</script>

<style>
    

    #services-list {
        list-style-type: none;
    }

    .year-nav {
        cursor: pointer;
        border-radius: 50%;
    }
    
    #services-list::-webkit-scrollbar {
    height: 2px;
    
    }
    #services-list::-webkit-scrollbar-track {
    background-color: white;
    /* box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.5); */
    border-radius: 2px;
    }
    #services-list::-webkit-scrollbar-thumb {
    background-color: black;
    border-radius: 10px;
}
</style>


@endsection