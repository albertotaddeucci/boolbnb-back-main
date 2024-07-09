@extends('layouts.app')

@section('content')

<body class="bg-form ">
  <div class="container py-4">
    <h1 class="mt-3 mb-5 text-center">Aggiungi un nuovo appartamento</h1>
    <div class="form-container p-5 rounded-3">
      <form action="{{route('admin.apartments.store')}}" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
        @csrf
        <div class="row">
          @if (session('error'))
          <div class="alert alert-danger">
            {{ session('error') }}
          </div>
          @endif
          <div class="mb-4 col-12 col-sm-6">
            <label for="title" class="fw-bold form-label">Nome della struttura <span class="obg-field">*</span></label>
            <input type="text" class="required-field form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
            @error('title')
            <div class="invalid-feedback">
              {{$message}}
            </div>
            @enderror
          </div>
          <div class="mb-4 col-12 col-sm-6" id="address-box">
            <label for="address" class="fw-bold form-label">Indirizzo <span class="obg-field">*</span></label>
            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="" onkeyup="handleKeyUp()">
            <div class="auto-complete-box hide">
              <ul id="suggested-roads-list" class="list-group">
              </ul>
            </div>
            @error('address')
            <div class="invalid-feedback">
              {{$message}}
            </div>
            @enderror
          </div>
        </div>
        <div class="mb-4">
          <label for="description" class="fw-bold form-label">Descrizione</label>
          <textarea type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ old('description') }}</textarea>
          @error('description')
          <div class="invalid-feedback">
            {{$message}}
          </div>
          @enderror
        </div>
        <div class="mb-4">
          <label for="image" class="fw-bold form-label">Anteprima</label>
          <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
          @error('image')
          <div class="invalid-feedback">
            {{$message}}
          </div>
          @enderror
        </div>
        <div class="row">
          <div class="mb-4 col-12 col-sm-6">
            <label for="n_rooms" class="fw-bold form-label">Numero stanze <span class="obg-field">*</span></label>
            <input type="number" class="required-field form-control @error('n_rooms') is-invalid @enderror" id="n_rooms" value="{{ old('n_rooms') }}" name="n_rooms" min="1" max="100" required>
            @error('n_rooms')
            <div class="invalid-feedback">
              {{$message}}
            </div>
            @enderror
          </div>
          <div class="mb-4 col-12 col-sm-6">
            <label for="n_beds" class="fw-bold form-label">Numero letti <span class="obg-field">*</span></label>
            <input type="number" class="required-field form-control @error('n_beds') is-invalid @enderror" id="n_beds" value="{{ old('n_beds') }}" name="n_beds" min="1" max="100" required>
            @error('n_beds')
            <div class="invalid-feedback">
              {{$message}}
            </div>
            @enderror
          </div>
        </div>
        <div class="row">
          <div class="mb-4 col-12 col-sm-6">
            <label for="n_bathrooms" class="fw-bold form-label">Numero bagni <span class="obg-field">*</span></label>
            <input type="number" class="required-field form-control @error('n_bathrooms') is-invalid @enderror" id="n_bathrooms" value="{{ old('n_bathrooms') }}" name="n_bathrooms" min="1" max="100" required>
            @error('n_bathrooms')
            <div class="invalid-feedback">
              {{$message}}
            </div>
            @enderror
          </div>
          <div class="mb-4 col-12 col-sm-6">
            <label for="squared_meters" class="fw-bold form-label">Metri quadri <span class="obg-field">*</span></label>
            <input type="number" class="required-field form-control @error('squared_meters') is-invalid @enderror" id="squared_meters" value="{{ old('squared_meters') }}" name="squared_meters" min="1" max="100" required>
            @error('squared_meters')
            <div class="invalid-feedback">
              {{$message}}
            </div>
            @enderror
          </div>
        </div>
        <div class="mb-4">
          <label class="mb-4 fw-bold">Servizi <span class="obg-field">*</span></label>
          <div class="d-flex gap-4 flex-wrap ">
            @foreach($services as $service)
            <div class="form-check @error('services') is-invalid @enderror ">
              <input type="checkbox" name="services[]" value="{{$service->id}}" class="btn-check required-check" id="btn-check-{{$service->id}}" {{ in_array($service->id, old('services', [])) ? 'checked' : '' }}>
              <label for="btn-check-{{$service->id}}" class="btn service-btn">{{$service->name}}</label>
            </div>
            @endforeach
            @error('services')
            <div class="invalid-feedback">
              {{$message}}
            </div>
            @enderror
          </div>
        </div>
        <div class="form-check mb-5">
          <input class="form-check-input" type="checkbox" name="is_visible" id="is_visible" value="1" {{ old('is_visible') ? 'checked' : '' }}>
          <label class="fw-bold form-check-label" for="is_visible" style="user-select: none">Vuoi rendere l'appartamento visibile agli utenti?</label>
        </div>
        <button type="submit" class="btn btn-cta w-100" id="btn-submit"></i>Inserisci</button>
      </form>

      <div class="mt-3 obg-field">
        <small>(*)Campo obbligatorio</small>
      </div>

    </div>

  </div>

