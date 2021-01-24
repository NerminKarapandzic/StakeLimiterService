@extends('base')

@section('content')

<div class="container">

    <div class="card my-2 p-3" style="border-radius:15px;">
        <form method="POST" action="{{route('config.update')}}">
            @csrf
            @method('POST')
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Stake Limit</label>
                        <input type="number" name="stakeLimit" class="form-control" value="{{$config->stakeLimit}}">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>Time Duration(seconds)</label>
                        <input type="number" name="timeDuration" class="form-control" value="{{$config->timeDuration}}">
                    </div>
                </div>
                <div class=" col">
                    <div class="form-group">
                        <label>Percentage Amount(%)</label>
                        <input type="number" name="hotAmountPctg" class="form-control"
                            value="{{$config->hotAmountPctg}}">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>Restriction time(seconds)</label>
                        <input type="number" name="restrExpiry" class="form-control" value="{{$config->restrExpiry}}">
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
            <th>Last({{$config->timeDuration}}) seconds</th>
            <th>Amount last({{$config->timeDuration}}) seconds</th>
            <th>Current status</th>
            <th>Block expires in</th>
            <th>Detail</th>
        </thead>

        <tbody>
            @foreach($devices as $device)
            <tr>
                <td>{{$device->id}}</td>
                <td>{{$device->tickets->count()}}</td>
                <td>{{$device->ticketsFromTimePeriod()->count()}}</td>
                <td>{{$device->stakeSumFromPeriod()}}</td>
                <td>{{$device->isBlocked() ? 'Blocked' : ($device->isAboveLimit() ? 'Above Limit' : ($device->isHot() ? 'Hot' : 'OK'))}}
                </td>
                <td>{{($device->isBlocked() ? ($device->restrExpiry === null ? 'Indefinite' : $device->restrExpiry->diff(now())->format('%H:%I:%S')) : 'Not blocked')}}
                </td>
                <td><a href="{{route('device.details', $device->id)}}">Details</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{$devices->links()}}

</div>
@endsection