@extends('layouts.app')

@section('title', 'Вход')

@section('content')
<h1>Вход в систему</h1>
<p>Выберите пользователя или введите email и пароль.</p>

<form method="POST" action="{{ route('login') }}" class="form-group" style="max-width: 400px;">
    @csrf
    <div class="form-group">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
               list="user-list" placeholder="например master1@repair.local">
        <datalist id="user-list">
            @foreach($users as $u)
                <option value="{{ $u->email }}">{{ $u->name }} ({{ $u->role }})</option>
            @endforeach
        </datalist>
    </div>
    <div class="form-group">
        <label for="password">Пароль</label>
        <input id="password" type="password" name="password" required placeholder="password">
    </div>
    <div class="form-group">
        <label><input type="checkbox" name="remember"> Запомнить</label>
    </div>
    <button type="submit" class="btn">Войти</button>
</form>

<p style="margin-top:1.5rem; color:#666; font-size:0.9rem;">
    Тестовые пользователи (пароль везде <code>password</code>):<br>
    Диспетчер: dispatcher@repair.local<br>
    Мастера: master1@repair.local, master2@repair.local
</p>
@endsection
