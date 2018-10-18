<h1 class="company">Abz.agensy test</h1>
<div class="buttons">
    @guest
        <button class="btn login">Login</button>
    @else
        <button class="btn logout"><a href="{{route('logout')}}">Logout</a></button>

        @if(url()->current() === route('employeesDepartment'))
            <button class="btn crud"><a href="{{route('showDepartments')}}">Home</a></button>
        @else
            <button class="btn crud"><a href="{{route('employeesDepartment')}}">CRUD</a></button>
        @endif
    @endguest
</div>
<form action="{{route('login')}}" method="post" class="login_window">
     @csrf
    <input type="text" name="login" placeholder="Login">
    <input type="password" name="password" placeholder="Pass">
    <button type="submit" class="btn">Login</button>
</form>