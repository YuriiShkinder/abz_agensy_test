@extends('layouts.site')

@section('header')
    @include('header')
@endsection

@section('content')
    <div class="crud__content">
        <aside class="crud__aside">
            <form class="crud__form" action="#">
                <label for="department">Отдел</label>
                <select id="department">
                    @foreach($departments as $item)
                        <option {{$department === $item ? 'selected' : '' }} value="{{route('employeesDepartment', ['department' => $item->slug])}}" > {{$item->name}} </option>
                    @endforeach
                </select>

                <label for="sort">Сортировать</label>
                <select id="sort">
                    @include('layouts.fields')
                </select>
                <div class="order" data-url="{{route('orderByEmployees')}}">
                    <label>po ubivaniu
                        <input name="orderBy" type="radio" value="desc" checked>
                    </label>
                    <br>
                    <label>po vozrostaniyu
                        <input name="orderBy" type="radio" value="asc">
                    </label>
                </div>
                <a href="{{route('showEmployeeModalForm')}}" class="btn addUser">Add User</a>
            </form>
        </aside>
        @if($employees->isNotEmpty())
        <div class="employees">
            <div class="employees__onload">
                <div class="load"></div>
            </div>
            <form action="{{route('searchEmployees')}}" class="search__form">
                <p>Search employee:</p>
                <select name="field">
                    @include('layouts.fields')
                </select>
                <input type="search" name="value" placeholder="more than 3 symbols">
            </form>
            @include('employeesItem')
        </div>
        @endif
    </div>
    <div class="modal">
        <div class="modal__close">X</div>
    </div>
@endsection