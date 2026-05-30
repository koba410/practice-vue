@extends('layouts.app')

@section('title', 'ユーザー一覧')

@section('content')
    <h1 class="text-2xl font-semibold mb-6">ユーザー一覧</h1>

    <p class="text-sm text-gray-600 mb-4">
        下のテーブルは Blade でサーバー描画（本業の PHP echo 相当）です。
        検索フィルタは Vue が担当します。
    </p>

    <table class="w-full border-collapse border border-gray-200 mb-8 text-sm">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-200 px-4 py-2 text-left">ID</th>
                <th class="border border-gray-200 px-4 py-2 text-left">名前</th>
                <th class="border border-gray-200 px-4 py-2 text-left">メール</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td class="border border-gray-200 px-4 py-2">{{ $user->id }}</td>
                    <td class="border border-gray-200 px-4 py-2">{{ $user->name }}</td>
                    <td class="border border-gray-200 px-4 py-2">{{ $user->email }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div id="user-filter-app" data-users='@json($users)'></div>
@endsection

@push('vite')
    @vite(['resources/js/pages/user-filter.js'])
@endpush
