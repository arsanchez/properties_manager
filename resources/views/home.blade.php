@extends('layout.app')
@section('content')
<div class="container">
    <div class="row">
         
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center">Properties list search</div>
            </div>
            <search-component :types="{{$types}}"></search-component>
        </div>
    </div>
</div>
@endsection