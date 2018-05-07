@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Two factor settings</div>
                    <div class="card-body">
                        two factor settings
                        <div class="col-md-6 col-md-offset-2">
                            <form action="{{ url("/settings/twofactor") }}" method="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <select name="two_factor_type" id="two_factor_type" class="form-control">
                                @foreach (config('twofactor.types') as $key => $name)
                                    <option value="{{ $key }}" {{ old('two_factor_type') === $key || Auth::user()->hasTwoFactorType($key) ? 'selected="selected"' : ''}}>{{$name}}</option>
                                @endforeach
                            </select>
                            <select name="phone_number_dialling_code" id="phone_number_dialling_code" class="form-control">
                                @foreach ($diallingCodes as $code)
                                    <option value="{{ $code->id }}" {{ old('phone_number_dialling_code') === $code->id || Auth::user()->hasDiallingCode($code->id) ? 'selected="selected"' : ''}}>{{$code->name}} (+{{$code->dialling_code}})</option>
                                @endforeach
                            </select>
                            <input type="number" name="phone" class="form-control" placeholder="Enter ur phone XX XXX XX XX" {{ Auth::user()->hasPhoneNumber() === true ? 'value='.Auth::user()->phoneNumber->phone_number : ''}} required>
                            <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
