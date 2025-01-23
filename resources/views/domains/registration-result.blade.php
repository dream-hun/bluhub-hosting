@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Domain Registration Result') }}</div>

                <div class="card-body">
                    @if ($error)
                        <div class="alert alert-danger" role="alert">
                            {{ $error }}
                        </div>
                    @else
                        @if ($success)
                            <div class="alert alert-success" role="alert">
                                <h4 class="alert-heading">{{ __('Success!') }}</h4>
                                <p>{{ $message }}</p>
                                <hr>
                                <p class="mb-0">{{ __('Domain') }}: {{ $domain }}</p>
                            </div>

                            <a href="{{ route('domains.info') }}?domain={{ explode('.', $domain)[0] }}&extension={{ implode('.', array_slice(explode('.', $domain), 1)) }}" 
                               class="btn btn-info">
                                {{ __('View Domain Info') }}
                            </a>
                        @else
                            <div class="alert alert-danger" role="alert">
                                <h4 class="alert-heading">{{ __('Registration Failed') }}</h4>
                                <p>{{ $message }}</p>
                                <hr>
                                <p class="mb-0">{{ __('Domain') }}: {{ $domain }}</p>
                            </div>
                        @endif
                    @endif

                    <div class="mt-3">
                        <a href="{{ route('domains.index') }}" class="btn btn-secondary">
                            {{ __('Back to Domain Search') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
