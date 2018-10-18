@if(!isset($employee))

        <form action="{{route('createEmployee',['department' => $department->slug])}}" method="post" enctype="multipart/form-data">
@else
        <form class="employeeEdit" action="{{route('editEmployee',['employee' => $employee->hash])}}" method="post" enctype="multipart/form-data">
@endif
    <div class="user__info">
        <label> Имя
            <input type="text" name="first_name" value="{{$employee->first_name ?? ''}}" placeholder="new user first name">
        </label>
        <label> Фамилия
            <input type="text" name="last_name" value="{{$employee->last_name ?? ''}}" placeholder="new user last name">
        </label>
        <label> з-п
            <input type="text" name="salary" value="{{$employee->salary ?? ''}}" placeholder="new user salary">
        </label>
        <label> Дата приема на работу
            <input type="text" name="data_reception" value="{{$employee->data_reception ?? ''}}" placeholder="new user employment date">
        </label>
        @if(isset($employee) && $employee->boss->isNotEmpty())
            <label> Начальник
                <input  type="search"  placeholder="search boss from last name" value="{{isset($employee) ? $employee->boss->first()->first_name. ' ' . $employee->boss->first()->last_name  : ''}}"  data-url="{{route('searchBoss',['department' => $department->slug])}}">
                <input id="bossHash" value="{{$employee->boss->first()->hash ?? ''}}"   type="hidden" name="boss" placeholder="new user boss">
            </label>
            <div class="search__boss"></div>
        @elseif(!isset($employee))
            <label> Начальник
                <input  type="search"  placeholder="search boss from last name"   data-url="{{route('searchBoss',['department' => $department->slug])}}">
                <input id="bossHash" type="hidden" name="boss" placeholder="new user boss">
            </label>
            <div class="search__boss"></div>
        @endif

        <label> Должность
            <select name="position">
                @foreach($positions as $position)

                    <option  {{isset($employee) && $employee->position_id == $position->id ? 'selected' : '' }} value="{{$position->hash}}">{{$position->name}}</option>

                @endforeach
            </select>
        </label>

        <button type="submit"  class="btn">Добавить</button>
    </div>
    <div class="file__input">
        <div class="file__upload">
            <label>
                <input type="file"  name="img">
                <span>Выбрать фото</span>
            </label>
        </div>
        <img class="upload_image" src="{{$employee->img ?? ''}}" alt="">
        <div class="upload_image_container"></div>
    </div>
</form>

