@extends ('sommaireComptable')
    @section('contenu1')
        <div id="contenu">
            <form action = "{{ route('chemin_majQuantiteFrais')}}"  method = "post">
                <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                    <div class="corpsForm">
                        <p>
                        <label for="id" >Numéro visiteur : </label>
                            <select name = "id">
                                @foreach ($lesVisiteurs as $unVisiteur)
                                    <option value="{{ $unVisiteur['id'] }}">{{ $unVisiteur['id'] }}</option>
                                @endforeach
                           </select>
                        </p>
                        <p>
                        <label for="mois" >Mois : </label>
                        
                            <select name = "mois">
                                @foreach ($lesMois as $mois)
                                    <option value="{{ $mois['mois'] }}">
                                        {{ $mois['numMois']}}/{{$mois['numAnnee'] }} 
                                    </option>
                                @endforeach
                            </select>

                        </p>
                    </div>
                    <div class="piedForm">
                        <p>
                            <input id="ok" type="submit" value="Valider" size="20" />
                        </p> 
                    </div>
            </form>

    @includeWhen($message != "", 'message', ['message' => $message])
    @includeWhen($erreurs != null, 'msgerreurs', ['erreurs' => $erreurs]) 
    
@endsection





 