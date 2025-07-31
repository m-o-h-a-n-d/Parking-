@extends('layouts.main')

@section('sec1')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Subscription</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('subscriptions.create') }}" class="btn btn-primary">New sub</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <div class="card-tools">
                            <div class="input-group input-group" style="width: 250px">
                                <form action="{{ route('subscriptios.search') }}" method="GET" class="d-inline">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Search by Customer Name" value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th width="60">ID</th>
                                    <th width="100">customer</th>
                                    <th width="100">car</th>
                                    <th width="70">slot</th>
                                    <th width="100">Start Date</th>
                                    <th width="100">End Date</th>
                                    <th width="100">Status</th>
                                    <th width="100">actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subscriptions as $sub)
                                    <tr data-subscription-id="{{ $sub->id }}" data-end-date="{{ $sub->end_date }}"
                                        data-customer-id="{{ $sub->customer->id }}" data-slot-id="{{ $sub->slot->id }}">
                                        <td>{{ $sub->id }}</td>
                                        <td class="customer-status">
                                            {{ $sub->customer->name }}
                                            <span class="status-indicator"></span>
                                        </td>
                                        <td>{{ $sub->car->number }}</td>
                                        <td class="slot-status">
                                            {{ $sub->slot->location }}
                                            <span class="status-indicator"></span>
                                        </td>
                                        <td>{{ $sub->start_date }}</td>
                                        <td>{{ $sub->end_date }}</td>
                                        <td class="subscription-status">
                                            <span class="badge badge-success">Active</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('subscriptions.edit', $sub->id) }}">
                                                <svg class="filament-link-icon w-4 h-4 mr-1"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor" aria-hidden="true">
                                                    <path
                                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                                    </path>
                                                </svg>
                                            </a>

                                            <form action="{{ route('subscriptions.destroy', $sub->id) }}"
                                                class="d-inline btn-delete" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-danger w-4 h-4 mr-1 border-0">
                                                    <svg wire:loading.remove.delay="" wire:target=""
                                                        class="filament-link-icon w-4 h-4 mr-1"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                        fill="currentColor" aria-hidden="true">
                                                        <path ath fill-rule="evenodd"
                                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        <ul class="pagination pagination m-0 float-right">
                            <li class="page-item"><a class="page-link" href="#">«</a></li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">»</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </section>
        <!-- /.content -->
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function checkExpiredSubscriptions() {
                const now = new Date();
                const rows = document.querySelectorAll('tr[data-end-date]');

                rows.forEach(row => {
                    const endDate = new Date(row.dataset.endDate);
                    const statusCell = row.querySelector('.subscription-status');
                    const customerCell = row.querySelector('.customer-status');
                    const slotCell = row.querySelector('.slot-status');

                    if (endDate <= now) {

                        statusCell.innerHTML = '<span class="badge badge-danger">Expired</span>';


                        const subscriptionId = row.dataset.subscriptionId;
                        const customerId = row.dataset.customerId;
                        const slotId = row.dataset.slotId;


                    } else {
                        // Calculate time remaining
                        const timeRemaining = endDate - now;
                        const hours = Math.floor(timeRemaining / (1000 * 60 * 60));
                        const minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));

                        // Update status with time remaining
                        statusCell.innerHTML =
                            `<span class="badge badge-success">Active (${hours}h ${minutes}m)</span>`;


                    }
                });
            }

            // Check immediately when page loads
            checkExpiredSubscriptions();

            // Then check every 30 seconds
            setInterval(checkExpiredSubscriptions, 30000);
        });
    </script>
@endpush
