@extends ('selectionIdMois')
        @section('contenu2')
        <h3>Fiche de frais de <i>{{ $nom['nom'] }} {{ $prenom['prenom'] }}</i>, pour le mois <i>{{ $numMois}}/{{$numAnnee}}</i></h3>
<form action = "{{ route('chemin_enregistrerValidation')}}"  method = "post">
        <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
        <input name="id" type="hidden" value="{{ $id }}"/>
        <input name="mois" type="hidden" value="{{ $mois }}"/>
        <input name="total" type="hidden" value="{{ $total }}"/>
        

<div class="corpsForm">
    <legend>Eléments forfaitisés</legend>

    <p>
        <label name = "libelle">Forfait Etape</label>
        <div class="centrer"> : {{ $saisieETP }}</div>
    </p>

    <p>
        <label name = "libelle">Frais Kilométrique</label>
        <div class="centrer"> : {{ $saisieKM }}</div>
    </p>

    <p>
        <label name = "libelle">Nuitée Hôtel</label>
        <div class="centrer"> : {{ $saisieNUI }}</div>
    </p>

    <p>
        <label name = "libelle">Repas Restaurant</label>
        <div class="centrer"> : {{ $saisieREP }}</div>
    </p>

    <div> <b>TOTAL</b>  {{ $total }} €</div>
</div>
<div class="piedForm">
    <p>
        <input id="ok" type="submit" value="Valider" size="20"/>
    </p> 

</div>
</form>
<!-- <a href="{{url('test/' . $id . '/' . $mois)}}"><button>Annuler</button></a>     -->

@endsection

<style>
    .centrer{
        padding-top:4px;
    }
</style>