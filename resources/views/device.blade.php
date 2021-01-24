@extends('base')

@section('content')
<div class="container py-4">
    <a href="/">Back</a>

    <div class="row">
        <div class="col">
            <strong>Device ID: </strong>
            <p>{{$device->id}}</p>
        </div>
        <div class="col">
            <strong> Status: </strong>
            <p class="font-weight-bold h3">
                {{$device->isBlocked() ? 'Blocked' : ($device->isAboveLimit() ? 'Above Limit' : ($device->isHot() ? 'Hot' : 'OK'))}}
            </p>
        </div>
        <div class="col">
            <strong> Block expires: </strong>
            <p class="font-weight-bold h3">
                {{($device->isBlocked() ? $device->restrExpiry->diff(now())->format('%H:%I:%S') : 'Not blocked')}}
            </p>
        </div>
        <div class="col">
            <strong>Tickets last({{$config->timeDuration}})seconds: </strong>
            <p class="font-weight-bold h3">{{$device->ticketsFromTimePeriod()->count()}}</p>
        </div>
        <div class="col">
            <strong>Amount last({{$config->timeDuration}})seconds: </strong>
            <p class="font-weight-bold h3">{{$device->stakeSumFromPeriod()}}</p>
        </div>
    </div>

    <div class="d-flex flex-column card mt-3 p-3">
        <h4>Add new ticket to this device</h4>
        <div>
            <form method="post" action="{{route('add.ticket', $device->id)}}">
                @csrf
                @method('POST')
                <div class="form-group">
                    <label>Stake value</label>
                    <input type="number" name="stake" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Add</button>
            </form>
        </div>
    </div>

    <div class="d-flex flex-column mt-3">
        <h4>Tickets from this device</h4>
        <table class="table">
            <thead>
                <th>ID</th>
                <th>Stake</th>
                <th>Created at</th>
            </thead>

            <tbody>
                @foreach ($tickets as $ticket)
                <tr>
                    <td>{{$ticket->id}}</td>
                    <td>{{$ticket->stake}}</td>
                    <td>{{$ticket->created_at->diffForHumans()}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{$tickets->links()}}
    </div>


</div>


@endsection