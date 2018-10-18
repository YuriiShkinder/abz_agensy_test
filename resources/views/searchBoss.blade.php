@if($employees->isNotEmpty())
    @foreach($employees as $employee)
        <p data-hash="{{$employee->hash}}">{{$employee->first_name}} {{$employee->last_name}}</p>
    @endforeach
@endif