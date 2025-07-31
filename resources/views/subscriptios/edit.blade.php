@extends('layouts.main')
@section('sec1')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Create Subscription </h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('subscriptions.index') }}" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                <form action="{{ route('subscriptions.update', $subscription->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="Customer_name">Customer Name</label>
                                        <select name="customer_id" id="customer_name" class="form-control" required>
                                            @foreach ($customerAvailable as $customer)
                                                <option value="{{ $customer->id }}" {{ $customer->id == $subscription->customer_id ? 'selected' : '' }}>
                                                    {{ $customer->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="car_name">Car Name</label>
                                        <select name="car_id" id="car_name" class="form-control">
                                            @foreach ($cars as $car)
                                                <option value="{{ $car->id }}" {{ $car->id == $subscription->car_id ? 'selected' : '' }}>
                                                    {{ $car->number }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="slot">Slot Location</label>
                                        <select name="slot_id" id="slot" class="form-control">
                                            <option value="{{ $subscription->slot_id }}">
                                                {{ $subscription->slot->location }}</option>
                                            @foreach ($availableSlots as $slot)
                                                <option value="{{ $slot->id }}">{{ $slot->location }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="Start Date">Start Date</label>
                                        <input type="datetime-local" value="{{ $subscription->start_date }}" name="start_date"
                                            id="Start Date" class="form-control" placeholder="Start Date">
                                    </div>
                                    <div class="mb-3">
                                        <label for="End Date">End Date</label>
                                        <input type="datetime-local" value="{{ $subscription->end_date }}" name="end_date"
                                            id="End Date" class="form-control" placeholder="Start Date">
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>
                    <div class="pb-5 pt-3">
                        <button class="btn btn-primary" type="submit">Create</button>
                        <a href="{{ route('subscriptions.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                    </div>
                </form>



            </div>
            <!-- /.card -->
        </section>
        <!-- /.content -->
    </div>
@endsection



@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const customerSelect = document.getElementById('customer_name');
            const carSelect = document.getElementById('car_name');

            // Function to load cars based on customer ID
            function loadCars(customerId, selectedCarId, subscriptionId) {
                if (!customerId) return;

                carSelect.innerHTML = '<option selected disabled>Loading cars...</option>';

                fetch(`/cars/by-customer/${customerId}`)
                    .then(response => response.json())
                    .then(data => {
                        carSelect.innerHTML = '<option selected disabled>-- Select Car --</option>';
                        data.forEach(car => {
                            const option = document.createElement('option');
                            option.value = car.id;
                            option.textContent = car.number;
                            if (selectedCarId && car.id == selectedCarId) {
                                option.selected = true;
                            }
                            carSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error loading cars:', error);
                        carSelect.innerHTML = '<option selected disabled>Failed to load cars</option>';
                    });
            }

            // Trigger car loading on page load if a customer is already selected
            const initialCustomerId = customerSelect.value;
            const initialCarId = "{{ $subscription->car_id }}";
            const currentSubscriptionId = "{{ $subscription->id }}"; // Get current subscription ID
            if (initialCustomerId) {
                loadCars(initialCustomerId, initialCarId, currentSubscriptionId);
            }

            // Event listener for customer change
            customerSelect.addEventListener('change', function() {
                loadCars(this.value, null, currentSubscriptionId); // Pass subscription ID on change
            });
        });
    </script>
@endpush
