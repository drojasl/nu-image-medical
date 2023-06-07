@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Insert the 4 digits code') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('tfa.validate') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="tfa_code" class="col-md-4 col-form-label text-md-end">{{ __('Two Factor Authentication Code') }}</label>

                            <div class="col-md-6">
                                <input id="tfa_code" maxlength="4" type="text" class="form-control @error('tfa_code') is-invalid @enderror" name="tfa_code" value="{{ old('tfa_code') }}" required autocomplete="email" autofocus>

                                @error('tfa_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh code been sent to your registered phone number.') }}
                        </div>
                    @endif

                    {{ __('If you did not receive the code') }},
                    <form class="d-inline" method="POST" action="{{ route('tfa.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                    </form>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
