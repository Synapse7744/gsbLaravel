@extends('sommaire')
@section('contenu1')

<form action = "{{ route('chemin_verification') }}" method = "post">
<input name="_token" type="hidden" value="{{ csrf_token() }}"/>


Saisir votre date d'embauche : <input type="text" name="date" value ="aaaa/mm/jj">
<br>
<br>
Saisir votre nouveau mot de passe : <input type="password" name="mdp1"/>
<br>
Retappez votre nouveau mot de passe : <input type="password" name="mdp2"/>

<br>
<br>




<input type="submit" name = "Valider" value="Valider"/>
</form>

@includeWhen($message != "", 'message', ['message' => $message])
@includeWhen($erreurs != null, 'msgerreurs', ['erreurs' => $erreurs]) 


@endsection