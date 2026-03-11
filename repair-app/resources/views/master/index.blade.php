@extends('layouts.app')

@section('title', 'Панель мастера')

@section('content')
<h1>Панель мастера</h1>
<p>Заявки, назначенные на вас.</p>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Клиент</th>
            <th>Телефон</th>
            <th>Адрес</th>
            <th>Описание</th>
            <th>Статус</th>
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
                <td>
                    @if($r->status === 'assigned')
                        <form action="{{ route('master.take', $r->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm">Взять в работу</button>
                        </form>
                    @endif
                    @if($r->status === 'in_progress')
                        <form action="{{ route('master.complete', $r->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">Завершить</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="7">Нет назначенных заявок.</td></tr>
        @endforelse
    </tbody>
</table>

{{ $requests->links() }}
@endsection
