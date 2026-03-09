@extends('layout')

@section('content')
    <div class="card" style="max-width: 520px; margin: 0 auto;">
        <h2>Вход в систему</h2>
        <p>Для упрощенной авторизации выберите пользователя из списка.</p>

        <form method="POST" action="{{ route('login.store') }}" class="grid">
            @csrf
            <label>
                Пользователь
                <select name="user_id" required>
                    <option value="">-- выбрать --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                    @endforeach
                </select>
            </label>
            <button type="submit">Войти</button>
        </form>
    </div>
@endsection
