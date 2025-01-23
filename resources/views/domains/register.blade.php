@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register Domain') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('domains.store') }}">
                        @csrf

                        <input type="hidden" name="domain" value="{{ $domain }}">
                        <input type="hidden" name="extension" value="{{ $extension }}">

                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">{{ __('Domain Name') }}</label>
                            <div class="col-md-6">
                                <p class="form-control-static">{{ $domain }}.{{ $extension }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="period" class="col-md-4 col-form-label text-md-end">{{ __('Registration Period') }}</label>
                            <div class="col-md-6">
                                <select id="period" name="period" class="form-select @error('period') is-invalid @enderror" required>
                                    @for ($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}">{{ $i }} {{ __('Year') }}{{ $i > 1 ? 's' : '' }}</option>
                                    @endfor
                                </select>

                                @error('period')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="registrant" class="col-md-4 col-form-label text-md-end">{{ __('Registrant Contact') }}</label>
                            <div class="col-md-6">
                                <input id="registrant" type="text" class="form-control @error('registrant') is-invalid @enderror" 
                                       name="registrant" value="{{ old('registrant') }}" required>

                                @error('registrant')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="admin" class="col-md-4 col-form-label text-md-end">{{ __('Admin Contact') }}</label>
                            <div class="col-md-6">
                                <input id="admin" type="text" class="form-control @error('admin') is-invalid @enderror" 
                                       name="admin" value="{{ old('admin') }}" required>

                                @error('admin')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="tech" class="col-md-4 col-form-label text-md-end">{{ __('Technical Contact') }}</label>
                            <div class="col-md-6">
                                <input id="tech" type="text" class="form-control @error('tech') is-invalid @enderror" 
                                       name="tech" value="{{ old('tech') }}" required>

                                @error('tech')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="billing" class="col-md-4 col-form-label text-md-end">{{ __('Billing Contact') }}</label>
                            <div class="col-md-6">
                                <input id="billing" type="text" class="form-control @error('billing') is-invalid @enderror" 
                                       name="billing" value="{{ old('billing') }}" required>

                                @error('billing')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="nameservers" class="col-md-4 col-form-label text-md-end">{{ __('Nameservers') }}</label>
                            <div class="col-md-6">
                                <div class="nameserver-inputs">
                                    <input type="text" class="form-control mb-2 @error('nameservers.0') is-invalid @enderror" 
                                           name="nameservers[]" placeholder="ns1.example.com" required>
                                    <input type="text" class="form-control mb-2 @error('nameservers.1') is-invalid @enderror" 
                                           name="nameservers[]" placeholder="ns2.example.com" required>
                                </div>
                                <button type="button" class="btn btn-sm btn-secondary add-nameserver">
                                    {{ __('Add Nameserver') }}
                                </button>

                                @error('nameservers.*')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register Domain') }}
                                </button>
                                <a href="{{ route('domains.index') }}" class="btn btn-secondary">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addNameserverBtn = document.querySelector('.add-nameserver');
        const nameserverInputs = document.querySelector('.nameserver-inputs');

        addNameserverBtn.addEventListener('click', function() {
            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control mb-2';
            input.name = 'nameservers[]';
            input.placeholder = 'ns3.example.com';
            nameserverInputs.appendChild(input);
        });
    });
</script>
@endpush
@endsection