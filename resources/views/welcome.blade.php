@extends('layouts.app')
@section('content')
<div class="bg-home">

    <div class="row w-75 pt-5 ps-3">

        <img src="{{asset('storage/bnb_images/BoolBnBLogo.png')}}" class="img-fluid pt-5">

        <div class="col-12 my-auto text-center  " id="btn-home">
            <a class="btn my_btn mt-4" href="{{route('admin.apartments.index')}}">Accedi</a>
        </div>
        
    </div>

    

</div>


<style >


</style>

@endsection