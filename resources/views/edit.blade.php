@extends('layout.app')
@section('content')
<div class="container">
    <div class="row">
         
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center">Properties list edit</div>
            </div>
            <add-edit-component :property="{{$property}}" :types="{{$types}}"></add-edit-component>
        </div>
    </div>
</div>
@endsection