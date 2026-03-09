@extends('layout')

@section('content')
    <div class="card">
        <div class="header" style="margin:0 0 10px 0;">
            <h2>Панель диспетчера</h2>
            <form method="GET" action="{{ route('dispatcher.index') }}" style="display:flex;gap:8px;">
                <select name="status">
                    <option value="">Все статусы</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" @selected($statusFilter === $status)>{{ $status }}</option>
                    @endforeach
                </select>
                <button class="secondary" type="submit">Фильтр</button>
            </form>
        </div>

        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Клиент</th>
                <th>Контакты</th>
                <th>Проблема</th>
                <th>Статус</th>
                <th>Мастер</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @forelse($requests as $request)
                <tr>
                    <td>{{ $request->id }}</td>
                    <td>{{ $request->client_name }}</td>
                    <td>{{ $request->phone }}<br>{{ $request->address }}</td>
                    <td>{{ $request->problem_text }}</td>
                    <td><span class="status">{{ $request->status }}</span></td>
                    <td>{{ $request->assignee?->name ?? 'не назначен' }}</td>
                    <td>
                        <div class="actions">
                            <form method="POST" action="{{ route('dispatcher.assign', $request) }}" class="grid">
                                @csrf
                                <select name="master_id" required>
                                    <option value="">Назначить мастера</option>
                                    @foreach($masters as $master)
                                        <option value="{{ $master->id }}" @selected($request->assigned_to === $master->id)>{{ $master->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit">Назначить</button>
                            </form>

                            <form method="POST" action="{{ route('dispatcher.cancel', $request) }}">
                                @csrf
                                <button class="danger" type="submit">Отменить</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Заявок не найдено.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
