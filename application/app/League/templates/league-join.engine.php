@extends('League.league-header')

@section('title') {{ $league->name }} - Join @endsection

<div class="container">
    <div class="row">
        <h1 class="text-center">
            Join
        </h1>
    </div>

    <div class="row">
        <form action="/league/{{ $league->slug }}/join/" method="POST">
            <div class="form-group col-md-4 col-md-offset-4">
                <label>Choose Your Team:</label>
                
                <select class="form-control" name="team">

                @unless (count($teams))
                    <option selected disabled>No Teams Available</option>
                @endunless

                @foreach ($teams as $team)
                    <option value="{{ $team->id }}">{{ $team->name }}</option>
                @endforeach

                </select>
                @unless (count($teams))
                    <p>
                        Either you don't have any teams or all your teams are currently in leagues already. To join this league you
                        must create another team or remove your team from another league.
                    </p>
                @endunless
                <br>
                <div class="pull-right">
                    @if (count($userRequests))

                        <p>Request to join is pending acceptance by the league owner</p>

                    @else
                        @if (!$isLeagueMember)
                            @if ($league->tournament)
                                <a href="/tournaments/team/{{$league->slug}}/">
                                    <div class="btn btn-danger">Register</div>
                                </a>
                            @else
                                <button class="btn btn-success">Join</button>
                            @endif
                        @else
                        You are already in this league
                        @endif
                    @endif
                    
                    <a href="/team/create/?next=/league/{{ $league->slug }}/join/">
                        <div class="btn btn-primary">Create New Team</div>
                    </a>
                </div>
                
            </div>
        </form>
    </div>
</div>