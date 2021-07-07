@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    About
                </div>
                <div class="card-body">

                  <div class="row">
                    <div class="col-4" style="min-width: 460px;">
                        <img src="/images/mercator.jpg">
                    </div>
                    <div class="col-6">

<p>
Mercator est une application Web permettant de gérer la cartographie d’un système d’information comme décrit 
dans le <a href="https://www.ssi.gouv.fr/guide/cartographie-du-systeme-dinformation/">Guide pour la cartographie du Système d’information</a> 
de l’<a href="https://www.ssi.gouv.fr/">ANSSI</a>. Les sources de l'application sont disponibles sous <a href="https://www.github.com/dbarzin/mercator">Github</a>.
</p>
<p>
Gérard Mercator, né le 5 mars 1512 à Rupelmonde et mort le 2 décembre 1594 à Duisbourg, 
est un mathématicien, géographe et cartographe flamand, inventeur de la projection
cartographique qui porte son nom. 
</p>
<p>
La projection Mercator est une projection cartographique de la Terre, 
dite «cylindrique», tangente à l'équateur du globe terrestre sur une carte plane. 
Elle s'est imposée comme le planisphère de référence dans le monde grâce à sa précision pour 
les voyages marins. Ce n'est pas, stricto sensu, une projection centrale : le point de latitude 
φ n'est pas envoyé, comme on pourrait 
s'y attendre, sur un point d'ordonnée proportionnelle à tan(φ) mais sur un point d'ordonnée 
proportionnelle à ln[tan(φ/2 + π/4)].
</p>
<p>
La projection Mercator est une projection conforme, c’est-à-dire qu'elle conserve les angles. 
Elle a cependant pour effet des déformations sur les distances et les aires. En effet, 
une distorsion s’accroît au fur et à mesure de l'éloignement de l'équateur vers les pôles. 
Une carte de Mercator ne peut ainsi couvrir les pôles : ils seraient infiniment grands. 
Cela a par exemple pour conséquence la vision d'une égalité de surface entre le Groenland 
et l'Afrique alors que cette dernière est 14 fois plus grande. 
</p>  
<p>
  src: <a href="https://fr.wikipedia.org/wiki/G%C3%A9rard_Mercator">Wikipedia</a>
</p>
                    </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>

@endsection

@section('scripts')
@parent
@endsection