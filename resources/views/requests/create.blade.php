@extends('layout')

@section('content')
    <div class="card" style="max-width: 760px; margin: 0 auto;">
        <h2>Создание заявки</h2>
        <form method="POST" action="{{ route('requests.store') }}" class="grid">
            @csrf
            <label>
                Клиент
                <input type="text" name="client_name" value="{{ old('client_name') }}" required>
            </label>
            <label>
                Телефон
                <input type="text" name="phone" value="{{ old('phone') }}" required>
            </label>
            <label>
                Адрес
                <input type="text" name="address" value="{{ old('address') }}" required>
            </label>
            <label>
                Описание проблемы
                <textarea name="problem_text" required>{{ old('problem_text') }}</textarea>
            </label>
            <button type="submit">Отправить заявку</button>
        </form>
    </div>
@endsection
