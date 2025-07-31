@extends('layouts.main')
@section('sec1')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Update Cars</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('cars.index') }}" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                <form action="{{ route('cars.update', $car->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="Customer_name">Customer Name</label>
                                        <select name="customer_id" id="customer_name" class="form-control">
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="number">number</label>
                                        <input type="text" value="{{ $car->number }}" name="number" id="number"
                                            class="form-control" placeholder="Number of vehicles">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">

                                        <div class="form-group">
                                            <label for="vehicle_color">Vehicle Color</label>
                                            <select name="color"   id="vehicle_color"
                                                class="form-control">
                                                <option value="{{ $car->color }}">{{ $car->color }}</option>
                                                <option value="Black">Black</option>
                                                <option value="White">White</option>
                                                <option value="Red">Red</option>
                                                <option value="Blue">Blue</option>
                                                <option value="Silver">Silver</option>
                                                <option value="Gray">Gray</option>
                                                <option value="Green">Green</option>
                                                <option value="Yellow">Yellow</option>
                                                <option value="Brown">Brown</option>
                                                <option value="Gold">Gold</option>
                                                <option value="Orange">Orange</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>


                                    </div>
                                    <div class="mb-3">
                                        <label for="model">model</label>
                                        <input type="text" value="{{ $car->model }} name="model" id="model"
                                            class="form-control" placeholder="Model of vehicle">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="address">type</label>
                                        <input name="type" id="address" value="{{ $car->type }} "class="form-control"
                                            placeholder="Type of vehicle">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pb-5 pt-3">
                        <button class="btn btn-primary" type="submit">Update</button>
                        <a href="{{ route('cars.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                    </div>
                </form>



            </div>
            <!-- /.card -->
        </section>
        <!-- /.content -->
    </div>
@endsection
