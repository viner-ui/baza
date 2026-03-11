@extends('layouts.app')

@section('title', 'Вход')

@section('content')
<div class="page-header">
    <h1>Вход в систему</h1>
    <p>Выберите пользователя или введите email и пароль.</p>
</div>

<div class="card" style="max-width: 420px;">
    <form method="POST" action="{{ route('login') }}">
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
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                <input type="checkbox" name="remember"> Запомнить меня
            </label>
        </div>
        <button type="submit" class="btn">Войти</button>
    </form>
</div>

<div class="hint">
    <strong>Тестовые пользователи</strong> (пароль везде <code>password</code>):<br>
    Диспетчер: <code>dispatcher@repair.local</code><br>
    Мастера: <code>master1@repair.local</code>, <code>master2@repair.local</code>
</div>
@endsection
