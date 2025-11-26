@extends('core::layouts.master')
@section('title')
    Add Client to Group
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add Client to Group</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('dashboard')}}">{{ trans_choice('dashboard::general.dashboard',1) }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{url('client')}}">{{ trans_choice('client::general.client',2) }}</a>
                        </li>
                        <li class="breadcrumb-item active">Add to Group</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <form method="post" action="{{ url('client/group/member/store') }}">
                    {{csrf_field()}}
                    
                    @if($client)
                        <input type="hidden" name="client_id" value="{{$client->id}}">
                    @endif

                    <div class="card shadow-sm">
                        <div class="card-header bg-primary">
                            <h3 class="card-title text-white">
                                <i class="fas fa-users"></i> Group Membership Details
                            </h3>
                        </div>
                        <div class="card-body p-4">
                            @if($client)
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    Adding <strong>{{$client->first_name}} {{$client->last_name}}</strong> to a group
                                </div>
                            @else
                                <div class="form-group">
                                    <label class="font-weight-bold">Select Client</label>
                                    <select name="client_id" class="form-control" required>
                                        <option value="">-- Select Client --</option>
                                        @foreach(\Modules\Client\Entities\Client::where('status', 'active')->get() as $c)
                                            <option value="{{$c->id}}">{{$c->first_name}} {{$c->last_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="form-group">
                                <label class="font-weight-bold">
                                    <i class="fas fa-users"></i> Select Group
                                </label>
                                <select name="group_id" class="form-control form-control-lg" required>
                                    <option value="">-- Select Group --</option>
                                    @foreach($groups as $group)
                                        <option value="{{$group->id}}">
                                            {{$group->name}} 
                                            ({{$group->members()->where('status', 'active')->count()}} members)
                                        </option>
                                    @endforeach
                                </select>
                                @error('group_id')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">
                                    <i class="fas fa-user-tag"></i> Member Role
                                </label>
                                <select name="role" class="form-control form-control-lg" required>
                                    <option value="member">Member</option>
                                    <option value="leader">Leader</option>
                                    <option value="treasurer">Treasurer</option>
                                    <option value="secretary">Secretary</option>
                                </select>
                                @error('role')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between">
                                <button type="button" onclick="window.history.back()" class="btn btn-lg btn-outline-secondary">
                                    <i class="fas fa-arrow-left"></i> Cancel
                                </button>
                                <button type="submit" class="btn btn-lg btn-success px-5">
                                    <i class="fas fa-user-plus"></i> Add to Group
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@section('styles')
    <style>
        .card.shadow-sm {
            box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
            border: none;
            margin-bottom: 1.5rem;
        }
    </style>
@endsection
