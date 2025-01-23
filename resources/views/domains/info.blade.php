@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Domain Information') }}</div>

                <div class="card-body">
                    @if ($error)
                        <div class="alert alert-danger" role="alert">
                            {{ $error }}
                        </div>
                    @else
                        <div class="mb-4">
                            <h4>{{ $domain }}</h4>
                            <div class="badge bg-info">{{ implode(', ', $status) }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">{{ __('Registrant') }}</div>
                            <div class="col-md-8">{{ $registrant }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">{{ __('Contacts') }}</div>
                            <div class="col-md-8">
                                <dl class="row">
                                    @foreach ($contacts as $type => $contact)
                                        <dt class="col-sm-3">{{ ucfirst($type) }}</dt>
                                        <dd class="col-sm-9">{{ $contact }}</dd>
                                    @endforeach
                                </dl>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">{{ __('Nameservers') }}</div>
                            <div class="col-md-8">
                                <ul class="list-unstyled">
                                    @foreach ($nameservers as $ns)
                                        <li>{{ $ns }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">{{ __('Created Date') }}</div>
                            <div class="col-md-8">{{ $created }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">{{ __('Expiry Date') }}</div>
                            <div class="col-md-8">{{ $expiry }}</div>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('domains.index') }}" class="btn btn-secondary">
                                {{ __('Back to Domain Search') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
