@extends('layouts.app')

@section('title', 'Панель диспетчера')

@section('content')
<div class="page-header">
    <h1>Панель диспетчера</h1>
    <p>Список заявок, назначение мастеров и отмена заявок.</p>
</div>

<form method="GET" action="{{ route('dispatcher.index') }}" class="filter-bar card">
    <label for="filter-status">Фильтр по статусу:</label>
    <select id="filter-status" name="status" onchange="this.form.submit()">
        <option value="">Все</option>
        @foreach(\App\Models\RepairRequest::statuses() as $s)
            <option value="{{ $s }}" @if(request('status') === $s) selected @endif>{{ $s }}</option>
        @endforeach
    </select>
</form>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Клиент</th>
                <th>Телефон</th>
                <th>Адрес</th>
                <th>Описание</th>
                <th>Статус</th>
                <th>Мастер</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requests as $r)
                <tr>
                    <td>{{ $r->id }}</td>
                    <td>{{ $r->client_name }}</td>
                    <td>{{ $r->phone }}</td>
                    <td>{{ $r->address }}</td>
                    <td>{{ Str::limit($r->problem_text, 40) }}</td>
                    <td><span class="badge badge-{{ $r->status }}">{{ $r->status }}</span></td>
                    <td>{{ $r->assignedUser?->name ?? '—' }}</td>
                    <td class="actions-cell">
                        @if($r->status === 'new')
                            <form action="{{ route('dispatcher.assign') }}" method="POST" class="form-inline">
                                @csrf
                                <input type="hidden" name="request_id" value="{{ $r->id }}">
                                <select name="master_id" required>
                                    <option value="">Выбрать</option>
                                    @foreach($masters as $m)
                                        <option value="{{ $m->id }}">{{ $m->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-sm">Назначить</button>
                            </form>
                        @endif
                        @if(in_array($r->status, ['new', 'assigned']))
                            <form action="{{ route('dispatcher.cancel', $r->id) }}" method="POST" class="form-inline" onsubmit="return confirm('Отменить заявку?');">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger">Отменить</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="8">Нет заявок.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $requests->links() }}
@endsection
