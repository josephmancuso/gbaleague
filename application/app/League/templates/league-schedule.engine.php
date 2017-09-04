@section('title') {{ $league->name }} - Schedule @endsection

@extends('League.league-header')

<div class="container">
    <div class="row">
        <h1 class="text-center">
            Schedule
        </h1>
    </div>

    <hr>

    @if ($isHost)
    <!-- Schedule a Match -->
    <div class="row text-center">
        <div class="col-xs-12 col-md-4 col-md-offset-4">
            <form action="league/{{ $league->name }}/schedule/" method="POST">
                <label>Team 1</label>
                <select class="form-control" name="team1" required>
                    <option selected disabled>Choose A Team</option>
                    @foreach ($teams as $team)
                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                    @endforeach
                </select>

                <label>Team 2</label>
                <select class="form-control" name="team2" required>
                    <option selected disabled>Choose A Team</option>
                    @foreach ($teams as $team)
                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                    @endforeach
                </select>

                <label>Date</label>
                <div class="input-group date">
                    <input type="text" class="form-control datepicker" name="date" required>
                    <div class="input-group-addon">
                        <span class="glyphicon glyphicon-th"></span>
                    </div>
                </div>

                <br><br>

                <div class="row">
                    <button class="btn btn-success">Add Schedule</button>
                </div>
            </form>
        </div>
    </div>
    @endif


    <h1 class="text-center">Scheduled Matches</h1>
    <div class="col-md-8 col-md-offset-2">
        <div class="table-responsive fixtures-table">
            <table class="table">
                <tbody><tr>
                    <th>match</th>
                    <th>date</th>
                    <th>winner</th>

                    @if ($isHost)
                        <th>delete</th>
                    @endif
                </tr>

                @foreach ($schedules as $schedule)
                <tr>
                    <td> {{$teamsModel->find($schedule->team1)->name}}   VS   {{$teamsModel->find($schedule->team2)->name}}</td>
                    <td>
                        @date $schedule->date
                    </td>
                    <td>
                    @if ($schedule->winner)
                        {{$teamsModel->find($schedule->team1)->name}}
                    @else 
                        Not Completed
                    @endif
                    </td>
                    @if ($isHost)
                    <td>

                        <form action="/league/{{ $league->slug }}/schedule/delete/{{ $schedule->id }}" method="POST">
                            <button class="btn btn-danger"><i class="fa fa-times" aria-hidden="true"></i></button>
                        </form>
                    
                    </td>  
                    @endif 
                </tr>
                @endforeach
                
            </tbody></table>
        </div>
    </div>

</div>