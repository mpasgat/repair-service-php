@extends('layout')

@section('content')
    <div class="card">
        <h2>Панель мастера</h2>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Клиент</th>
                <th>Телефон</th>
                <th>Адрес</th>
                <th>Проблема</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @forelse($requests as $request)
                <tr>
                    <td>{{ $request->id }}</td>
                    <td>{{ $request->client_name }}</td>
                    <td>{{ $request->phone }}</td>
                    <td>{{ $request->address }}</td>
                    <td>{{ $request->problem_text }}</td>
                    <td><span class="status">{{ $request->status }}</span></td>
                    <td>
                        <div class="actions">
                            <form method="POST" action="{{ route('master.take', $request) }}">
                                @csrf
                                <button type="submit" @disabled($request->status !== 'assigned')>Взять в работу</button>
                            </form>
                            <form method="POST" action="{{ route('master.complete', $request) }}">
                                @csrf
                                <button class="secondary" type="submit" @disabled($request->status !== 'in_progress')>Завершить</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Нет заявок, назначенных на вас.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
