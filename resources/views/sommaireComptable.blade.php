@extends ('modeles/visiteur')
    @section('menu')
        <div id="menuGauche">
          <ul id="menuList">
            <li>
              <strong>Bonjour {{ $comptable['nom'] . ' ' . $comptable['prenom'] }}</strong>
            </li>
            <br>
            <li class="smenu">
              <a href="{{ route('chemin_selectionIdMois')}}" title="selectionnerIdMois"><i> - Valider une fiche de frais</i></a> 
            </li>
            <br>
            <li class="smenu">
              <a href="{{ route('chemin_deconnexion') }}" title="Se déconnecter"><i> - Déconnexion</i></a>
            </li>
          </ul>
        </div>
@endsection

<style>

</style>