</body>

<script>
  // Variabile flag, in base al suo valore (true o false) permette l'invio o meno dei dati del form
let flag = false;

// Funzione per verificare lo stato di compilazione di tutti i campi obbligatori
function checkRequiredFields() {
  // Recupera tutti i campi obbligatori
  const requiredFields = document.querySelectorAll('.required-field');
  
  // Recupera tutte le checkbox 
  const requiredChecks = document.querySelectorAll('.required-check');
  
  // Verifica se tutti i campi obbligatori sono stati compilati
  let allFieldsFilled = true;
  requiredFields.forEach(field => {
      if (field.value.trim() === '') {
          allFieldsFilled = false;
      }
  });

  let checkBoxChecked = false;
  requiredChecks.forEach(check => {
      if (check.checked == true) {
        checkBoxChecked = true;
      }
  });

  // Abilita o disabilita il pulsante di invio in base allo stato di compilazione dei campi
  document.querySelector('#btn-submit').disabled = !(allFieldsFilled && checkBoxChecked && flag);
}

// Aggiorna lo stato dei campi obbligatori ogni volta che un campo viene modificato
document.querySelectorAll('.required-field').forEach(field => {
    field.addEventListener('input', checkRequiredFields);
});


document.querySelectorAll('.required-check').forEach(check => {
    check.addEventListener('input', checkRequiredFields);
});

// Controllo sull'input dell'indirizzo
if (document.getElementById('address').value.trim() == '') {
    // Il campo è vuoto quindi il bottone è spento
    document.querySelector('#btn-submit').disabled = true;
} 


let streets = [];

// Valida l'invio del form
function validateForm() {
  console.log(flag)
  // Controllo del flag
  if (flag) {
      // Return true
      return flag;
  } else {
      // Return false
      return flag;
  }
}

document.querySelector('#address').addEventListener('click', function () {
  // Variabile settata a false così da non poter inviare i dati del form
  flag = false;

  // Il bottone viene disattivato ogni qual volta si scrivono o cancellano caratteri
  document.querySelector('#btn-submit').disabled = true;
});

function handleKeyUp(event) {
  // La variabile viene settata a false ogni volta che vengono inseriti o cancellati caratteri
  flag = false;

  // Lista delle vie suggerite
  const UlEle = document.getElementById('suggested-roads-list');
  UlEle.innerHTML = '';

  // Valore del campo dell'indirizzo
  const input = document.getElementById('address').value;

  // Controllo sull'input che non sia vuoto
  if (input.trim() != '') {
      // Il bottone viene disattivato ogni qual volta si scrivono o cancellano caratteri
      document.querySelector('#btn-submit').disabled = true;

      axios.get('http://127.0.0.1:8000/api/autocomplete-address?query=' + input)
          .then(response => {
              // Inserita l'array dei risultati in un array locale
              streets = response.data.result.results;
              console.log(streets);
          })
          .catch(error => {
              console.error(error);
          });
  } else {
      document.querySelector('.auto-complete-box').classList.add('hide');

      // controlla che tutti i campi siano stati compilati o meno e disattiva e attiva il pulsate in relazione alla scansione
      checkRequiredFields()
  }

  if (streets.length != 0) {
      for (let i = 0; i < streets.length; i++) {
          const liEl = document.createElement('li');
          const divEl = document.createElement('div');
          divEl.innerText = streets[i].address.freeformAddress + ', ' + streets[i].address.country;
          liEl.append(divEl);
          liEl.classList.add('list-group-item', 'list-group-item-action');
          UlEle.append(liEl);

          liEl.addEventListener('click', function () {
              flag = true;
              // Viene inserito la via scelta nella casella dell'indirizzo
              document.getElementById('address').value = liEl.innerText;

              // Il menu viene nascosto
              document.querySelector('.auto-complete-box').classList.add('hide');
              
              // controlla che tutti i campi siano stati compilati o meno e disattiva e attiva il pulsate in relazione alla scansione
              checkRequiredFields()
          });
      }
      document.querySelector('.auto-complete-box').classList.remove('hide');
  } else {
      document.querySelector('.auto-complete-box').classList.add('hide');
      
      // controlla che tutti i campi siano stati compilati o meno e disattiva e attiva il pulsate in relazione alla scansione
      checkRequiredFields()
  }
}
</script>
@endsection