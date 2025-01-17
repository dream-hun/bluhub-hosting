<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDomainRequest;
use App\Http\Requests\Admin\UpdateDomainRequest;
use App\Models\Domain;
use App\Models\DomainPricing;
use App\Models\User;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class DomainController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('domain_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domains = Domain::with(['domainPricing', 'user'])->get();

        return view('admin.domains.index', compact('domains'));
    }

    public function create()
    {
        abort_if(Gate::denies('domain_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domain_pricings = DomainPricing::pluck('tld', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.domains.create', compact('domain_pricings', 'users'));
    }

    public function store(StoreDomainRequest $request)
    {
        $domain = Domain::create($request->all());

        return redirect()->route('admin.domains.index');
    }

    public function edit(Domain $domain)
    {
        abort_if(Gate::denies('domain_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domain_pricings = DomainPricing::pluck('tld', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $domain->load('domain_pricing', 'user');

        return view('admin.domains.edit', compact('domain', 'domain_pricings', 'users'));
    }

    public function update(UpdateDomainRequest $request, Domain $domain)
    {
        $domain->update($request->all());

        return redirect()->route('admin.domains.index');
    }

    public function show(Domain $domain)
    {
        abort_if(Gate::denies('domain_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domain->load('domain_pricing', 'user');

        return view('admin.domains.show', compact('domain'));
    }

    public function destroy(Domain $domain)
    {
        abort_if(Gate::denies('domain_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domain->delete();

        return back();
    }
}
