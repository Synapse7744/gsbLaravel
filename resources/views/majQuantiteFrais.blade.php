@extends ('selectionIdMois')
        @section('contenu2')
      
        <h3>Fiche de frais de {{ $nom['nom'] }} {{ $prenom['prenom'] }}, numero : {{$id}}  pour le mois {{ $numMois}}/{{$numAnnee}}</h3>
        <form action = "{{ route('chemin_recap')}}"  method = "post">
        <input name="_token" type="hidden" value="{{ csrf_token() }}"/>

        <input name="id" type="hidden" value="{{ $id }}"/>
        <input name="mois" type="hidden" value="{{ $mois }}"/>
        

        <div class="corpsForm">
            <legend>Eléments forfaitisés</legend>
            <p>
                <label name = "libelle">
                    Forfait Etape
                </label>
                <input type="text" name="saisieETP" value = "{{ $ETP }}">
            </p>
            <p>
                <label name = "libelle">
                    Frais Kilométrique
                </label>
                <input type="text" name="saisieKM" value = "{{ $KM }}">
            </p>
            <p>
                <label name = "libelle">
                    Nuitée Hôtel
                </label>
                <input type="text" name="saisieNUI" value = "{{ $NUI }}">
            </p>
            <p>
                <label name = "libelle">
                    Repas Restaurant 	
                </label>
                <input type="text" name="saisieREP" value = "{{ $REP }}">
            </p>
            <div> Total : {{$total}} €</div>
        </div>

        <div class="piedForm">
            <p>
                <input id="ok" type="submit" value="Valider" size="20" />
            </p> 
        </div>
</form>


@endsection

