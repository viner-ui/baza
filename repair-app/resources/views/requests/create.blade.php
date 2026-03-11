@extends('layouts.app')

@section('title', 'Создание заявки')

@section('content')
<div class="page-header">
    <h1>Создание заявки в ремонтную службу</h1>
    <p>Заполните форму — заявка получит статус «Новая» и появится у диспетчера.</p>
</div>

<div class="card" style="max-width: 520px;">
    <form method="POST" action="{{ route('requests.store') }}">
        @csrf
        <div class="form-group">
            <label for="client_name">ФИО клиента *</label>
            <input id="client_name" type="text" name="client_name" value="{{ old('client_name') }}" required maxlength="255" placeholder="Иванов Иван Иванович">
        </div>
        <div class="form-group">
            <label for="phone">Телефон *</label>
            <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required maxlength="50" placeholder="+7 999 123-45-67">
        </div>
        <div class="form-group">
            <label for="address">Адрес *</label>
            <input id="address" type="text" name="address" value="{{ old('address') }}" required maxlength="500" placeholder="ул. Примерная, д. 1">
        </div>
        <div class="form-group">
            <label for="problem_text">Описание проблемы *</label>
            <textarea id="problem_text" name="problem_text" rows="4" required maxlength="2000" placeholder="Опишите неисправность...">{{ old('problem_text') }}</textarea>
        </div>
        <button type="submit" class="btn">Создать заявку</button>
    </form>
</div>
@endsection
