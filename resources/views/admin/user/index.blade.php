@extends('layouts.admin')

@section('title', 'Data User')
@section('page-title', 'Data User')

@section('content')
    <div class="page-header">
        <h2 class="page-title">Daftar User</h2>
        <a href="{{ route('admin.user.create') }}" class="btn-add">
            ➕ Tambah User
        </a>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%;">ID</th>
                    <th>Username</th>
                    <th style="width: 15%;">Role</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $user->iduser }}</td>
                    <td>{{ $user->username }}</td>
                    <td>
                        <span class="badge badge-{{ $user->iduser == 1 ? 'danger' : 'info' }}">
                            {{ $user->role }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center; color: #94a3b8;">Belum ada data user</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem;">
        {{ $users->links() }}
    </div>
@endsection

@push('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        border-bottom: 1px solid #e5e5e5;
        padding-bottom: 1rem;
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1a1a1a;
    }

    .btn-add {
        display: inline-block;
        padding: 0.625rem 1.25rem;
        background: #1a1a1a;
        color: #fff;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: background 0.2s;
    }

    .btn-add:hover {
        background: #333;
    }

    .table-responsive {
        overflow-x: auto;
        background: #fff;
        border: 1px solid #e5e5e5;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9375rem;
    }

    .data-table th,
    .data-table td {
        padding: 0.875rem 1rem;
        text-align: left;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
    }

    .data-table thead th {
        background-color: #f9fafb;
        font-weight: 600;
        color: #374151;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .data-table tbody tr:hover {
        background-color: #f9fafb;
    }

    .badge {
        display: inline-block;
        padding: 0.25em 0.6em;
        font-size: 0.8125rem;
        font-weight: 500;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
    }

    .badge-info { color: #1e40af; background-color: #dbeafe; }
    .badge-danger { color: #991b1b; background-color: #fee2e2; }

    .btn-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        border: none;
        background: transparent;
        text-decoration: none;
        font-size: 1.125rem;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .btn-icon:hover {
        background-color: #f0f0f0;
    }

    .btn-view { color: #2563eb; }
    .btn-edit { color: #1d4ed8; }
    .btn-delete { color: #b91c1c; }
</style>
@endpush