@extends('League.league-header')

@section('title') {{ $league->name }} - Trading @endsection
<div class="container">
    <div class="row">
        <h1 class="text-center">
            Trade
        </h1>
    </div>

@unless ($currentUser->member)
<h2 class="text-center"><span class="fa fa-lock"></span> Locked for <span class="gold">Premium</span> Members Only</h2>
<div class="text-center">
    <a href="/premium/">
        <div class="btn btn-warning">
            Sign Up For Premium
        </div>
    </a>
</div>
@endunless

@if ($currentUser->member)

    <div class="row text-center">
        <h3>
        Which pokemon would you like to put on the trading block?
        </h3>

        <div class="col-xs-12 col-md-4 col-md-offset-4">
            <form action="/league/{{$league->id}}/trade/" method="POST">
                <select class="form-control" name="pokemon">
                    @foreach ($usersPokemon as $team)
                    @declare $pokemon = $pokemonModel->find($team->pokemon)

                        <option value="{{ $pokemon->id }}">{{$pokemon->pokemonName}}</option>

                    @endforeach
                </select>
                <br>
                <button class="btn btn-success">Offer</button>
            </form>
        </div>
    </div>
    <hr>

    
        @foreach ($tradingPokemon as $pokemon) 
        <div class="row">
        <h2 class="text-center">
        {{ $teamModel->find($pokemon->tradingteam)->name }} would like to trade their

        {{ $pokemonModel->find($pokemon->pokemon)->pokemonName }}
        </h2>

        <h3 class="text-center">Offers</h3>
        <hr>

        @declare $pokemonOffers = $tradingOffers->filter("trade = '$pokemon->id'")

        @unless ($pokemonOffers)
        <h4 class='text-center'>No Offers</h4>
        @endunless

        @foreach ($pokemonOffers as $offer)
        <div class="text-center">
        <h5>{{ $pokemonModel->find($offer->offer)->pokemonName }}</h5>

        @if ($usersTeam->id == $pokemon->tradingteam)
        <form action="/league/{{ $league->id }}/tradeoffer/" method="POST">
            <input type="hidden" name="oldteam" value="{{ $offer->team }}">
            <input type="hidden" name="trade" value="{{ $offer->trade }}">
            <input type="hidden" name="tradingoffer" value="{{$offer->id}}">
            <input type="hidden" name="pokemontrading" value="{{$tradingOffers->find($offer->id)->trade()->pokemon}}">
            <input type="hidden" name="pokemon" value="{{ $pokemonModel->find($offer->offer)->id }}">
        
            <input class="btn btn-success" type="submit" name="choice" value="Accept">
            <input class="btn btn-danger" type="submit" name="choice" value="Decline">
        </form>
        @endif
        </div><br>
        @endforeach

        <div class="col-md-4 col-md-offset-4 text-center">
            <label>Offer a Pokemon</label>
            <form action="/league/{{$league->id}}/offer/" method="POST">
            <input type="hidden" name="trade" value="{{$pokemon->id}}">
                <select class="form-control" name="pokemon">
                    @foreach ($usersPokemon as $team)
                    @declare $pokemon = $pokemonModel->find($team->pokemon)
                        <option value="{{ $pokemon->id }}">{{$pokemon->pokemonName}}</option>
                    @endforeach
                </select>
                <br>
                <button class="btn btn-success">Offer</button>
            </form>
        </div>
        
        </div>
        <hr>
        @endforeach
    

@endif
</div>