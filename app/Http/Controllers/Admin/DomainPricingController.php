<?php

namespace App\Http\Controllers\Admin;

use App\Actions\DomainPricing\DeleteDomainPricingAction;
use App\Actions\DomainPricing\StoreDomainPricingAction;
use App\Actions\DomainPricing\UpdateDomainPricingAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDomainPricingRequest;
use App\Http\Requests\Admin\UpdateDomainPricingRequest;
use App\Models\DomainPricing;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class DomainPricingController extends Controller
{
    public function __construct(
        private readonly StoreDomainPricingAction $storeDomainPricingAction,
        private readonly UpdateDomainPricingAction $updateDomainPricingAction,
        private readonly DeleteDomainPricingAction $deleteDomainPricingAction,
    ) {}

    public function index()
    {
        abort_if(Gate::denies('domain_pricing_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $pricing = DomainPricing::all();

        return view('admin.pricing.index', ['pricing' => $pricing]);
    }

    public function create()
    {
        return view('admin.pricing.create');
    }

    public function store(StoreDomainPricingRequest $request)
    {
        $this->storeDomainPricingAction->execute($request->validated());

        return redirect()->route('admin.pricing.index')
            ->with('success', 'Domain pricing created successfully.');
    }

    public function edit(DomainPricing $pricing)
    {
        return view('admin.pricing.edit', ['pricing' => $pricing]);
    }

    public function update(UpdateDomainPricingRequest $request, DomainPricing $pricing)
    {
        $this->updateDomainPricingAction->execute($pricing, $request->validated());

        return redirect()->route('admin.pricing.index')
            ->with('success', 'Domain pricing updated successfully.');
    }

    public function destroy(DomainPricing $pricing)
    {
        $this->deleteDomainPricingAction->execute($pricing);

        return redirect()->route('admin.pricing.index')
            ->with('success', 'Domain pricing deleted successfully.');
    }
}
