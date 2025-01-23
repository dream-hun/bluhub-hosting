<x-app-layout>
    <div class="container" style="margin-top: 100px">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Domain Search Results') }}</div>

                    <div class="card-body">
                        @if ($error)
                            <div class="alert alert-danger" role="alert">
                                {{ $error }}
                            </div>
                        @else
                            <h4>Domain: {{ $domain }}</h4>

                            @if ($available)
                                <div class="alert alert-success" role="alert">
                                    <strong>{{ __('Congratulations!') }}</strong> {{ __('This domain is available!') }}
                                    @if(isset($domainPricing['.'.explode('.', $domain, 2)[1]]))
                                        <br>
                                        <span class="mt-2 d-block">
                                            Price: ${{ number_format($domainPricing['.'.explode('.', $domain, 2)[1]]->price, 2) }}/year
                                        </span>
                                    @endif
                                </div>

                                <form method="GET" action="{{ route('domains.register') }}">
                                    <input type="hidden" name="domain" value="{{ explode('.', $domain)[0] }}">
                                    <input type="hidden" name="extension"
                                        value="{{ implode('.', array_slice(explode('.', $domain), 1)) }}">

                                    <button type="submit" class="btn btn-success">
                                        {{ __('Register Now') }}
                                    </button>
                                </form>
                            @else
                                <div class="alert alert-warning" role="alert">
                                    {{ __('Sorry, this domain is already taken.') }}
                                </div>

                                <a href="{{ route('domains.info') }}?domain={{ explode('.', $domain)[0] }}&extension={{ implode('.', array_slice(explode('.', $domain), 1)) }}"
                                    class="btn btn-info">
                                    {{ __('View Domain Info') }}
                                </a>

                                @if (count($alternativeDomains) > 0)
                                    <div class="mt-4">
                                        <h5>{{ __('Available Alternatives') }}</h5>
                                        <div class="list-group mt-2">
                                            @foreach ($alternativeDomains as $altDomain)
                                                <div class="list-group-item">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <strong>{{ $altDomain }}</strong>
                                                            @if(isset($domainPricing['.'.explode('.', $altDomain, 2)[1]]))
                                                                <small class="text-muted d-block">
                                                                    Price: ${{ number_format($domainPricing['.'.explode('.', $altDomain, 2)[1]]->price, 2) }}/year
                                                                </small>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <a href="{{ route('domains.info') }}?domain={{ explode('.', $altDomain)[0] }}&extension={{ implode('.', array_slice(explode('.', $altDomain), 1)) }}"
                                                                class="btn btn-sm btn-info me-2">
                                                                {{ __('Info') }}
                                                            </a>
                                                            <form method="GET" action="{{ route('domains.register') }}" class="d-inline">
                                                                <input type="hidden" name="domain" value="{{ explode('.', $altDomain)[0] }}">
                                                                <input type="hidden" name="extension"
                                                                    value="{{ implode('.', array_slice(explode('.', $altDomain), 1)) }}">
                                                                <button type="submit" class="btn btn-sm btn-success">
                                                                    {{ __('Register') }}
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endif

                            <div class="mt-3">
                                <a href="{{ route('domains.search') }}" class="btn btn-secondary">
                                    {{ __('Search Another Domain') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
