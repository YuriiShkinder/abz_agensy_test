@extends('layouts.site')

@section('header')
    @include('header')
@endsection

@section('content')
    <div class="departments" data-rewrite-boss-employee="{{route('rewriteBossEmployee')}}">
        @foreach($departments as $department)
            <div class="departments__item"  data-url="{{route('departmentEmployees',['department' => $department->slug ])}}">
                <p>{{$department->name}}</p>
                <p>Employees - {{$department->employees_count}}</p>
                <div class="close_department"><img src="{{asset('img/left-arrow.svg')}}" alt=""></div>
            </div>
        @endforeach
    </div>
@endsection
