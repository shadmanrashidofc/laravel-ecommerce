@extends('admin.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Profile</h1>
        <a href="{{ route('admin.profile.edit') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Edit Profile
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-500">Name</label>
                <p class="text-lg text-gray-900">{{ $user->name }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Email</label>
                <p class="text-lg text-gray-900">{{ $user->email }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Role</label>
                <p class="text-lg text-gray-900 capitalize">{{ $user->role }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Member Since</label>
                <p class="text-lg text-gray-900">{{ $user->created_at->format('F j, Y') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
