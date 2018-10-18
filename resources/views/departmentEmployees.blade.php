@if($employees)
<div class="departments__content">
    <div class="director">
        <p class="name">{{$employees->first_name.' '.$employees->last_name}}</p>
        <p class="position">{{$employees->position->name}}</p>
    </div>
    <ul class="subordinate " data-hierarchy="2">
        @if($employees->subordinate->isNotEmpty())
            @foreach($employees->subordinate as $subordinate)
                <li class="subordinate__item" data-url="{{route('employeeSubordinates',['employee' => $subordinate->hash ])}}">
                    <p class="name">{{$subordinate->first_name.' '.$subordinate->last_name}}</p>
                    <p class="position">{{$subordinate->position->name}}</p>
                    <div class="show_subordinate"><img src="{{asset('img/next.svg')}}" alt=""></div>
                </li>
            @endforeach
        @else
            <p>No records</p>
        @endif
    </ul>
</div>
@else
    <p>No records</p>
@endif