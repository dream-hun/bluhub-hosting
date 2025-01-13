<x-admin-layout>

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Domain Pricing</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Domain Pricing</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                    <div class="col-md-12">
                        @can('domain_pricing_create')
                            <div style="margin-bottom: 10px;" class="row " >
                                <div class="col-lg-12">
                                    <a class="btn btn-success fa-pull-right" href="{{ route('admin.pricing.create') }}">
                                        <span class="fa fa-plus-circle"></span>
                                        {{ trans('global.add') }} {{ trans('cruds.domainPricing.title_singular') }}
                                    </a>
                                </div>
                            </div>
                        @endcan


                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Domain Pricing</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Tld</th>
                                        <th>Price</th>
                                        <th>Renewal Price</th>
                                        <th>Transfer Price</th>
                                        <th>Grace Period</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($pricing as $price)
                                        <tr>
                                            <td>{{$price->id}}.</td>
                                            <td>{{$price->tld}}</td>
                                            <td>
                                                {{ $price->formattedRegistrationPrice() }}
                                            </td>
                                            <td>{{ $price->formattedRenewalPrice() }}</td>
                                            <td>{{ $price->formattedTransferPrice() }}</td>
                                            <td>{{ $price->grace_period }} days</td>
                                            <td>
                                                <div class="btn-group">

                                                        <a href="{{ route('admin.pricing.edit', $price) }}" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-edit"></i>
                                                            Edit
                                                        </a>
                                                        <form action="{{ route('admin.pricing.destroy', $price) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this pricing?')">
                                                              Delete  <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>


                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach


                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
        </section>

</x-admin-layout>
