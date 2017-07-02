@extends('layouts.dashboard')

@section('content')

<div class="col-sm-16 col-xs-12">
    <div class="card">
        <div class="card-header">
          My Invoices
        </div>
        <div class="card-body">
            <div class="row col-md-9">
                <div class="col-md-3">
                    <a class="btn btn-default btn-lg" href="{{ url('/admin/invoices/unpaid') }}">Unpaid</a>
                </div>
                <div class="col-md-3">
                    <a class="btn btn-default btn-lg" href="{{ url('/admin/invoices/paid') }}">Paid</a>
                </div>
            </div>
            <!-- table -->
            <table class="table table-hover">
                <tr>
                    <td><strong>ID</strong></td>
                    <td><strong>Client</strong></td>
                    <td><strong>Order Id</strong></td>
                    <td><strong>Amount</strong></td>
                    <td><strong>Paid on</strong></td>                    
                    <td><strong>Status</strong></td>
                    <td><strong>Actions</strong></td>
                </tr>
                <?php foreach ($invoices as $invoice) { ?>
                <tr>
                    <td>{{ $invoice->invoice_id }}</td>
                    <td><a href="{{ url('/profile?userid='.$invoice->user_id)}}">({{ $invoice->user_id }}) - {{ $invoice->user->name }}</a></td>
                    <td><a href="{{ url('orders'.'/'.$invoice->order_id)}}">{{ $invoice->order_id }}</a></td>
                    <td><?php echo App\Http\Controllers\HelperController::funcConvertDecimalToCurrency($invoice->amount)  ?></td>
                    <td><?php echo App\Http\Controllers\HelperController::funcDateTimeMysqlToUSA($invoice->paid_date);  ?></td>
                    <td><?php echo App\Http\Controllers\HelperController::returnPymtStatusByChar($invoice->inv_status); ?></td>
                    <td><a href="{{ url('admin/invoices/receipt/'.$invoice->invoice_id.'?userid='.$invoice->user_id) }}" target="_new" title="View"><i class="fa fa-search fa-3x" aria-hidden="true"></i></a></td>
                </tr>   
                <?php } ?>
            </table>            
            <!-- table -->
            
        </div>
    </div>
</div>
        
@endsection
