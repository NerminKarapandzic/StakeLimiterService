@extends('base')

@section('content')

<div class="container">

    <div class="card my-2 p-3" style="border-radius:15px;">
        <form method="POST" action="{{route('config.update')}}">
            @csrf
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Stake Limit</label>
                        <input type="number" class="form-control" value="{{$config->stakeLimit}}">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>Time Duration(minutes)</label>
                        <input type="number" class="form-control" value="{{$config->timeDuration}}">
                    </div>
                </div>
                <div class=" col">
                    <div class="form-group">
                        <label>Percentage Amount(%)</label>
                        <input type="number" class="form-control" value="{{$config->hotAmountPctg}}">
                    </div>
                </div>
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>

    <table class="table">
        <thead>
            <th>Device</th>
            <th>Tickets amount</th>
            <th>Last({{$config->timeDuration}}) minutes</th>
            <th>Amount last({{$config->timeDuration}}) minutes</th>
            <th>Status</th>
        </thead>

        <tbody>
            @foreach($devices as $device)
            <tr>
                <td>{{$device->id}}</td>
                <td>{{$device->tickets->count()}}</td>
                <td>{{$device->ticketsFromTimePeriod()->count()}}</td>
                <td>{{$device->stakeSumFromPeriod()}}</td>
                <td>{{$device->isBlocked() || $device->isAboveLimit() ? 'Blocked' : ($device->isHot() ? 'Hot' : 'OK')}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{$devices->links()}}

</div>
@endsection