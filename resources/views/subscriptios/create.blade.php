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
                <form action="{{ route('subscriptions.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="Customer_name">Customer Name</label>
                                        <select name="customer_id" id="customer_name" class="form-control" required>
                                            <option selected disabled>-- Select customer --</option>
                                            @foreach ($customerAvailable as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="car_name">Car Name</label>
                                        <select name="car_id" id="car_name" class="form-control" required>

                                            @foreach ($cars as $car)
                                                <option value="{{ $car->id }}">{{ $car->number }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="slot">Slot Location</label>
                                        <select name="slot_id" id="slot" class="form-control" required>
                                            @foreach ($availableSlots as $slot)
                                                <option value="{{ $slot->id }}">{{ $slot->location }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="Start Date">Start Date</label>
                                        <input type="datetime-local" name="start_date" id="Start Date" class="form-control" required
                                            placeholder="Start Date" min="{{ date('Y-m-d\TH:i') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="End Date">End Date</label>
                                        <input type="datetime-local" name="end_date" id="End Date" class="form-control" required
                                            placeholder="End Date" min="{{ date('Y-m-d\TH:i') }}">
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>
                    <div class="pb-5 pt-3">
                        <button class="btn btn-primary" type="submit" id="createSubscriptionBtn">Create</button>
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
            const startDateInput = document.getElementById('Start Date');
            const endDateInput = document.getElementById('End Date');

            // Start with empty car list
            carSelect.innerHTML = '<option selected disabled>-- Select Car --</option>';

            // Add validation for dates
            startDateInput.addEventListener('change', function() {
                // Set minimum end date to be after start date
                endDateInput.min = this.value;

                // If end date is before start date, clear it
                if (endDateInput.value && endDateInput.value < this.value) {
                    endDateInput.value = '';
                }
            });

            endDateInput.addEventListener('change', function() {
                // Ensure end date is after start date
                if (this.value < startDateInput.value) {
                    alert('End date must be after start date');
                    this.value = '';
                }
            });

            customerSelect.addEventListener('change', function() {
                const customerId = this.value;

                // Show loading message
                carSelect.innerHTML = '<option selected disabled>Loading cars...</option>';

                fetch(`/cars/by-customer/${customerId}`)
                    .then(response => response.json())
                    .then(data => {
                        carSelect.innerHTML = '<option selected disabled>-- Select Car --</option>';
                        data.forEach(car => {
                            const option = document.createElement('option');
                            option.value = car.id;
                            option.textContent = car.number;
                            carSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error loading cars:', error);
                        carSelect.innerHTML = '<option selected disabled>Failed to load cars</option>';
                    });
            });

            // --- Button disabling script ---
            const form = document.querySelector('form');
            const createButton = document.getElementById('createSubscriptionBtn');
            const requiredFields = form.querySelectorAll('[required]');

            function checkFormValidity() {
                let allFieldsFilled = true;
                requiredFields.forEach(field => {
                    if (field.tagName === 'SELECT') {
                        const selectedOption = field.options[field.selectedIndex];
                        if (!field.value || (selectedOption && selectedOption.disabled)) {
                            allFieldsFilled = false;
                        }
                    } else if (field.type === 'datetime-local') {
                        if (!field.value) {
                            allFieldsFilled = false;
                        }
                    } else if (!field.value) {
                        allFieldsFilled = false;
                    }
                });

                // Additional check for dates
                if (startDateInput.value && endDateInput.value) {
                    if (endDateInput.value <= startDateInput.value) {
                        allFieldsFilled = false;
                    }
                }

                createButton.disabled = !allFieldsFilled;
            }

            // Add event listeners to required fields
            requiredFields.forEach(field => {
                field.addEventListener('input', checkFormValidity);
                field.addEventListener('change', checkFormValidity);
            });

            // Initial check on page load
            checkFormValidity();
        });
    </script>
@endpush
