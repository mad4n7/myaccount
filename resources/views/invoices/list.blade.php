@extends('layouts.dashboard')

@section('content')

<div class="col-sm-16 col-xs-12">
    <div class="card">
        <div class="card-header">
          My Invoices
        </div>
        <div class="card-body">
          
            <!-- table -->
            <table class="table table-hover">
                <tr>                    
                    <td><strong>Order Id</strong></td>
                    <td><strong>Amount</strong></td>
                    <td><strong>Created at</strong></td>                    
                    <td><strong>Status</strong></td>
                    <td><strong>Actions</strong></td>
                </tr>
                <?php foreach ($invoices as $invoice) { ?>
                <tr>
                    <td><a href="{{ url('orders'.'/'.$invoice->order_id)}}">{{ $invoice->order_id }}</a></td>
                    <td><?php echo App\Http\Controllers\HelperController::funcConvertDecimalToCurrency($invoice->amount)  ?></td>
                    <td><?php echo App\Http\Controllers\HelperController::funcDateTimeMysqlToUSA($invoice->created_at);  ?></td>
                    <td><?php echo App\Http\Controllers\HelperController::returnPymtStatusByChar($invoice->inv_status); ?></td>
                    <td><a href="{{ url('invoices'.'/'.$invoice->invoice_id) }}" title="View"><i class="fa fa-search fa-3x" aria-hidden="true"></i></a></td>
                </tr>   
                <?php } ?>
            </table>            
            <!-- table -->
            
        </div>
    </div>
</div>




        
@endsection
