@extends('layouts.dashboard')

@section('content')

<div class="col-sm-16 col-xs-12">
    <div class="card">
        <div class="card-header">
          Clients
        </div>
        <div class="card-body">            
            <!-- table -->
            <table class="table table-hover">
                <tr>
                    <td><strong>ID</strong></td>
                    <td><strong>Client</strong></td>
                    <td><strong>Order Id</strong></td>

                </tr>
                <?php foreach ($clients as $cli) { ?>
                <tr>
                    <td>{{ $cli->id }}</td>
                    <td><a href="#">({{ $cli->name }}) - {{ $cli->company }}</a></td>
                    <td><a href="#"></a></td>
                </tr>   
                <?php } ?>
            </table>            
            <!-- table -->
            
        </div>
    </div>
</div>
        
@endsection
