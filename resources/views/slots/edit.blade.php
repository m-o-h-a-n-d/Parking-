@extends('layouts.main')
@section('sec1')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Update Slot</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('slots.index') }}" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                <form action="{{ route('slots.update', $slot->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="number">Name Of Door</label>
                                        <input type="text" value="{{ $slot->number }}" name="number" id="number"
                                            class="form-control" placeholder="Number of Door ">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="location">Location</label>
                                        <input type="text" value="{{ $slot->location }}" name="location" id="location"
                                            class="form-control" placeholder="Location">
                                    </div>
                                </div>

                                <div class="col-md-12">

                                    <div class="mb-3">
                                        <label for="Price">Price</label>
                                        <input type="number" name="price" value="{{ $slot->price }}"
                                            id="driving_license" class="form-control" placeholder="Price">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="pb-5 pt-3">
                        <button class="btn btn-primary" type="submit">Update</button>
                        <a href="{{ route('slots.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                    </div>
                </form>



            </div>
            <!-- /.card -->
        </section>
        <!-- /.content -->
    </div>
@endsection
